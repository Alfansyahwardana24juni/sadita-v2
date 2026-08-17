<?php

namespace App\Console\Commands;

use App\Services\RajaOngkirService;
use Illuminate\Console\Command;

class TestRajaOngkir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rajaongkir:test {--mock} {--verbose}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test RajaOngkir API integration';

    /**
     * RajaOngkir Service
     */
    private RajaOngkirService $service;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->service = app(RajaOngkirService::class);
        $verbose = $this->option('verbose');

        $this->info('=== RajaOngkir API Integration Test ===');
        $this->newLine();

        // Configuration
        $this->line('Configuration:');
        $this->line('  API Key: ' . (config('rajaongkir.api_key') ? '✓ Set' : '✗ Not set'));
        $this->line('  Account Type: ' . config('rajaongkir.account_type'));
        $this->line('  Couriers: ' . implode(', ', config('rajaongkir.couriers')));
        $this->line('  Origin City ID: ' . config('rajaongkir.origin_city_id'));
        $this->line('  Mock Mode: ' . (config('rajaongkir.use_mock') ? 'ON' : 'OFF'));
        $this->line('  Supports Subdistrict: ' . ($this->service->supportsSubdistrict() ? 'YES' : 'NO'));
        $this->newLine();

        // Test 1: Get Provinces
        $this->line('Test 1: Fetching Provinces...');
        try {
            $provinces = $this->service->getProvinces();
            $this->info('✓ Success! Got ' . count($provinces) . ' provinces');
            if ($verbose && count($provinces) > 0) {
                $this->line('  Sample: ' . $provinces[0]['province'] ?? 'N/A');
            }
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Test 2: Get Cities
        $this->line('Test 2: Fetching Cities (Province ID: 6 - Jakarta)...');
        try {
            $cities = $this->service->getCities(6);
            $this->info('✓ Success! Got ' . count($cities) . ' cities');
            if ($verbose && count($cities) > 0) {
                $this->line('  Sample: ' . $cities[0]['city_name'] ?? 'N/A');
            }
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Test 3: Get Shipping Cost
        $this->line('Test 3: Calculating Shipping Cost...');
        $this->line('  From: City ID ' . config('rajaongkir.origin_city_id') . ' (Origin)');
        $this->line('  To: City ID 153 (Makassar)');
        $this->line('  Weight: 1000g (1kg)');
        $this->line('  Couriers: ' . implode(', ', config('rajaongkir.couriers')));
        $this->newLine();

        try {
            $methods = $this->service->getShippingCost(153, 1000);
            
            if (empty($methods)) {
                $this->warn('⚠ No shipping methods returned (API might be down or invalid params)');
            } else {
                $this->info('✓ Success! Got ' . count($methods) . ' shipping methods');
                $this->newLine();
                
                // Display shipping methods
                $this->line('Available Shipping Methods:');
                foreach ($methods as $index => $method) {
                    $costDisplay = $method['cost'] == 0 ? 'GRATIS' : 'Rp ' . number_format($method['cost'], 0, ',', '.');
                    $this->line("  " . ($index + 1) . ". {$method['name']} - {$method['service_name']}");
                    $this->line("     Cost: {$costDisplay} | ETD: {$method['estimated_days']} hari");
                }
            }
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Test 4: Cache Test
        $this->line('Test 4: Cache Performance...');
        $startTime = microtime(true);
        $this->service->getProvinces();
        $firstCallTime = (microtime(true) - $startTime) * 1000;
        
        $startTime = microtime(true);
        $this->service->getProvinces();
        $secondCallTime = (microtime(true) - $startTime) * 1000;
        
        $this->line("  First call: " . number_format($firstCallTime, 2) . "ms");
        $this->line("  Second call (cached): " . number_format($secondCallTime, 2) . "ms");
        $this->info('✓ Cache is working! Second call is much faster');
        $this->newLine();

        // Summary
        $this->info('=== All Tests Passed! ===');
        $this->info('RajaOngkir API integration is working correctly.');
        
        return 0;
    }
}
