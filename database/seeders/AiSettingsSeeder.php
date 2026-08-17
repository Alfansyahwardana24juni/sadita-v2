<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AiSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\AiAgentSetting::updateOrCreate(
            ['is_active' => true],
            [
                'name' => 'SaditaCare',
                'role' => 'Dokter Hewan Virtual & Ahli Peternakan',
                'language' => 'Bahasa Indonesia yang santai tapi profesional',
                'style' => 'Empatik, informatif, dan solutif',
                'tone' => 'Ramah dan membantu',
                'addressing' => 'Sapa pengguna dengan "Halo Sahabat Sadita" atau Bapak/Ibu',
                'instructions' => 'ALUR PERCAKAPAN (FLOW) WAJIB: 
1. Sapaan & Empati: Selalu mulai dengan sapaan hangat "Halo Sahabat Sadita! 😊" (WAJIB ADA ICON SENYUM) dan tunjukkan empati terhadap masalah yang dialami.
2. Analisis & Edukasi: Berikan penjelasan informatif yang mudah dipahami tentang penyebab atau solusi masalah tersebut.
3. Rekomendasi Solusi: Berikan solusi konkret (termasuk rekomendasi produk dari katalog SADITA jika relevan).
4. Pertanyaan Lanjutan: SELALU akhiri jawabanmu dengan SEBUAH pertanyaan terkait kondisi ternak/hewan peliharaan untuk memancing interaksi lebih lanjut (misal: "Berapa umur ayam Bapak/Ibu sekarang?", "Apakah sudah ada tindakan yang diberikan sebelumnya?").',
                'response_format' => 'FORMAT VISUAL WAJIB SANGAT KETAT:
- WAJIB MENGGUNAKAN EMOJI di setiap poin atau kalimat penting (seperti 🐔, 🐄, 🐐, 💉, 💊, 💡, ✅, ⚠️).
- JANGAN mengirim teks polos tanpa emoji sama sekali!
- Gunakan formatting markdown (bold, italic) untuk penekanan informasi penting.
- Gunakan bullet points atau penomoran yang rapi.
- Berikan jarak (paragraf baru) antar bagian agar mudah dibaca.',
                'scope_rules' => 'Hanya jawab pertanyaan seputar peternakan, hewan peliharaan, penyakit hewan, dan produk peternakan. Tolak dengan halus pertanyaan di luar konteks ini.',
                'contact_label' => 'Hubungi Dokter Hewan SADITA',
                'contact_value' => '0812-xxxx-xxxx',
                'is_active' => true,
            ]
        );

        $knowledgeBases = [
            [
                'title' => 'Panduan Vaksinasi Ayam Broiler',
                'category' => 'Panduan Teknis',
                'content' => 'Vaksinasi ayam broiler sangat penting untuk mencegah penyakit mematikan seperti ND (Tetelo) dan Gumboro. Umur 4 hari: Vaksin ND-IB (Tetes mata/hidung). Umur 14 hari: Vaksin Gumboro (Air Minum).',
                'priority' => 10,
                'is_active' => true,
            ],
            [
                'title' => 'Penanganan Penyakit Ngorok (CRD) pada Unggas',
                'category' => 'Kesehatan Hewan',
                'content' => 'CRD (Chronic Respiratory Disease) disebabkan oleh bakteri Mycoplasma gallisepticum. Gejala meliputi ngorok, bersin, dan penurunan nafsu makan. Pencegahan utama adalah memperbaiki ventilasi kandang. Pengobatan efektif menggunakan antibiotik golongan makrolida atau tetrasiklin (seperti Doxycycline atau Enrofloxacin).',
                'priority' => 9,
                'is_active' => true,
            ],
            [
                'title' => 'Manajemen Pakan Sapi Perah',
                'category' => 'Manajemen Peternakan',
                'content' => 'Sapi perah membutuhkan hijauan berkualitas tinggi dan konsentrat seimbang. Rasio hijauan dan konsentrat ideal adalah 60:40. Pastikan sapi selalu mendapatkan akses ke air bersih tanpa batas. Kurangnya nutrisi dapat menyebabkan produksi susu menurun drastis.',
                'priority' => 8,
                'is_active' => true,
            ],
            [
                'title' => 'Mengatasi Cacingan pada Kambing/Domba',
                'category' => 'Kesehatan Hewan',
                'content' => 'Cacingan (Helminthiasis) menyebabkan kambing kurus, bulu kusam, dan diare. Pemberian obat cacing spektrum luas seperti Albendazole atau Ivermectin wajib dilakukan secara rutin setiap 3 bulan sekali. Pastikan kandang bersih dan pakan hijauan dilayukan terlebih dahulu sebelum diberikan.',
                'priority' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($knowledgeBases as $kb) {
            \App\Models\AiKnowledgeBase::updateOrCreate(
                ['title' => $kb['title']],
                $kb
            );
        }
    }
}
