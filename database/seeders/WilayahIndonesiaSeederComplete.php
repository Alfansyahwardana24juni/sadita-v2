<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahIndonesiaSeederComplete extends Seeder
{
    /**
     * Seed data lengkap wilayah Indonesia
     * Data SAMPLE untuk semua 34 provinsi (minimal 1-2 kota per provinsi)
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('provinces')->truncate();
        DB::table('regencies')->truncate();
        DB::table('districts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seedProvinces();
        $this->seedRegencies();
        $this->seedDistricts();
        
        $this->command->info('✅ Complete wilayah Indonesia seeded!');
    }

    private function seedProvinces(): void
    {
        $provinces = [
            ['id' => '11', 'name' => 'ACEH'],
            ['id' => '12', 'name' => 'SUMATERA UTARA'],
            ['id' => '13', 'name' => 'SUMATERA BARAT'],
            ['id' => '14', 'name' => 'RIAU'],
            ['id' => '15', 'name' => 'JAMBI'],
            ['id' => '16', 'name' => 'SUMATERA SELATAN'],
            ['id' => '17', 'name' => 'BENGKULU'],
            ['id' => '18', 'name' => 'LAMPUNG'],
            ['id' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG'],
            ['id' => '21', 'name' => 'KEPULAUAN RIAU'],
            ['id' => '31', 'name' => 'DKI JAKARTA'],
            ['id' => '32', 'name' => 'JAWA BARAT'],
            ['id' => '33', 'name' => 'JAWA TENGAH'],
            ['id' => '34', 'name' => 'DI YOGYAKARTA'],
            ['id' => '35', 'name' => 'JAWA TIMUR'],
            ['id' => '36', 'name' => 'BANTEN'],
            ['id' => '51', 'name' => 'BALI'],
            ['id' => '52', 'name' => 'NUSA TENGGARA BARAT'],
            ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
            ['id' => '61', 'name' => 'KALIMANTAN BARAT'],
            ['id' => '62', 'name' => 'KALIMANTAN TENGAH'],
            ['id' => '63', 'name' => 'KALIMANTAN SELATAN'],
            ['id' => '64', 'name' => 'KALIMANTAN TIMUR'],
            ['id' => '65', 'name' => 'KALIMANTAN UTARA'],
            ['id' => '71', 'name' => 'SULAWESI UTARA'],
            ['id' => '72', 'name' => 'SULAWESI TENGAH'],
            ['id' => '73', 'name' => 'SULAWESI SELATAN'],
            ['id' => '74', 'name' => 'SULAWESI TENGGARA'],
            ['id' => '75', 'name' => 'GORONTALO'],
            ['id' => '76', 'name' => 'SULAWESI BARAT'],
            ['id' => '81', 'name' => 'MALUKU'],
            ['id' => '82', 'name' => 'MALUKU UTARA'],
            ['id' => '91', 'name' => 'PAPUA BARAT'],
            ['id' => '94', 'name' => 'PAPUA'],
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->insert($province);
        }
    }

    private function seedRegencies(): void
    {
        $regencies = [
            // ACEH (11)
            ['id' => '1101', 'province_id' => '11', 'name' => 'ACEH UTARA', 'rajaongkir_city_id' => null],
            ['id' => '1171', 'province_id' => '11', 'name' => 'BANDA ACEH', 'rajaongkir_city_id' => null],

            // SUMATERA UTARA (12)
            ['id' => '1201', 'province_id' => '12', 'name' => 'LANGKAT', 'rajaongkir_city_id' => null],
            ['id' => '1271', 'province_id' => '12', 'name' => 'MEDAN', 'rajaongkir_city_id' => 269],

            // SUMATERA BARAT (13)
            ['id' => '1301', 'province_id' => '13', 'name' => 'PESISIR SELATAN', 'rajaongkir_city_id' => null],
            ['id' => '1371', 'province_id' => '13', 'name' => 'PADANG', 'rajaongkir_city_id' => null],

            // RIAU (14)
            ['id' => '1401', 'province_id' => '14', 'name' => 'KUANTAN SINGINGI', 'rajaongkir_city_id' => null],
            ['id' => '1471', 'province_id' => '14', 'name' => 'PEKANBARU', 'rajaongkir_city_id' => null],

            // JAMBI (15)
            ['id' => '1501', 'province_id' => '15', 'name' => 'KERINCI', 'rajaongkir_city_id' => null],
            ['id' => '1571', 'province_id' => '15', 'name' => 'JAMBI', 'rajaongkir_city_id' => null],

            // SUMATERA SELATAN (16)
            ['id' => '1601', 'province_id' => '16', 'name' => 'OGAN KOMERING ULU', 'rajaongkir_city_id' => null],
            ['id' => '1671', 'province_id' => '16', 'name' => 'PALEMBANG', 'rajaongkir_city_id' => null],

            // BENGKULU (17)
            ['id' => '1701', 'province_id' => '17', 'name' => 'SELUMA', 'rajaongkir_city_id' => null],
            ['id' => '1771', 'province_id' => '17', 'name' => 'BENGKULU', 'rajaongkir_city_id' => null],

            // LAMPUNG (18)
            ['id' => '1801', 'province_id' => '18', 'name' => 'LAMPUNG UTARA', 'rajaongkir_city_id' => null],
            ['id' => '1871', 'province_id' => '18', 'name' => 'BANDAR LAMPUNG', 'rajaongkir_city_id' => null],

            // KEPULAUAN BANGKA BELITUNG (19)
            ['id' => '1901', 'province_id' => '19', 'name' => 'BANGKA', 'rajaongkir_city_id' => null],
            ['id' => '1971', 'province_id' => '19', 'name' => 'PANGKAL PINANG', 'rajaongkir_city_id' => null],

            // KEPULAUAN RIAU (21)
            ['id' => '2101', 'province_id' => '21', 'name' => 'BINTAN', 'rajaongkir_city_id' => null],
            ['id' => '2171', 'province_id' => '21', 'name' => 'BATAM', 'rajaongkir_city_id' => null],

            // DKI JAKARTA (31)
            ['id' => '3101', 'province_id' => '31', 'name' => 'KEPULAUAN SERIBU', 'rajaongkir_city_id' => null],
            ['id' => '3171', 'province_id' => '31', 'name' => 'JAKARTA SELATAN', 'rajaongkir_city_id' => 152],
            ['id' => '3172', 'province_id' => '31', 'name' => 'JAKARTA TIMUR', 'rajaongkir_city_id' => 154],
            ['id' => '3173', 'province_id' => '31', 'name' => 'JAKARTA PUSAT', 'rajaongkir_city_id' => 151],
            ['id' => '3174', 'province_id' => '31', 'name' => 'JAKARTA BARAT', 'rajaongkir_city_id' => 157],
            ['id' => '3175', 'province_id' => '31', 'name' => 'JAKARTA UTARA', 'rajaongkir_city_id' => 155],

            // JAWA BARAT (32)
            ['id' => '3201', 'province_id' => '32', 'name' => 'BOGOR', 'rajaongkir_city_id' => 68],
            ['id' => '3271', 'province_id' => '32', 'name' => 'BOGOR KOTA', 'rajaongkir_city_id' => 69],
            ['id' => '3202', 'province_id' => '32', 'name' => 'SUKABUMI', 'rajaongkir_city_id' => null],
            ['id' => '3203', 'province_id' => '32', 'name' => 'CIANJUR', 'rajaongkir_city_id' => null],
            ['id' => '3204', 'province_id' => '32', 'name' => 'BANDUNG', 'rajaongkir_city_id' => 22],
            ['id' => '3273', 'province_id' => '32', 'name' => 'BANDUNG KOTA', 'rajaongkir_city_id' => 23],
            ['id' => '3205', 'province_id' => '32', 'name' => 'GARUT', 'rajaongkir_city_id' => null],
            ['id' => '3206', 'province_id' => '32', 'name' => 'TASIKMALAYA', 'rajaongkir_city_id' => null],
            ['id' => '3207', 'province_id' => '32', 'name' => 'CIAMIS', 'rajaongkir_city_id' => null],
            ['id' => '3208', 'province_id' => '32', 'name' => 'KUNINGAN', 'rajaongkir_city_id' => null],
            ['id' => '3209', 'province_id' => '32', 'name' => 'CIREBON', 'rajaongkir_city_id' => null],
            ['id' => '3210', 'province_id' => '32', 'name' => 'MAJALENGKA', 'rajaongkir_city_id' => null],
            ['id' => '3211', 'province_id' => '32', 'name' => 'SUMEDANG', 'rajaongkir_city_id' => null],
            ['id' => '3212', 'province_id' => '32', 'name' => 'INDRAMAYU', 'rajaongkir_city_id' => null],
            ['id' => '3213', 'province_id' => '32', 'name' => 'SUBANG', 'rajaongkir_city_id' => null],
            ['id' => '3214', 'province_id' => '32', 'name' => 'PURWAKARTA', 'rajaongkir_city_id' => null],
            ['id' => '3215', 'province_id' => '32', 'name' => 'KARAWANG', 'rajaongkir_city_id' => null],
            ['id' => '3216', 'province_id' => '32', 'name' => 'BEKASI', 'rajaongkir_city_id' => 38],
            ['id' => '3275', 'province_id' => '32', 'name' => 'BEKASI KOTA', 'rajaongkir_city_id' => 39],
            ['id' => '3217', 'province_id' => '32', 'name' => 'BANDUNG BARAT', 'rajaongkir_city_id' => null],
            ['id' => '3218', 'province_id' => '32', 'name' => 'PANGANDARAN', 'rajaongkir_city_id' => null],
            ['id' => '3276', 'province_id' => '32', 'name' => 'DEPOK', 'rajaongkir_city_id' => 107],

            // JAWA TENGAH (33)
            ['id' => '3301', 'province_id' => '33', 'name' => 'CILACAP', 'rajaongkir_city_id' => null],
            ['id' => '3371', 'province_id' => '33', 'name' => 'SEMARANG', 'rajaongkir_city_id' => 398],

            // DI YOGYAKARTA (34)
            ['id' => '3401', 'province_id' => '34', 'name' => 'SLEMAN', 'rajaongkir_city_id' => null],
            ['id' => '3471', 'province_id' => '34', 'name' => 'YOGYAKARTA', 'rajaongkir_city_id' => null],

            // JAWA TIMUR (35)
            ['id' => '3501', 'province_id' => '35', 'name' => 'PACITAN', 'rajaongkir_city_id' => null],
            ['id' => '3571', 'province_id' => '35', 'name' => 'SURABAYA', 'rajaongkir_city_id' => 444],

            // BANTEN (36)
            ['id' => '3601', 'province_id' => '36', 'name' => 'PANDEGLANG', 'rajaongkir_city_id' => null],
            ['id' => '3671', 'province_id' => '36', 'name' => 'SERANG', 'rajaongkir_city_id' => null],

            // BALI (51)
            ['id' => '5101', 'province_id' => '51', 'name' => 'BADUNG', 'rajaongkir_city_id' => null],
            ['id' => '5171', 'province_id' => '51', 'name' => 'DENPASAR', 'rajaongkir_city_id' => 114],

            // NUSA TENGGARA BARAT (52)
            ['id' => '5201', 'province_id' => '52', 'name' => 'LOMBOK UTARA', 'rajaongkir_city_id' => null],
            ['id' => '5271', 'province_id' => '52', 'name' => 'MATARAM', 'rajaongkir_city_id' => null],

            // NUSA TENGGARA TIMUR (53)
            ['id' => '5301', 'province_id' => '53', 'name' => 'KUPANG', 'rajaongkir_city_id' => null],
            ['id' => '5371', 'province_id' => '53', 'name' => 'KUPANG KOTA', 'rajaongkir_city_id' => null],

            // KALIMANTAN BARAT (61)
            ['id' => '6101', 'province_id' => '61', 'name' => 'SAMBAS', 'rajaongkir_city_id' => null],
            ['id' => '6171', 'province_id' => '61', 'name' => 'PONTIANAK', 'rajaongkir_city_id' => null],

            // KALIMANTAN TENGAH (62)
            ['id' => '6201', 'province_id' => '62', 'name' => 'KOTAWARINGIN BARAT', 'rajaongkir_city_id' => null],
            ['id' => '6271', 'province_id' => '62', 'name' => 'PALANGKARAYA', 'rajaongkir_city_id' => null],

            // KALIMANTAN SELATAN (63)
            ['id' => '6301', 'province_id' => '63', 'name' => 'TANAH LAUT', 'rajaongkir_city_id' => null],
            ['id' => '6371', 'province_id' => '63', 'name' => 'BANJARMASIN', 'rajaongkir_city_id' => null],

            // KALIMANTAN TIMUR (64)
            ['id' => '6401', 'province_id' => '64', 'name' => 'BERAU', 'rajaongkir_city_id' => null],
            ['id' => '6471', 'province_id' => '64', 'name' => 'SAMARINDA', 'rajaongkir_city_id' => null],

            // KALIMANTAN UTARA (65)
            ['id' => '6501', 'province_id' => '65', 'name' => 'BULUNGAN', 'rajaongkir_city_id' => null],
            ['id' => '6571', 'province_id' => '65', 'name' => 'TARAKAN', 'rajaongkir_city_id' => null],

            // SULAWESI UTARA (71)
            ['id' => '7101', 'province_id' => '71', 'name' => 'BOLAANG MONGONDOW', 'rajaongkir_city_id' => null],
            ['id' => '7171', 'province_id' => '71', 'name' => 'MANADO', 'rajaongkir_city_id' => null],

            // SULAWESI TENGAH (72)
            ['id' => '7201', 'province_id' => '72', 'name' => 'BANGGAI KEPULAUAN', 'rajaongkir_city_id' => null],
            ['id' => '7271', 'province_id' => '72', 'name' => 'PALU', 'rajaongkir_city_id' => null],

            // SULAWESI SELATAN (73)
            ['id' => '7301', 'province_id' => '73', 'name' => 'KEPULAUAN SELAYAR', 'rajaongkir_city_id' => null],
            ['id' => '7302', 'province_id' => '73', 'name' => 'BULUKUMBA', 'rajaongkir_city_id' => null],
            ['id' => '7303', 'province_id' => '73', 'name' => 'BANTAENG', 'rajaongkir_city_id' => null],
            ['id' => '7304', 'province_id' => '73', 'name' => 'JENEPONTO', 'rajaongkir_city_id' => null],
            ['id' => '7305', 'province_id' => '73', 'name' => 'TAKALAR', 'rajaongkir_city_id' => null],
            ['id' => '7306', 'province_id' => '73', 'name' => 'GOWA', 'rajaongkir_city_id' => 156],
            ['id' => '7307', 'province_id' => '73', 'name' => 'SINJAI', 'rajaongkir_city_id' => null],
            ['id' => '7308', 'province_id' => '73', 'name' => 'MAROS', 'rajaongkir_city_id' => 280],
            ['id' => '7309', 'province_id' => '73', 'name' => 'PANGKAJENE DAN KEPULAUAN', 'rajaongkir_city_id' => null],
            ['id' => '7310', 'province_id' => '73', 'name' => 'BARRU', 'rajaongkir_city_id' => null],
            ['id' => '7311', 'province_id' => '73', 'name' => 'BONE', 'rajaongkir_city_id' => null],
            ['id' => '7312', 'province_id' => '73', 'name' => 'SOPPENG', 'rajaongkir_city_id' => null],
            ['id' => '7371', 'province_id' => '73', 'name' => 'MAKASSAR', 'rajaongkir_city_id' => 153],
            ['id' => '7372', 'province_id' => '73', 'name' => 'PAREPARE', 'rajaongkir_city_id' => null],
            ['id' => '7373', 'province_id' => '73', 'name' => 'PALOPO', 'rajaongkir_city_id' => null],

            // SULAWESI TENGGARA (74)
            ['id' => '7401', 'province_id' => '74', 'name' => 'MUNA', 'rajaongkir_city_id' => null],
            ['id' => '7471', 'province_id' => '74', 'name' => 'KENDARI', 'rajaongkir_city_id' => null],

            // GORONTALO (75)
            ['id' => '7501', 'province_id' => '75', 'name' => 'BOALEMO', 'rajaongkir_city_id' => null],
            ['id' => '7571', 'province_id' => '75', 'name' => 'GORONTALO', 'rajaongkir_city_id' => null],

            // SULAWESI BARAT (76)
            ['id' => '7601', 'province_id' => '76', 'name' => 'MAJENE', 'rajaongkir_city_id' => null],
            ['id' => '7671', 'province_id' => '76', 'name' => 'MAMUJU', 'rajaongkir_city_id' => null],

            // MALUKU (81)
            ['id' => '8101', 'province_id' => '81', 'name' => 'MALUKU TENGAH', 'rajaongkir_city_id' => null],
            ['id' => '8171', 'province_id' => '81', 'name' => 'AMBON', 'rajaongkir_city_id' => null],

            // MALUKU UTARA (82)
            ['id' => '8201', 'province_id' => '82', 'name' => 'HALMAHERA BARAT', 'rajaongkir_city_id' => null],
            ['id' => '8271', 'province_id' => '82', 'name' => 'TERNATE', 'rajaongkir_city_id' => null],

            // PAPUA BARAT (91)
            ['id' => '9101', 'province_id' => '91', 'name' => 'SORONG', 'rajaongkir_city_id' => null],
            ['id' => '9171', 'province_id' => '91', 'name' => 'SORONG KOTA', 'rajaongkir_city_id' => null],

            // PAPUA (94)
            ['id' => '9401', 'province_id' => '94', 'name' => 'MIMIKA', 'rajaongkir_city_id' => null],
            ['id' => '9471', 'province_id' => '94', 'name' => 'JAYAPURA', 'rajaongkir_city_id' => null],
        ];

        foreach ($regencies as $regency) {
            DB::table('regencies')->insert($regency);
        }
    }

    private function seedDistricts(): void
    {
        // Minimal 1-2 kecamatan untuk setiap regency
        $districts = [
            // Jakarta Pusat (3173)
            ['id' => '3173010', 'regency_id' => '3173', 'name' => 'GAMBIR'],
            ['id' => '3173020', 'regency_id' => '3173', 'name' => 'MENTENG'],

            // Bogor Kabupaten (3201)
            ['id' => '3201010', 'regency_id' => '3201', 'name' => 'TANJUNGSARI'],
            ['id' => '3201020', 'regency_id' => '3201', 'name' => 'RUMPIN'],

            // Bogor Kota (3271)
            ['id' => '3271010', 'regency_id' => '3271', 'name' => 'BOGOR TENGAH'],
            ['id' => '3271020', 'regency_id' => '3271', 'name' => 'BOGOR TIMUR'],

            // Bandung Kabupaten (3204)
            ['id' => '3204010', 'regency_id' => '3204', 'name' => 'SOREANG'],
            ['id' => '3204020', 'regency_id' => '3204', 'name' => 'CIPARAY'],

            // Bandung Kota (3273)
            ['id' => '3273010', 'regency_id' => '3273', 'name' => 'BANDUNG WETAN'],
            ['id' => '3273020', 'regency_id' => '3273', 'name' => 'CICENDO'],

            // Makassar Kota (7371)
            ['id' => '7371010', 'regency_id' => '7371', 'name' => 'MARISO'],
            ['id' => '7371020', 'regency_id' => '7371', 'name' => 'MAKASSAR'],
        ];

        foreach ($districts as $district) {
            DB::table('districts')->insert($district);
        }
    }
}
