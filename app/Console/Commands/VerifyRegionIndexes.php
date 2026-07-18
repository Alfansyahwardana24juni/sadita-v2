<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifyRegionIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'region:verify-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify all foreign key indexes and composite indexes for region tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Verifying Region Foreign Key Indexes ===\n');

        $this->verifyForeignKeyIndexes();
        $this->verifyCompositeIndexes();
        $this->displayTableIndexes();
        $this->suggestExplainQueries();

        $this->info('\n✓ Index verification complete!');
    }

    /**
     * Verify all foreign key indexes are in place
     */
    private function verifyForeignKeyIndexes()
    {
        $this->line('📋 Foreign Key Indexes Status:');

        $fkChecks = [
            'regencies' => 'province_id',
            'districts' => 'regency_id',
            'villages' => 'district_id',
            'orders' => ['province_id', 'regency_id', 'district_id', 'village_id'],
        ];

        foreach ($fkChecks as $table => $columns) {
            $columns = is_array($columns) ? $columns : [$columns];
            
            foreach ($columns as $column) {
                $result = DB::select("
                    SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = ? 
                    AND COLUMN_NAME = ?
                    AND SEQ_IN_INDEX = 1
                ", [$table, $column]);

                $status = $result[0]->count > 0 ? '✓' : '✗';
                $this->line("  $status {$table}.{$column}");
            }
        }
        $this->newLine();
    }

    /**
     * Verify composite indexes are in place
     */
    private function verifyCompositeIndexes()
    {
        $this->line('📊 Composite Index Status:');

        $compositeIndexes = [
            'regencies' => 'idx_regencies_province_name',
            'districts' => 'idx_districts_regency_name',
            'villages' => 'idx_villages_district_name',
            'orders' => [
                'idx_orders_province_payment',
                'idx_orders_regency_payment',
                'idx_orders_region_cascade',
                'idx_orders_region_hierarchy',
            ],
        ];

        foreach ($compositeIndexes as $table => $indexes) {
            $indexes = is_array($indexes) ? $indexes : [$indexes];
            
            foreach ($indexes as $indexName) {
                $result = DB::select("
                    SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = ? 
                    AND INDEX_NAME = ?
                ", [$table, $indexName]);

                $status = $result[0]->count > 0 ? '✓' : '⚠';
                $statusText = $result[0]->count > 0 ? 'Exists' : 'Missing';
                $this->line("  $status {$table}.{$indexName} - {$statusText}");
            }
        }
        $this->newLine();
    }

    /**
     * Display all indexes on region tables
     */
    private function displayTableIndexes()
    {
        $this->line('🔍 All Indexes on Region Tables:\n');

        $tables = ['provinces', 'regencies', 'districts', 'villages'];

        foreach ($tables as $table) {
            $indexes = DB::select("
                SELECT INDEX_NAME, COLUMN_NAME, SEQ_IN_INDEX 
                FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                ORDER BY INDEX_NAME, SEQ_IN_INDEX
            ", [$table]);

            if (!empty($indexes)) {
                $this->line("<fg=cyan>$table:</>", );
                $currentIndex = null;
                
                foreach ($indexes as $idx) {
                    if ($idx->INDEX_NAME !== $currentIndex) {
                        $this->line("  └─ {$idx->INDEX_NAME}");
                        $currentIndex = $idx->INDEX_NAME;
                    }
                    $this->line("     └─ {$idx->COLUMN_NAME}");
                }
                $this->newLine();
            }
        }
    }

    /**
     * Suggest EXPLAIN queries to verify performance
     */
    private function suggestExplainQueries()
    {
        $this->line('🚀 Suggested EXPLAIN Queries (run in database client):\n');

        $queries = [
            'Cascading regencies by province' => 'EXPLAIN SELECT * FROM regencies WHERE province_id = "11" ORDER BY name;',
            'Cascading districts by regency' => 'EXPLAIN SELECT * FROM districts WHERE regency_id = "1101" ORDER BY name;',
            'Cascading villages by district' => 'EXPLAIN SELECT * FROM villages WHERE district_id = "110101" ORDER BY name;',
            'Orders by province + payment' => 'EXPLAIN SELECT * FROM orders WHERE province_id = "11" AND payment_status = "paid";',
            'Orders with region cascade' => 'EXPLAIN SELECT * FROM orders WHERE province_id = "11" AND regency_id = "1101" AND district_id = "110101";',
        ];

        foreach ($queries as $description => $query) {
            $this->line("<fg=yellow>$description:</>");
            $this->line("  <fg=gray>$query</>");
            $this->line('  Expected: type=range/ref, Extra contains "Using index"');
            $this->newLine();
        }
    }
}
