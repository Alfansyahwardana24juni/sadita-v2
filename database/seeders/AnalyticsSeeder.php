<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $urls = ['/', '/toko', '/toko/katalog', '/saditacare', '/tentang', '/artikel'];
        
        for ($i = 0; $i < 500; $i++) {
            \App\Models\PageVisit::create([
                'url' => $urls[array_rand($urls)],
                'session_id' => \Illuminate\Support\Str::random(10),
                'duration_seconds' => rand(10, 300),
                'created_at' => now()->subDays(rand(0, 30))->subMinutes(rand(0, 60)),
            ]);
        }

        // We assume Orders, Products, and Warehouses exist in the database, if not we skip.
        if (\App\Models\Order::count() == 0 && \App\Models\Product::count() > 0) {
            $products = \App\Models\Product::pluck('id')->toArray();
            $warehouses = \App\Models\Warehouse::pluck('id')->toArray();
            if(!empty($warehouses)) {
                for ($i = 0; $i < 50; $i++) {
                    $order = \App\Models\Order::create([
                        'order_number' => 'ORD-' . rand(1000, 9999),
                        'user_id' => null,
                        'warehouse_id' => $warehouses[array_rand($warehouses)],
                        'status' => 'delivered',
                        'subtotal' => rand(100000, 500000),
                        'total' => rand(100000, 500000),
                        'shipping_cost' => 10000,
                        'customer_name' => 'Dummy Customer',
                        'customer_phone' => '08123456789',
                        'customer_address' => 'Jl. Dummy No. 1',
                        'created_at' => now()->subDays(rand(0, 30)),
                    ]);

                    \App\Models\OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $products[array_rand($products)],
                        'quantity' => rand(1, 5),
                        'price' => rand(50000, 150000),
                        'subtotal' => rand(50000, 150000),
                    ]);
                }
            }
        }
    }
}
