<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\HeroBanner::insert([
            [
                'title' => 'Solusi sehat ternak anda',
                'subtitle' => 'Cari produk, konsultasi gejala ternak, dan pilih gudang terdekat sebelum pesan.',
                'image_url' => 'https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=1200&q=80',
                'link_url' => '/toko/katalog',
                'button_text' => 'Cari Produk',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Lindungi Peternakan Anda',
                'subtitle' => 'Peralatan sanitasi dan perlindungan menyeluruh dari penyakit unggas menular.',
                'image_url' => 'https://images.unsplash.com/photo-1627943248386-4f40dcb6cbcd?auto=format&fit=crop&w=1200&q=80',
                'link_url' => '/saditacare',
                'button_text' => 'Tanya Dokter AI',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Nutrisi Berkualitas Tinggi',
                'subtitle' => 'Produk pakan dan vitamin terbaik untuk mempercepat pertumbuhan bobot broiler.',
                'image_url' => 'https://images.unsplash.com/photo-1588693895082-f54f15d29e7f?auto=format&fit=crop&w=1200&q=80',
                'link_url' => '/toko/katalog',
                'button_text' => 'Beli Vitamin',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
