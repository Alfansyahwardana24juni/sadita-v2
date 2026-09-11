<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SaditaRealProductsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');
        $warehouses = Warehouse::all();

        // Fallback category helper
        $getCategoryId = function ($name) use ($categories) {
            return $categories->get($name)?->id ?? $categories->first()->id;
        };

        $realProducts = [
            [
                'category' => 'Antibiotik',
                'name' => 'Doxycycline Injection',
                'slug' => 'doxycycline-injection',
                'pack' => 'Vial 100 ml',
                'description' => 'Antibiotik pilihan untuk CRD, snot, kolibasilosis (ayam, sapi).',
                'indication' => 'CRD, snot, kolibasilosis.',
                'usage_instruction' => 'Injeksi IM (Intramuskular).',
                'dosage' => '1ml/10kg BB, 1x sehari, 3-5 hari',
                'symptom_tags' => 'crd snot kolibasilosis antibiotik ayam sapi ngorok',
                'price' => 120000,
                'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde0b?auto=format&fit=crop&w=700&q=80',
                'stocks' => [500, 200], // [Bogor, Makassar]
            ],
            [
                'category' => 'Antibiotik',
                'name' => 'Enrofloxacin Oral',
                'slug' => 'enrofloxacin-oral',
                'pack' => 'Botol 1 Liter',
                'description' => 'Untuk infeksi saluran pernapasan dan pencernaan unggas.',
                'indication' => 'Infeksi saluran pernapasan dan pencernaan.',
                'usage_instruction' => 'Oral atau dicampur air minum.',
                'dosage' => '10mg/kg BB, 1x sehari, 3-5 hari',
                'symptom_tags' => 'pernapasan pencernaan antibiotik ayam mencret ngorok',
                'price' => 180000,
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=700&q=80',
                'stocks' => [300, 150],
            ],
            [
                'category' => 'Antibiotik',
                'name' => 'Oxytetracycline Long Acting',
                'slug' => 'oxytetracycline-long-acting',
                'pack' => 'Vial 100 ml',
                'description' => 'Antibiotik berspektrum luas untuk pneumonia, septikemia pada sapi dan kambing.',
                'indication' => 'Pneumonia, septikemia.',
                'usage_instruction' => 'Injeksi IM/IV (Long acting).',
                'dosage' => '1ml/10kg BB, 1x pemberian',
                'symptom_tags' => 'sapi kambing pneumonia antibiotik',
                'price' => 145000,
                'image' => 'https://images.unsplash.com/photo-1579165466741-7f35e4755660?auto=format&fit=crop&w=700&q=80',
                'stocks' => [120, 80],
            ],
            [
                'category' => 'Antibiotik',
                'name' => 'Amoxicillin Bolus',
                'slug' => 'amoxicillin-bolus',
                'pack' => 'Box 50 Bolus',
                'description' => 'Bolus antibiotik untuk mastitis dan metritis pada sapi.',
                'indication' => 'Mastitis, metritis.',
                'usage_instruction' => 'Intramammary ke setiap kuarter yang terinfeksi.',
                'dosage' => '1 bolus, 1x sehari, 3 hari',
                'symptom_tags' => 'sapi mastitis metritis bolus antibiotik',
                'price' => 250000,
                'image' => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=700&q=80',
                'stocks' => [200, 100],
            ],
            [
                'category' => 'Vitamin',
                'name' => 'Vita Stress',
                'slug' => 'vita-stress',
                'pack' => 'Sachet 250g',
                'description' => 'Vitamin anti stres untuk pemulihan pasca sakit, stres transport, dan setelah vaksinasi.',
                'indication' => 'Pemulihan pasca sakit, stres transportasi, pasca vaksinasi.',
                'usage_instruction' => 'Campurkan dalam air minum.',
                'dosage' => '1g/4-5 liter air minum, 3-5 hari berturut-turut',
                'symptom_tags' => 'stres lemas vitamin ayam vaksinasi',
                'price' => 45000,
                'image' => 'https://images.unsplash.com/photo-1550572017-edd951aa8f72?auto=format&fit=crop&w=700&q=80',
                'stocks' => [1000, 450],
            ],
            [
                'category' => 'Vitamin',
                'name' => 'B-Complex Injection',
                'slug' => 'b-complex-injection',
                'pack' => 'Vial 100 ml',
                'description' => 'Meningkatkan nafsu makan turun, lemas, anemia pada sapi, kambing.',
                'indication' => 'Nafsu makan turun, lemas, anemia.',
                'usage_instruction' => 'Injeksi IM/SC.',
                'dosage' => '1ml/20kg BB, 1x sehari, 3 hari',
                'symptom_tags' => 'nafsu makan lemas anemia vitamin injeksi',
                'price' => 95000,
                'image' => 'https://images.unsplash.com/photo-1516824711718-9c1e683412ac?auto=format&fit=crop&w=700&q=80',
                'stocks' => [400, 250],
            ],
            [
                'category' => 'Vitamin',
                'name' => 'Multivitamin + Amino',
                'slug' => 'multivitamin-amino',
                'pack' => 'Botol 1 Liter',
                'description' => 'Suplemen cair untuk menjaga performa dan memacu pertumbuhan.',
                'indication' => 'Meningkatkan performa dan pertumbuhan.',
                'usage_instruction' => 'Campurkan ke air minum.',
                'dosage' => '0.5-1 gram/liter air minum, setiap hari',
                'symptom_tags' => 'performa pertumbuhan vitamin ayam',
                'price' => 220000,
                'image' => 'https://images.unsplash.com/photo-1606914469633-bd39206ea739?auto=format&fit=crop&w=700&q=80',
                'stocks' => [300, 100],
            ],
            [
                'category' => 'Antiparasit',
                'name' => 'Albendazole Suspensi',
                'slug' => 'albendazole-suspensi',
                'pack' => 'Botol 1 Liter',
                'description' => 'Obat cacing spektrum luas untuk cacing gelang, pita, dan hati (sapi, kambing).',
                'indication' => 'Cacing gelang, pita, hati.',
                'usage_instruction' => 'Diberikan secara oral (cekok).',
                'dosage' => '10mg/kg BB, single dose, ulangi 2 mgg kemudian',
                'symptom_tags' => 'cacing kurus sapi kambing antiparasit',
                'price' => 150000,
                'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?auto=format&fit=crop&w=700&q=80',
                'stocks' => [0, 0], // Dibuat kosong agar AI bisa tes "stok habis"
            ],
            [
                'category' => 'Antiparasit',
                'name' => 'Ivermectin Injection',
                'slug' => 'ivermectin-injection',
                'pack' => 'Vial 50 ml',
                'description' => 'Suntikan untuk mengatasi cacing dan ektoparasit (kutu, tungau).',
                'indication' => 'Cacingan dan ektoparasit.',
                'usage_instruction' => 'Injeksi SC (Subkutan).',
                'dosage' => '1ml/50kg BB, single dose',
                'symptom_tags' => 'cacing kutu ektoparasit sapi kambing',
                'price' => 195000,
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=700&q=80',
                'stocks' => [250, 150],
            ],
            [
                'category' => 'Disinfektan',
                'name' => 'Formades Spray',
                'slug' => 'formades-spray',
                'pack' => 'Jerigen 5 Liter',
                'description' => 'Cairan desinfeksi kandang dan peralatan.',
                'indication' => 'Desinfeksi kandang, peralatan ternak.',
                'usage_instruction' => 'Semprot merata, diamkan 30 menit, bilas.',
                'dosage' => '10ml / 1 Liter air',
                'symptom_tags' => 'desinfektan kandang cuci sanitasi',
                'price' => 175000,
                'image' => 'https://images.unsplash.com/photo-1585435557343-3b092031a831?auto=format&fit=crop&w=700&q=80',
                'stocks' => [150, 80],
            ],
            [
                'category' => 'Disinfektan',
                'name' => 'Virkon S',
                'slug' => 'virkon-s',
                'pack' => 'Ember 1 Kg',
                'description' => 'Serbuk desinfeksi high-level ampuh membunuh virus dan bakteri.',
                'indication' => 'Desinfeksi high-level.',
                'usage_instruction' => 'Semprot atau celup peralatan.',
                'dosage' => '10 gram/10 liter air',
                'symptom_tags' => 'virus bakteri desinfektan sanitasi',
                'price' => 380000,
                'image' => 'https://images.unsplash.com/photo-1606914469633-bd39206ea739?auto=format&fit=crop&w=700&q=80',
                'stocks' => [50, 20],
            ],
            [
                'category' => 'Antibiotik',
                'name' => 'Tilmicosin Injection',
                'slug' => 'tilmicosin-injection',
                'pack' => 'Vial 100 ml',
                'description' => 'Antibiotik makrolida untuk kasus CRD berat dan pneumonia pada ayam dan sapi.',
                'indication' => 'CRD berat, pneumonia.',
                'usage_instruction' => 'Injeksi SC/IM.',
                'dosage' => '1ml/15kg BB, 1x sehari 3 hari',
                'symptom_tags' => 'crd pneumonia napas ngorok berat sapi ayam',
                'price' => 280000,
                'image' => 'https://images.unsplash.com/photo-1579165466741-7f35e4755660?auto=format&fit=crop&w=700&q=80',
                'stocks' => [110, 40],
            ]
        ];

        foreach ($realProducts as $index => $prod) {
            $product = Product::updateOrCreate(
                ['slug' => $prod['slug']],
                [
                    'category_id' => $getCategoryId($prod['category']),
                    'name' => $prod['name'],
                    'pack' => $prod['pack'],
                    'description' => $prod['description'],
                    'short_description' => $prod['description'],
                    'indication' => $prod['indication'],
                    'usage_instruction' => $prod['usage_instruction'],
                    'dosage' => $prod['dosage'],
                    'symptom_tags' => $prod['symptom_tags'],
                    'price' => $prod['price'],
                    'image' => $prod['image'],
                    'sold_count' => rand(50, 1000),
                    'status' => 'active',
                    'is_featured' => true,
                    'sort_order' => $index + 20,
                ]
            );

            $unit = \App\Models\ProductUnit::updateOrCreate(
                ['slug' => $prod['slug']],
                [
                    'product_id' => $product->id,
                    'name' => $prod['pack'],
                    'price' => $prod['price'],
                    'is_default' => true,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );

            foreach ($warehouses as $warehouseIndex => $warehouse) {
                $stockVal = $prod['stocks'][$warehouseIndex] ?? 0;
                ProductStock::updateOrCreate(
                    ['product_unit_id' => $unit->id, 'warehouse_id' => $warehouse->id],
                    ['product_id' => $product->id, 'stock' => $stockVal, 'reserved_stock' => 0]
                );
            }
        }
    }
}
