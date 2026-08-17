<?php

namespace App\Console\Commands;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Import data wilayah Indonesia dari repo GitHub emsifa/api-wilayah-indonesia
 *
 * Source: https://github.com/emsifa/api-wilayah-indonesia
 *         Format JSON: provinces.json, regencies/{province_id}.json, etc.
 *
 * Usage:
 *   php artisan import:regions              -- import semua
 *   php artisan import:regions --only=provinces
 *   php artisan import:regions --fresh      -- truncate dulu, lalu import ulang
 */
class ImportRegions extends Command
{
    protected $signature = 'import:regions
                            {--fresh : Hapus data lama sebelum import}
                            {--only= : Pilih level: provinces|regencies|districts|villages}';

    protected $description = 'Import data wilayah Indonesia lengkap dari GitHub (provinces, regencies, districts, villages)';

    private const BASE_URL = 'https://raw.githubusercontent.com/emsifa/api-wilayah-indonesia/master/static/api/';

    private const SOURCES = [
        'provinces' => 'provinces.json',
        'regencies' => 'regencies/%s.json',
        'districts' => 'districts/%s.json',
        'villages'  => 'villages/%s.json',
    ];

    private const RAJAONGKIR_CITY_IDS = [
        '1271' => 269, // Medan
        '3171' => 152, // Jakarta Selatan
        '3172' => 154, // Jakarta Timur
        '3173' => 151, // Jakarta Pusat
        '3174' => 153, // Jakarta Barat
        '3175' => 155, // Jakarta Utara
        '3201' => 68,  // Bogor
        '3271' => 69,  // Kota Bogor
        '3204' => 22,  // Bandung
        '3273' => 23,  // Kota Bandung
        '3216' => 38,  // Bekasi
        '3275' => 39,  // Kota Bekasi
        '3276' => 107, // Depok
        '3371' => 398, // Semarang
        '3571' => 444, // Surabaya
        '5171' => 114, // Denpasar
        '7306' => 156, // Gowa
        '7308' => 280, // Maros
        '7371' => 300, // Makassar
    ];

    public function handle(): int
    {
        $only  = $this->option('only');
        $fresh = $this->option('fresh');

        $levels = $only
            ? [$only]
            : ['provinces', 'regencies', 'districts', 'villages'];

        // Validasi pilihan
        foreach ($levels as $level) {
            if (!array_key_exists($level, self::SOURCES)) {
                $this->error("Level tidak dikenal: {$level}. Pilihan: provinces, regencies, districts, villages");
                return 1;
            }
        }

        if ($fresh) {
            $this->warn('Menghapus data lama...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Village::truncate();
            District::truncate();
            Regency::truncate();
            Province::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->info('Data lama berhasil dihapus.');
        }

        foreach ($levels as $level) {
            $this->importLevel($level);
        }

        $this->newLine();
        $this->info('✅ Import selesai!');
        $this->table(
            ['Level', 'Total'],
            [
                ['Provinsi',   Province::count()],
                ['Kota/Kab',   Regency::count()],
                ['Kecamatan',  District::count()],
                ['Kelurahan',  Village::count()],
            ]
        );

        return 0;
    }

    private function importLevel(string $level): void
    {
        if ($level === 'regencies') {
            $this->importNestedLevel(
                'regencies',
                Province::query()->orderBy('id')->pluck('id')->all(),
                fn (array $item) => $this->mapRow('regencies', $item),
                fn (array $rows) => Regency::upsert($rows, ['id'], ['name', 'province_id', 'rajaongkir_city_id'])
            );
            return;
        }

        if ($level === 'districts') {
            $this->importNestedLevel(
                'districts',
                Regency::query()->orderBy('id')->pluck('id')->all(),
                fn (array $item) => $this->mapRow('districts', $item),
                fn (array $rows) => District::upsert($rows, ['id'], ['name', 'regency_id'])
            );
            return;
        }

        if ($level === 'villages') {
            $this->importNestedLevel(
                'villages',
                District::query()->orderBy('id')->pluck('id')->all(),
                fn (array $item) => $this->mapRow('villages', $item),
                fn (array $rows) => Village::upsert($rows, ['id'], ['name', 'district_id'])
            );
            return;
        }

        $url = self::BASE_URL . self::SOURCES[$level];
        $this->info("Mengunduh {$level} dari GitHub...");

        try {
            $response = Http::timeout(60)->get($url);

            if (!$response->successful()) {
                $this->error("Gagal mengunduh {$level}: HTTP " . $response->status());
                return;
            }

            $data = $response->json();

            if (empty($data)) {
                $this->warn("Data {$level} kosong.");
                return;
            }

            $this->info("  -> " . count($data) . " records ditemukan. Menyimpan ke database...");
            $bar = $this->output->createProgressBar(count($data));
            $bar->start();

            $chunks = array_chunk($data, 500);

            foreach ($chunks as $chunk) {
                $rows = [];
                foreach ($chunk as $item) {
                    $rows[] = $this->mapRow($level, $item);
                }

                match ($level) {
                    'provinces' => Province::upsert($rows, ['id'], ['name']),
                    'regencies' => Regency::upsert($rows, ['id'], ['name', 'province_id', 'rajaongkir_city_id']),
                    'districts' => District::upsert($rows, ['id'], ['name', 'regency_id']),
                    'villages'  => Village::upsert($rows, ['id'], ['name', 'district_id']),
                };

                $bar->advance(count($chunk));
            }

            $bar->finish();
            $this->newLine();
            $this->info("  {$level} berhasil diimport.");

        } catch (\Exception $e) {
            $this->error("Error saat import {$level}: " . $e->getMessage());
        }
    }

    private function importNestedLevel(string $level, array $parentIds, callable $map, callable $persist): void
    {
        if (empty($parentIds)) {
            $this->warn("Tidak ada parent untuk import {$level}.");
            return;
        }

        $this->info("Mengunduh {$level} dari GitHub...");
        $bar = $this->output->createProgressBar(count($parentIds));
        $bar->start();

        $total = 0;

        foreach ($parentIds as $parentId) {
            $url = self::BASE_URL . sprintf(self::SOURCES[$level], $parentId);

            try {
                $response = Http::timeout(60)->get($url);

                if (! $response->successful()) {
                    $bar->advance();
                    continue;
                }

                $data = $response->json() ?: [];
                $chunks = array_chunk($data, 500);

                foreach ($chunks as $chunk) {
                    $rows = [];
                    foreach ($chunk as $item) {
                        $rows[] = $map($item);
                    }
                    $persist($rows);
                    $total += count($rows);
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("Gagal import {$level} parent {$parentId}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  {$level} berhasil diimport: {$total} records.");
    }

    private function mapRow(string $level, array $item): array
    {
        $now = now()->toDateTimeString();

        return match ($level) {
            'provinces' => [
                'id'         => $item['id'] ?? $item['kode'] ?? $item['code'],
                'name'       => $item['name'] ?? $item['nama'],
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'regencies' => [
                'id'          => $item['id'] ?? $item['kode'] ?? $item['code'],
                'province_id' => $item['province_id'] ?? $item['kode_provinsi'] ?? substr($item['id'] ?? $item['kode'], 0, 2),
                'name'        => $item['name'] ?? $item['nama'],
                'rajaongkir_city_id' => self::RAJAONGKIR_CITY_IDS[$item['id'] ?? $item['kode'] ?? $item['code']] ?? null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            'districts' => [
                'id'         => $item['id'] ?? $item['kode'] ?? $item['code'],
                'regency_id' => $item['regency_id'] ?? $item['kode_kabupaten'] ?? substr($item['id'] ?? $item['kode'], 0, 4),
                'name'       => $item['name'] ?? $item['nama'],
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'villages' => [
                'id'          => $item['id'] ?? $item['kode'] ?? $item['code'],
                'district_id' => $item['district_id'] ?? $item['kode_kecamatan'] ?? substr($item['id'] ?? $item['kode'], 0, 7),
                'name'        => $item['name'] ?? $item['nama'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        };
    }
}
