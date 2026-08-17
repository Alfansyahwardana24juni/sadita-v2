<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Antibiotik', 'slug' => 'antibiotik', 'icon' => 'vaccines', 'sort_order' => 1],
            ['name' => 'Vitamin', 'slug' => 'vitamin', 'icon' => 'medication', 'sort_order' => 2],
            ['name' => 'Desinfektan', 'slug' => 'desinfektan', 'icon' => 'cleaning_services', 'sort_order' => 3],
            ['name' => 'Premix', 'slug' => 'premix', 'icon' => 'nutrition', 'sort_order' => 4],
        ])->mapWithKeys(fn ($category) => [
            $category['slug'] => Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['is_active' => true]
            ),
        ]);

        $gallery = [
            'https://images.unsplash.com/photo-1587854692152-cbe660dbde0b?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1631217314997-dc41ceaa2014?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1576091160640-112b8217b85b?w=500&h=500&fit=crop',
        ];

        $products = [
            ['Cyprotil', 'Antibiotik Spektrum Luas untuk Unggas', 'antibiotik', 125000, 156000, 1850, true],
            ['Tetravet', 'Antibiotik untuk infeksi saluran napas', 'antibiotik', 95000, 120000, 960, true],
            ['Amoxicillin', 'Antibiotik oral untuk ternak', 'antibiotik', 85000, 105000, 740, false],
            ['Doxycycline', 'Terapi infeksi bakteri pada unggas', 'antibiotik', 110000, 140000, 520, false],
            ['Erythromycin', 'Dukungan terapi penyakit respirasi', 'antibiotik', 115000, 145000, 430, false],
            ['Sulfamet', 'Sediaan sulfa untuk unggas', 'antibiotik', 75000, 95000, 680, false],
            ['Vitachick Plus', 'Vitamin dan elektrolit untuk DOC', 'vitamin', 38000, 45000, 2200, true],
            ['Aminovit', 'Asam amino dan vitamin unggas', 'vitamin', 68000, 82000, 1300, false],
            ['Biosecure Farm', 'Desinfektan kandang konsentrat', 'desinfektan', 145000, 175000, 410, true],
            ['Premix Layer', 'Premix mineral untuk ayam layer', 'premix', 188000, 220000, 350, true],
        ];

        foreach ($products as [$name, $subtitle, $categorySlug, $price, $originalPrice, $stock, $featured]) {
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $categories[$categorySlug]->id,
                    'sku' => strtoupper(Str::slug($name, '-')),
                    'name' => $name,
                    'subtitle' => $subtitle,
                    'description' => 'Produk kesehatan ternak untuk membantu menjaga performa dan menekan risiko gangguan kesehatan di kandang.',
                    'benefits' => [
                        'Mudah diaplikasikan sesuai kebutuhan peternakan',
                        'Cocok untuk program kesehatan ternak terjadwal',
                        'Didukung jaringan distribusi gudang SADITA',
                    ],
                    'specifications' => [
                        'Bentuk' => $categorySlug === 'desinfektan' ? 'Cair' : 'Powder',
                        'Kemasan' => '25 kg/bag',
                        'Dosis' => 'Ikuti petunjuk teknis atau konsultasi dokter hewan',
                        'Garansi' => '100% original dari produsen',
                    ],
                    'price' => $price,
                    'original_price' => $originalPrice,
                    'stock' => $stock,
                    'sold_count' => random_int(120, 3200),
                    'condition' => 'Baru',
                    'image' => $gallery[0],
                    'gallery' => $gallery,
                    'seller_name' => 'PT. Satwa Medika Utama',
                    'warehouse' => $categorySlug === 'premix' ? 'Makassar, Sulawesi Selatan' : 'Bogor, Jawa Barat',
                    'rating' => 4.8,
                    'review_count' => 248,
                    'is_active' => true,
                    'is_featured' => $featured,
                ]
            );

            if ($product->slug === 'cyprotil') {
                Review::updateOrCreate(
                    ['product_id' => $product->id, 'customer_name' => 'Budi Santoso'],
                    [
                        'rating' => 5,
                        'comment' => 'Produk sangat bagus, ayam saya cepat pulih dari penyakit. Harga terjangkau dan kualitas terjamin.',
                        'reviewed_at' => now()->subWeeks(2),
                        'is_published' => true,
                    ]
                );

                Review::updateOrCreate(
                    ['product_id' => $product->id, 'customer_name' => 'Siti Nurhaliza'],
                    [
                        'rating' => 5,
                        'comment' => 'Efektif untuk mengatasi masalah pada ternak. Rekomendasi dari tetangga dan tidak mengecewakan.',
                        'reviewed_at' => now()->subMonth(),
                        'is_published' => true,
                    ]
                );
            }
        }
    }
}
