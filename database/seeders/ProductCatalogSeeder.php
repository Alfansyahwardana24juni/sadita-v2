<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductUnit;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Katalog kategori company profile (8 kategori) — icon di storage/app/public/category-images.
        $categories = collect([
            [
                'name' => 'Antibiotik',
                'icon' => 'category-images/01JVXVRR4H921KJB2GKP6VQJT3.png',
                'description' => 'Antibiotik untuk tujuan apapun dalam peternakan: pengobatan pada saat sakit (terapeutik), pengobatan sekelompok hewan ketika sebagian terdiagnosis infeksi klinis (metafilaksis), dan pengobatan pencegahan (profilaksis).',
                'banner_image' => 'https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Vitamin',
                'icon' => 'category-images/01JVXVTRNN2CXGS6DVTE0KPSA4.png',
                'description' => 'Vitamin, elektrolit, mineral, dan suplemen pendukung stamina serta pemulihan ternak.',
                'banner_image' => 'https://images.unsplash.com/photo-1550572017-edd951aa8f72?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Anti Coccidia',
                'icon' => 'category-images/01JVXW13APKB91RMAAZ297GPX2.png',
                'description' => 'Koksidiostat untuk pencegahan dan pengobatan koksidiosis pada unggas dan ternak.',
                'banner_image' => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Antiparasit',
                'icon' => 'category-images/01JVXW29NR7GKWQ90EPHHDQ8PQ.png',
                'description' => 'Obat cacing dan pengendali parasit internal maupun eksternal pada ternak.',
                'banner_image' => 'https://images.unsplash.com/photo-1444858291040-58f756a3bdd6?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Disinfektan',
                'icon' => 'category-images/01JVXWW5YBPJ4G0E8CQ78REQJR.png',
                'description' => 'Desinfektan dan produk kebersihan kandang untuk mendukung biosecurity peternakan.',
                'banner_image' => 'https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Premix',
                'icon' => 'category-images/01JVXWWXTTTD0Y1NZ13YDBADHB.png',
                'description' => 'Premix dan feed additive untuk performa pakan, pertumbuhan, dan produksi telur.',
                'banner_image' => 'https://images.unsplash.com/photo-1606914469633-bd39206ea739?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Akuatik',
                'icon' => 'category-images/01JVXWXFSFTKX7HB6K6P579XGF.png',
                'description' => 'Produk kesehatan dan nutrisi untuk budidaya ikan dan satwa akuatik.',
                'banner_image' => 'https://images.unsplash.com/photo-1524704796725-9fc3044a58b2?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Produk PMK',
                'icon' => 'category-images/01JVXWY7RJPVFENBJV6AQCCJX2.png',
                'description' => 'Produk pendukung penanganan dan pencegahan Penyakit Mulut dan Kuku (PMK) pada ternak besar.',
                'banner_image' => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?auto=format&fit=crop&w=1200&q=80',
            ],
        ])->mapWithKeys(function (array $category, int $index) {
            $model = Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'image' => $category['icon'],
                    'description' => $category['description'],
                    'banner_image' => $category['banner_image'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );

            return [$model->name => $model];
        });

        // Rapikan kategori lama yang tidak lagi dipakai (mis. "Pernapasan", "Sanitasi").
        Category::query()
            ->whereNotIn('slug', $categories->map->slug->all())
            ->each(function (Category $stale) {
                if ($stale->products()->count() === 0) {
                    $stale->delete();
                } else {
                    $stale->update(['is_active' => false]);
                }
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
        ])->values();

        // 1 produk = 1 no. reg. Kementan. "units" = varian kemasan / SKU dengan harga + stok masing-masing.
        $products = [
            [
                'category' => 'Antibiotik',
                'name' => 'Cyprotil',
                'slug' => 'cyprotil',
                'short_description' => 'Ciprofloxacin dan Tylosin Tartrate soluble powder untuk terapi gangguan pernapasan unggas.',
                'description' => 'Cyprotil adalah kombinasi antibiotik soluble powder untuk membantu pengobatan CRD kompleks, Coryza, Colibacillosis, dan infeksi sekunder pada unggas.',
                'composition' => 'Ciprofloxacin, Tylosin Tartrate',
                'indication' => 'CRD, Coryza, Colibacillosis, infeksi sekunder saluran pernapasan.',
                'usage_instruction' => 'Larutkan pada air minum dan gunakan sesuai arahan teknis.',
                'dosage' => '1 g / 2-4 L air minum',
                'withdrawal_time' => '5 hari sebelum potong',
                'registration_number' => 'Contoh KEMENTAN RI No. D 00000000 PKC',
                'animal_type' => 'Unggas / ayam',
                'symptom_tags' => 'crd ngorok pernapasan antibiotik unggas ayam coryza',
                'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&w=700&q=80',
                'brochure_image' => 'https://placehold.co/800x1800/ffffff/800000?text=Brosur+CYPROTIL%0A(contoh+gambar+panjang)',
                'sold_count' => 2000,
                'is_featured' => true,
                'units' => [
                    ['name' => 'Cyprotil 250 mg', 'price' => 125000, 'compare_at_price' => 156000, 'is_default' => true, 'weight' => 120, 'stocks' => [1850, 620]],
                    ['name' => 'Cyprotil 100 mg', 'price' => 62000, 'weight' => 60, 'stocks' => [900, 300]],
                    ['name' => 'Cyprotil 50 mg', 'price' => 35000, 'weight' => 35, 'stocks' => [1200, 450]],
                ],
            ],
            [
                'category' => 'Vitamin', 'name' => 'Vet-Vital B Complex',
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=700&q=80',
                'symptom_tags' => 'vitamin stamina nafsu makan pemulihan lemas', 'sold_count' => 730,
                'units' => [
                    ['name' => 'Botol 100 ml', 'price' => 85000, 'is_default' => true, 'weight' => 150, 'stocks' => [420, 180]],
                    ['name' => 'Botol 500 ml', 'price' => 320000, 'weight' => 620, 'stocks' => [120, 60]],
                ],
            ],
            [
                'category' => 'Disinfektan', 'name' => 'Farm Shield Disinfectant',
                'image' => 'https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=700&q=80',
                'symptom_tags' => 'desinfektan sanitasi kandang kebersihan biosecurity', 'sold_count' => 540,
                'units' => [
                    ['name' => 'Jeriken 1 liter', 'price' => 72500, 'is_default' => true, 'weight' => 1100, 'stocks' => [310, 140]],
                    ['name' => 'Jeriken 5 liter', 'price' => 330000, 'weight' => 5200, 'stocks' => [80, 40]],
                ],
            ],
            [
                'category' => 'Premix', 'name' => 'Layer Plus Premix',
                'image' => 'https://images.unsplash.com/photo-1606914469633-bd39206ea739?auto=format&fit=crop&w=700&q=80',
                'symptom_tags' => 'premix pakan mineral layer produksi telur', 'sold_count' => 1040,
                'units' => [
                    ['name' => 'Sachet 500 g', 'price' => 98000, 'is_default' => true, 'weight' => 520, 'stocks' => [260, 90]],
                ],
            ],
            [
                'category' => 'Vitamin', 'name' => 'Electrolyte Pro',
                'image' => 'https://images.unsplash.com/photo-1550572017-edd951aa8f72?auto=format&fit=crop&w=700&q=80',
                'symptom_tags' => 'elektrolit vitamin lemas stres panas dehidrasi', 'sold_count' => 440,
                'units' => [
                    ['name' => 'Sachet 100 g', 'price' => 32500, 'is_default' => true, 'weight' => 110, 'stocks' => [780, 260]],
                    ['name' => 'Pouch 1 kg', 'price' => 285000, 'weight' => 1050, 'stocks' => [140, 70]],
                ],
            ],
            [
                'category' => 'Vitamin', 'name' => 'Amino Grow Forte',
                'image' => 'https://images.unsplash.com/photo-1516824711718-9c1e683412ac?auto=format&fit=crop&w=700&q=80',
                'symptom_tags' => 'suplemen amino pertumbuhan pakan fcr', 'sold_count' => 590,
                'units' => [
                    ['name' => 'Botol 250 ml', 'price' => 115000, 'is_default' => true, 'weight' => 320, 'stocks' => [190, 110]],
                ],
            ],
            [
                'category' => 'Antibiotik', 'name' => 'Respira-Care Soluble',
                'image' => 'https://images.unsplash.com/photo-1579165466741-7f35e4755660?auto=format&fit=crop&w=700&q=80',
                'symptom_tags' => 'pernapasan ngorok crd antibiotik ayam', 'sold_count' => 850,
                'units' => [
                    ['name' => 'Sachet 250 g', 'price' => 142000, 'is_default' => true, 'weight' => 260, 'stocks' => [144, 74]],
                ],
            ],
            [
                'category' => 'Vitamin', 'name' => 'Calci Mineral Liquid',
                'image' => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=700&q=80',
                'symptom_tags' => 'mineral kalsium vitamin tulang produksi', 'sold_count' => 320,
                'units' => [
                    ['name' => 'Botol 500 ml', 'price' => 67000, 'is_default' => true, 'weight' => 620, 'stocks' => [225, 85]],
                ],
            ],
        ];

        foreach ($products as $index => $productData) {
            $units = $productData['units'];
            unset($productData['units']);

            $defaultUnit = collect($units)->firstWhere('is_default', true) ?? $units[0];

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
                    'pack' => $defaultUnit['name'],
                    'animal_type' => $productData['animal_type'] ?? 'Unggas dan ternak umum',
                    'symptom_tags' => $productData['symptom_tags'],
                    'price' => $defaultUnit['price'],
                    'compare_at_price' => $defaultUnit['compare_at_price'] ?? null,
                    'image' => $productData['image'],
                    'brochure_image' => $productData['brochure_image'] ?? null,
                    'sold_count' => $productData['sold_count'] ?? 0,
                    'status' => 'active',
                    'is_featured' => $productData['is_featured'] ?? $index < 4,
                    'sort_order' => $index + 1,
                ],
            );

            foreach ($units as $unitIndex => $unitData) {
                $stocks = $unitData['stocks'];
                $unitSlug = $unitData['slug'] ?? (
                    Str::startsWith(Str::slug($unitData['name']), Str::slug($productData['name']))
                        ? Str::slug($unitData['name'])
                        : Str::slug($productData['name'] . ' ' . $unitData['name'])
                );
                $unit = ProductUnit::updateOrCreate(
                    ['slug' => $unitSlug],
                    [
                        'product_id' => $product->id,
                        'name' => $unitData['name'],
                        'sku' => $unitData['sku'] ?? null,
                        'price' => $unitData['price'],
                        'compare_at_price' => $unitData['compare_at_price'] ?? null,
                        'weight' => $unitData['weight'] ?? 500,
                        'length' => $unitData['length'] ?? 0,
                        'width' => $unitData['width'] ?? 0,
                        'height' => $unitData['height'] ?? 0,
                        'is_default' => $unitData['is_default'] ?? false,
                        'is_active' => true,
                        'sort_order' => $unitIndex,
                    ],
                );

                foreach ($warehouses as $warehouseIndex => $warehouse) {
                    ProductStock::updateOrCreate(
                        ['product_unit_id' => $unit->id, 'warehouse_id' => $warehouse->id],
                        [
                            'product_id' => $product->id,
                            'stock' => $stocks[$warehouseIndex] ?? 0,
                            'reserved_stock' => 0,
                            'low_stock_threshold' => 10,
                        ],
                    );
                }
            }
        }
    }
}
