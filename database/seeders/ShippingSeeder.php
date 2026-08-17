<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================
        // SHIPPING ZONES
        // =========================================

        // Zone 1: Makassar & Maros (Kurir SADITA Available)
        ShippingZone::create([
            'zone_name' => 'Makassar & Maros',
            'cities' => ['Makassar', 'Maros'],
            'shipping_methods' => ['kurir_sadita', 'pickup'],
            'free_shipping_threshold' => 10.00, // Free jika >= 10kg
            'flat_rate' => 50000.00, // Rp 50,000 jika < 10kg
            'is_active' => true,
        ]);

        // Zone 2: Luar Makassar/Maros (RajaOngkir Only)
        ShippingZone::create([
            'zone_name' => 'Luar Kota',
            'cities' => ['*'], // Semua kota lain
            'shipping_methods' => ['rajaongkir', 'pickup'],
            'free_shipping_threshold' => null,
            'flat_rate' => null,
            'is_active' => true,
        ]);

        // =========================================
        // WAREHOUSES
        // =========================================

        // Warehouse 1: Makassar
        // CATATAN: rajaongkir_city_id harus dicari dari API RajaOngkir
        // Untuk Makassar, ID nya adalah 153
        Warehouse::create([
            'name' => 'Gudang Makassar',
            'slug' => 'gudang-makassar',
            'code' => 'MKS-01',
            'city' => 'Makassar',
            'province' => 'Sulawesi Selatan',
            'rajaongkir_city_id' => 153, // ID RajaOngkir untuk Makassar
            'address' => 'Jl. A. P. Pettarani No. 123, Makassar',
            'phone' => '0411-123456',
            'whatsapp' => '081234567890',
            'service_area' => 'Makassar, Maros, dan sekitarnya',
            'delivery_estimate' => '1-2 hari kerja',
            'is_active' => true,
        ]);

        // Warehouse 2: Maros (Opsional - jika ada gudang di Maros)
        // Uncomment jika ada gudang di Maros
        /*
        Warehouse::create([
            'name' => 'Gudang Maros',
            'slug' => 'gudang-maros',
            'code' => 'MRS-01',
            'city' => 'Maros',
            'province' => 'Sulawesi Selatan',
            'rajaongkir_city_id' => 280, // ID RajaOngkir untuk Maros
            'address' => 'Jl. Trans Sulawesi, Maros',
            'phone' => '0411-654321',
            'whatsapp' => '081234567891',
            'service_area' => 'Maros dan sekitarnya',
            'delivery_estimate' => '1-2 hari kerja',
            'is_active' => true,
        ]);
        */

        $this->command->info('Shipping zones and warehouses seeded successfully!');
        $this->command->warn('⚠️  PENTING: Update rajaongkir_city_id di tabel warehouses sesuai dengan ID kota dari RajaOngkir API');
    }
}
