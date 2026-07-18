<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Create article categories
        $categories = [
            ['name' => 'Tips Ternak', 'slug' => 'tips-ternak', 'description' => 'Tips dan trik merawat ternak dengan optimal'],
            ['name' => 'Kesehatan Ternak', 'slug' => 'kesehatan-ternak', 'description' => 'Panduan kesehatan dan pencegahan penyakit ternak'],
            ['name' => 'Nutrisi & Pakan', 'slug' => 'nutrisi-pakan', 'description' => 'Panduan nutrisi dan manajemen pakan ternak'],
            ['name' => 'Produk SADITA', 'slug' => 'produk-sadita', 'description' => 'Informasi dan review produk SADITA'],
        ];

        $categoryModels = [];
        foreach ($categories as $category) {
            $categoryModels[] = ArticleCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Create articles
        $articles = [
            [
                'category' => 'Kesehatan Ternak',
                'title' => 'Panduan Lengkap Pencegahan Penyakit CRD pada Ayam Broiler',
                'slug' => 'panduan-pencegahan-crd-ayam-broiler',
                'excerpt' => 'Chronic Respiratory Disease (CRD) adalah penyakit pernapasan kronis yang sering menyerang ayam broiler. Berikut adalah panduan lengkap pencegahannya.',
                'content' => '<h2>Apa itu CRD?</h2><p>CRD adalah penyakit pernapasan yang disebabkan oleh infeksi bakteri kompleks. Gejala utamanya adalah ngorok, batuk, dan produksi menurun.</p><h2>Pencegahan CRD</h2><ul><li>Jaga kebersihan kandang dengan desinfektan berkala</li><li>Pastikan ventilasi kandang optimal</li><li>Gunakan vitamin dan suplemen untuk stamina</li><li>Isolasi ternak yang sakit</li><li>Vaksinasi sesuai program</li></ul><h2>Penanganan CRD</h2><p>Gunakan antibiotik seperti Cyprotil yang terbukti efektif mengatasi CRD kompleks.</p>',
                'author' => 'Tim Teknis SADITA',
                'views_count' => 1250,
            ],
            [
                'category' => 'Tips Ternak',
                'title' => 'Cara Meningkatkan Produksi Telur Ayam Petelur dengan Manajemen Pakan Tepat',
                'slug' => 'meningkatkan-produksi-telur-manajemen-pakan',
                'excerpt' => 'Produksi telur yang optimal memerlukan manajemen pakan yang tepat. Artikel ini memberikan panduan lengkap untuk meningkatkan produktivitas ayam petelur Anda.',
                'content' => '<h2>Pentingnya Manajemen Pakan</h2><p>Pakan berkualitas adalah kunci utama peningkatan produksi telur. Minimal 60% biaya produksi adalah untuk pakan.</p><h2>Tips Manajemen Pakan</h2><ul><li>Gunakan pakan layer dengan kandungan protein 16-18%</li><li>Pastikan mineral dan vitamin tercukupi dengan premix berkualitas</li><li>Berikan pakan sesuai umur dan kebutuhan</li><li>Sediakan air minum yang bersih dan cukup</li><li>Monitor konsumsi pakan harian</li></ul><h2>Produk Rekomendasi</h2><p>Layer Plus Premix SADITA membantu melengkapi kebutuhan mineral dan vitamin untuk produksi telur optimal.</p>',
                'author' => 'Agronomi SADITA',
                'views_count' => 980,
            ],
            [
                'category' => 'Nutrisi & Pakan',
                'title' => 'Manfaat Elektrolit untuk Ternak Saat Cuaca Panas',
                'slug' => 'manfaat-elektrolit-cuaca-panas',
                'excerpt' => 'Cuaca panas menyebabkan dehidrasi pada ternak. Elektrolit membantu menjaga keseimbangan cairan dan mineral tubuh ternak Anda.',
                'content' => '<h2>Tanda-tanda Dehidrasi</h2><p>Ternak yang dehidrasi akan terlihat lesu, nafsu makan berkurang, dan produksi menurun signifikan.</p><h2>Solusi Elektrolit</h2><p>Memberikan elektrolit dalam air minum membantu:</p><ul><li>Mengganti mineral yang hilang melalui keringat</li><li>Meningkatkan nafsu makan</li><li>Menjaga stamina dan performa</li><li>Mengurangi stres panas</li></ul><h2>Cara Pemberian</h2><p>Electrolyte Pro SADITA dapat diberikan setiap hari selama musim panas atau saat produksi menurun akibat panas.</p>',
                'author' => 'Nutrisionis SADITA',
                'views_count' => 756,
            ],
            [
                'category' => 'Produk SADITA',
                'title' => 'Keunggulan Cyprotil 250 mg untuk Terapi Pernapasan Ayam',
                'slug' => 'keunggulan-cyprotil-250mg',
                'excerpt' => 'Cyprotil 250 mg adalah solusi terbaik untuk mengatasi penyakit pernapasan pada ayam. Kombinasi Ciprofloxacin dan Tylosin Tartrate terbukti efektif.',
                'content' => '<h2>Komposisi Cyprotil</h2><p>Cyprotil mengandung kombinasi dua antibiotik kuat:</p><ul><li><strong>Ciprofloxacin</strong>: Fluorokuinolon untuk infeksi bakteri gram negatif</li><li><strong>Tylosin Tartrate</strong>: Makrolida untuk infeksi bakteri kompleks</li></ul><h2>Keunggulan</h2><ul><li>Terapi ganda yang saling mendukung</li><li>Larutkan mudah dalam air minum</li><li>Cocok untuk CRD kompleks dan Coryza</li><li>Registered dan tersertifikasi</li></ul><h2>Hasil Nyata</h2><p>Peternak di Bogor dan Sukabumi merasakan peningkatan signifikan dalam 3-5 hari terapi dengan Cyprotil.</p>',
                'author' => 'R&D SADITA',
                'views_count' => 2100,
            ],
            [
                'category' => 'Tips Ternak',
                'title' => '5 Kesalahan Umum dalam Manajemen Biosecurity Kandang',
                'slug' => '5-kesalahan-biosecurity-kandang',
                'excerpt' => 'Biosecurity yang buruk adalah penyebab utama penyakit masuk ke kandang. Hindari 5 kesalahan umum ini untuk melindungi ternak Anda.',
                'content' => '<h2>Kesalahan 1: Kurangnya Desinfeksi</h2><p>Kandang yang tidak didesinfeksi secara berkala menjadi sarang penyakit. Gunakan desinfektan seperti Farm Shield secara rutin.</p><h2>Kesalahan 2: Akses Umum ke Kandang</h2><p>Batasi akses orang yang masuk ke kandang untuk mencegah penularan penyakit dari luar.</p><h2>Kesalahan 3: Pencampuran Umur Ternak</h2><p>Hindari mencampur ternak dari berbagai umur yang berbeda karena rentan terhadap penyakit.</p><h2>Kesalahan 4: Ventilasi Buruk</h2><p>Kandang dengan ventilasi buruk menjadi tempat berkembang biaknya penyakit pernapasan.</p><h2>Kesalahan 5: Pakan dari Sumber Tidak Terpercaya</h2><p>Selalu gunakan pakan dan premix dari sumber terpercaya seperti SADITA.</p>',
                'author' => 'Konsultan Lapang SADITA',
                'views_count' => 1120,
            ],
            [
                'category' => 'Kesehatan Ternak',
                'title' => 'Pentingnya Vitamin B Complex untuk Pemulihan Ternak Sakit',
                'slug' => 'vitamin-b-complex-pemulihan-ternak',
                'excerpt' => 'Vitamin B Complex berperan penting dalam metabolisme dan pemulihan ternak yang sedang sakit atau stress.',
                'content' => '<h2>Fungsi Vitamin B Complex</h2><ul><li>Mendukung metabolisme energi</li><li>Mempercepat pemulihan dari stres</li><li>Meningkatkan nafsu makan</li><li>Memperkuat sistem imun</li></ul><h2>Kapan Memberikan</h2><p>Berikan Vet-Vital B Complex dalam kasus:</p><ul><li>Ternak sedang dalam pemulihan dari penyakit</li><li>Stres pemindahan atau perubahan lingkungan</li><li>Produksi menurun drastis</li><li>Program vaksinasi</li></ul><h2>Dosis & Cara Pemberian</h2><p>Vitamin B Complex SADITA dapat diberikan melalui air minum sesuai arahan pada kemasan produk.</p>',
                'author' => 'Tim Nutrisi SADITA',
                'views_count' => 654,
            ],
            [
                'category' => 'Nutrisi & Pakan',
                'title' => 'Bagaimana Memilih Premix Pakan yang Tepat untuk Ternak Anda',
                'slug' => 'memilih-premix-pakan-tepat',
                'excerpt' => 'Premix yang tepat adalah investasi untuk kesehatan dan produktivitas ternak jangka panjang. Pelajari cara memilih premix berkualitas.',
                'content' => '<h2>Apa itu Premix?</h2><p>Premix adalah campuran vitamin, mineral, dan nutrisi esensial lainnya yang ditambahkan ke pakan untuk melengkapi kebutuhan nutrisi ternak.</p><h2>Kriteria Premix Berkualitas</h2><ul><li>Memiliki sertifikasi resmi dari kementerian terkait</li><li>Komposisi jelas dan tertera pada kemasan</li><li>Dari produsen terpercaya dengan track record baik</li><li>Sesuai dengan jenis dan umur ternak Anda</li></ul><h2>Produk Pilihan SADITA</h2><p>Layer Plus Premix dirancang khusus untuk ayam petelur dengan formula balanced untuk produksi telur optimal.</p>',
                'author' => 'Ahli Pakan SADITA',
                'views_count' => 892,
            ],
        ];

        foreach ($articles as $articleData) {
            $category = ArticleCategory::where('name', $articleData['category'])->first();
            
            if ($category) {
                Article::updateOrCreate(
                    ['slug' => $articleData['slug']],
                    [
                        'category_id' => $category->id,
                        'title' => $articleData['title'],
                        'excerpt' => $articleData['excerpt'],
                        'content' => $articleData['content'],
                        'featured_image' => 'https://images.unsplash.com/photo-1500595046891-9c05b5d43e6f?auto=format&fit=crop&w=1200&q=80',
                        'author' => $articleData['author'],
                        'status' => 'published',
                        'published_at' => now()->subDays(rand(1, 30)),
                        'views_count' => $articleData['views_count'],
                        'sort_order' => 1,
                    ]
                );
            }
        }
    }
}
