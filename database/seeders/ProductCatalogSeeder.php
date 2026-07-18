<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Review;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Pernapasan', 'description' => 'Produk untuk gangguan pernapasan, CRD, dan infeksi sekunder.'],
            ['name' => 'Vitamin', 'description' => 'Vitamin, elektrolit, mineral, dan suplemen pendukung stamina.'],
            ['name' => 'Sanitasi', 'description' => 'Desinfektan dan produk kebersihan kandang.'],
            ['name' => 'Premix', 'description' => 'Premix dan feed additive untuk performa pakan.'],
            ['name' => 'Antibiotik', 'description' => 'Produk antibiotik hewan sesuai arahan teknis.'],
        ])->mapWithKeys(function (array $category, int $index) {
            $model = Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );

            return [$model->name => $model];
        });

        $warehouses = collect([
            [
                'name' => 'SADITA Bogor Timur',
                'slug' => 'sadita-bogor-timur',
                'code' => 'BGR',
                'address' => 'Jl. Industri Kesehatan No. 45, Bogor Timur',
                'city' => 'Bogor',
                'province' => 'Jawa Barat',
                'postal_code' => '16146',
                'phone' => '0251-000000',
                'whatsapp' => '6281234567890',
                'service_area' => 'Jawa Barat, Jabodetabek, dan sekitarnya',
                'delivery_estimate' => '1-2 hari',
                'image' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?auto=format&fit=crop&w=1000&q=80',
            ],
            [
                'name' => 'SADITA Makassar',
                'slug' => 'sadita-makassar',
                'code' => 'MKS',
                'address' => 'Jl. Perintis Distribusi No. 12, Makassar',
                'city' => 'Makassar',
                'province' => 'Sulawesi Selatan',
                'postal_code' => '90245',
                'phone' => '0411-000000',
                'whatsapp' => '6281234567890',
                'service_area' => 'Sulawesi, Maluku, Nusa Tenggara, dan Papua',
                'delivery_estimate' => '2-4 hari',
                'image' => 'https://images.unsplash.com/photo-1605902711622-cfb43c4437d1?auto=format&fit=crop&w=1000&q=80',
            ],
        ])->mapWithKeys(fn (array $warehouse) => [
            $warehouse['slug'] => Warehouse::updateOrCreate(['slug' => $warehouse['slug']], $warehouse + ['is_active' => true]),
        ]);

        $products = [
            [
                'category' => 'Pernapasan',
                'name' => 'Cyprotil 250 mg',
                'slug' => 'cyprotil-250-mg',
                'pack' => 'Antibiotik - 100g',
                'short_description' => 'Ciprofloxacin dan Tylosin Tartrate soluble powder untuk terapi gangguan pernapasan unggas.',
                'description' => 'Cyprotil 250 mg adalah kombinasi antibiotik soluble powder untuk membantu pengobatan CRD kompleks, Coryza, Colibacillosis, dan infeksi sekunder pada unggas.',
                'composition' => 'Ciprofloxacin, Tylosin Tartrate',
                'indication' => 'CRD, Coryza, Colibacillosis, infeksi sekunder saluran pernapasan.',
                'usage_instruction' => 'Larutkan pada air minum dan gunakan sesuai arahan teknis.',
                'dosage' => '1 g / 2-4 L air minum',
                'withdrawal_time' => '5 hari sebelum potong',
                'registration_number' => 'Contoh KEMENTAN RI No. D 00000000 PKC',
                'animal_type' => 'Unggas / ayam',
                'symptom_tags' => 'crd ngorok pernapasan antibiotik unggas ayam coryza',
                'price' => 125000,
                'compare_at_price' => 156000,
                'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&w=700&q=80',
                'rating' => 4.9,
                'reviews_count' => 248,
                'sold_count' => 2000,
                'is_featured' => true,
                'stocks' => [1850, 620],
            ],
            ['category' => 'Vitamin', 'name' => 'Vet-Vital B Complex', 'pack' => 'Botol 100 ml', 'price' => 85000, 'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=700&q=80', 'rating' => 4.8, 'reviews_count' => 92, 'stocks' => [420, 180], 'symptom_tags' => 'vitamin stamina nafsu makan pemulihan lemas'],
            ['category' => 'Sanitasi', 'name' => 'Farm Shield Disinfectant', 'pack' => 'Jeriken 1 liter', 'price' => 72500, 'image' => 'https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=700&q=80', 'rating' => 4.7, 'reviews_count' => 67, 'stocks' => [310, 140], 'symptom_tags' => 'desinfektan sanitasi kandang kebersihan biosecurity'],
            ['category' => 'Premix', 'name' => 'Layer Plus Premix', 'pack' => 'Sachet 500 g', 'price' => 98000, 'image' => 'https://images.unsplash.com/photo-1606914469633-bd39206ea739?auto=format&fit=crop&w=700&q=80', 'rating' => 4.8, 'reviews_count' => 131, 'stocks' => [260, 90], 'symptom_tags' => 'premix pakan mineral layer produksi telur'],
            ['category' => 'Vitamin', 'name' => 'Electrolyte Pro', 'pack' => 'Sachet 100 g', 'price' => 32500, 'image' => 'https://images.unsplash.com/photo-1550572017-edd951aa8f72?auto=format&fit=crop&w=700&q=80', 'rating' => 4.6, 'reviews_count' => 55, 'stocks' => [780, 260], 'symptom_tags' => 'elektrolit vitamin lemas stres panas dehidrasi'],
            ['category' => 'Vitamin', 'name' => 'Amino Grow Forte', 'pack' => 'Botol 250 ml', 'price' => 115000, 'image' => 'https://images.unsplash.com/photo-1516824711718-9c1e683412ac?auto=format&fit=crop&w=700&q=80', 'rating' => 4.7, 'reviews_count' => 74, 'stocks' => [190, 110], 'symptom_tags' => 'suplemen amino pertumbuhan pakan fcr'],
            ['category' => 'Pernapasan', 'name' => 'Respira-Care Soluble', 'pack' => 'Sachet 250 g', 'price' => 142000, 'image' => 'https://images.unsplash.com/photo-1579165466741-7f35e4755660?auto=format&fit=crop&w=700&q=80', 'rating' => 4.8, 'reviews_count' => 106, 'stocks' => [144, 74], 'symptom_tags' => 'pernapasan ngorok crd antibiotik ayam'],
            ['category' => 'Vitamin', 'name' => 'Calci Mineral Liquid', 'pack' => 'Botol 500 ml', 'price' => 67000, 'image' => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=700&q=80', 'rating' => 4.5, 'reviews_count' => 41, 'stocks' => [225, 85], 'symptom_tags' => 'mineral kalsium vitamin tulang produksi'],
        ];

        foreach ($products as $index => $productData) {
            $stocks = $productData['stocks'];
            unset($productData['stocks']);

            $product = Product::updateOrCreate(
                ['slug' => $productData['slug'] ?? Str::slug($productData['name'])],
                [
                    'category_id' => $categories[$productData['category']]->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'] ?? 'Produk SADITA untuk kebutuhan kesehatan dan performa ternak. Data lengkap akan disesuaikan dengan katalog resmi.',
                    'short_description' => $productData['short_description'] ?? 'Produk SADITA untuk kebutuhan lapangan peternak.',
                    'composition' => $productData['composition'] ?? null,
                    'indication' => $productData['indication'] ?? null,
                    'usage_instruction' => $productData['usage_instruction'] ?? 'Gunakan sesuai label produk dan arahan teknis.',
                    'dosage' => $productData['dosage'] ?? 'Konfirmasi dosis ke tim SADITA.',
                    'withdrawal_time' => $productData['withdrawal_time'] ?? 'Ikuti label produk.',
                    'registration_number' => $productData['registration_number'] ?? 'Data registrasi menunggu katalog resmi.',
                    'pack' => $productData['pack'],
                    'animal_type' => $productData['animal_type'] ?? 'Unggas dan ternak umum',
                    'symptom_tags' => $productData['symptom_tags'],
                    'price' => $productData['price'],
                    'compare_at_price' => $productData['compare_at_price'] ?? null,
                    'image' => $productData['image'],
                    'rating' => $productData['rating'],
                    'reviews_count' => $productData['reviews_count'],
                    'sold_count' => $productData['sold_count'] ?? ($productData['reviews_count'] * 8),
                    'status' => 'active',
                    'is_featured' => $productData['is_featured'] ?? $index < 4,
                    'sort_order' => $index + 1,
                ],
            );

            foreach ($warehouses->values() as $warehouseIndex => $warehouse) {
                ProductStock::updateOrCreate(
                    ['product_id' => $product->id, 'warehouse_id' => $warehouse->id],
                    ['stock' => $stocks[$warehouseIndex], 'reserved_stock' => 0, 'low_stock_threshold' => 10],
                );
            }
        }

        $cyprotil = Product::where('slug', 'cyprotil-250-mg')->first();

        if ($cyprotil) {
            foreach ([
                ['customer_name' => 'Budi Santoso', 'location' => 'Bogor', 'comment' => 'Larutnya cepat dan ayam lebih stabil setelah program terapi. Pengiriman dari Bogor juga cepat.'],
                ['customer_name' => 'Rina Farm', 'location' => 'Sukabumi', 'comment' => 'Kemasan rapi, batch jelas, dan stok selalu tersedia saat butuh repeat order.'],
            ] as $review) {
                Review::updateOrCreate(
                    ['product_id' => $cyprotil->id, 'customer_name' => $review['customer_name']],
                    $review + ['rating' => 5, 'is_verified' => true],
                );
            }
        }
    }
}
