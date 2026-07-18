<?php

namespace App\Services;

use App\Models\AiAgentSetting;
use App\Models\AiKnowledgeBase;
use LucianoTonet\GroqPHP\Groq;

class AiService
{
    private Groq $groq;
    
    private string $systemPrompt = <<<'PROMPT'
# Upgrade Knowledge & Reasoning SaditaCare AI

Tujuan utama SaditaCare adalah membantu peternak Indonesia menganalisis gejala awal ternak dan memberikan edukasi serta rekomendasi produk yang relevan.

AI **bukan** dokter hewan dan **tidak boleh** memberikan diagnosis pasti tanpa informasi yang cukup.

---

# IDENTITAS

Nama AI:
SaditaCare

Peran:
Asisten AI kesehatan ternak Indonesia.

Target pengguna:
- Peternak ayam broiler
- Ayam petelur
- Bebek
- Sapi
- Kambing
- Domba
- Ikan

Fokus utama:
- Analisis gejala
- Edukasi
- Pencegahan penyakit
- Biosecurity
- Manajemen kandang
- Nutrisi
- Vitamin
- Obat
- Rekomendasi produk SADITA

---

# ATURAN PALING PENTING

JANGAN PERNAH langsung mendiagnosis penyakit.

JANGAN PERNAH langsung mengatakan:

"Penyebabnya adalah..."

atau

"Ayam Anda terkena..."

Jika data belum cukup.

SaditaCare WAJIB mengumpulkan informasi terlebih dahulu.

---

# ALUR BERPIKIR

Setiap user menjelaskan gejala.

SaditaCare harus berpikir seperti berikut:

STEP 1

Identifikasi:

Jenis ternak

Jika belum diketahui,

Tanyakan.

Contoh:

"Ayam broiler atau layer?"

---

STEP 2

Tanyakan data penting.

Minimal:

- umur
- jumlah ternak
- sejak kapan
- gejala utama
- nafsu makan
- minum
- kematian
- warna feses
- kondisi kandang
- vaksin terakhir (jika ada)

Jika data belum lengkap

JANGAN memberikan diagnosis.

---

STEP 3

Baru lakukan analisis.

Gunakan kalimat:

"Berdasarkan informasi yang diberikan, kemungkinan penyebabnya antara lain..."

JANGAN menggunakan kalimat pasti.

---

STEP 4

Berikan:

✅ kemungkinan penyakit

✅ alasan

✅ pemeriksaan tambahan

✅ penanganan awal

✅ rekomendasi produk SADITA

✅ kapan harus menghubungi dokter hewan

---

# SKALA KEYAKINAN

Jika informasi sangat sedikit

Jangan menebak.

Gunakan:

"Tingkat keyakinan saya masih rendah karena informasi yang diberikan belum cukup."

---

Jika data lengkap

Gunakan:

"Kemungkinan terbesar adalah..."

bukan

"Sudah pasti..."

---

# PAHAMI BAHASA PETERNAK INDONESIA

SaditaCare harus memahami istilah sehari-hari peternak.

Contoh:

"kencing celana"

BUKAN berarti ayam mengompol.

Biasanya mengarah pada:

- kloaka basah
- diare
- feses encer
- gangguan pencernaan

SaditaCare harus meminta klarifikasi.

---

"ngorok"

Bukan diagnosis.

Tetapi gejala gangguan pernapasan.

Kemungkinan:

- CRD
- Coryza
- ND
- Bronkitis

Harus dianalisis bersama gejala lain.

---

"pilek"

Bisa berarti:

- keluar lendir
- bersin
- hidung basah

Bukan penyakit.

---

"berak kapur"

Mengacu pada:

feses putih.

Perlu analisis lanjutan.

---

"mencret hijau"

Bukan diagnosis.

Harus dikaitkan dengan:

- umur
- nafsu makan
- vaksin
- kematian

---

"ayam turun"

Biasanya berarti:

- lemas
- tidak mampu berdiri

Bukan nama penyakit.

---

# JANGAN MENAKUT-NAKUTI USER

Hindari:

"Penyakit ginjal kronis"

"Kehilangan fungsi ginjal"

"Organ gagal"

Jika belum ada bukti.

Gunakan bahasa sederhana.

---

# PRIORITASKAN EDUKASI

Selain menjawab,

Selalu jelaskan:

- kenapa bisa terjadi
- bagaimana mencegahnya
- apa yang harus dipantau

---

# REKOMENDASI PRODUK

Jika ada produk SADITA yang relevan.

Jelaskan:

- fungsi produk
- alasan direkomendasikan
- cara penggunaan singkat

Jangan memaksa membeli.

---

# FORMAT JAWABAN

Gunakan format tetap.

## Analisis Awal

...

## Kemungkinan Penyebab

- ...
- ...

## Informasi yang Masih Dibutuhkan

- ...
- ...

## Langkah Awal

- ...
- ...

## Produk SADITA yang Mungkin Membantu

- ...

## Kapan Harus Menghubungi Dokter Hewan

...

---

# GAYA BAHASA

Gunakan bahasa Indonesia yang sederhana.

Hindari istilah medis yang sulit dipahami peternak.

Bersikap ramah, sopan, dan membantu.

Jawaban harus terasa seperti dokter hewan yang sedang mewawancarai peternak, bukan chatbot yang langsung menebak penyakit.
PROMPT;

    public function __construct()
    {
        $this->groq = new Groq(config('groq.api_key'));
    }

    public function chat(array $history, string $newMessage): string
    {
        try {
            // Format messages for Groq API
            $messages = [
                ['role' => 'system', 'content' => $this->buildSystemPrompt()]
            ];
            
            // Add history
            foreach ($history as $msg) {
                $content = $msg['text'];
                if ($msg['role'] === 'assistant') {
                    // Strip the [PRODUCT:...] markers so the AI doesn't learn and hallucinate them
                    $content = preg_replace('/\[PRODUCT:[^\]]+\]/i', '', $content);
                }
                
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => trim($content)
                ];
            }
            
            // Add new message
            $messages[] = [
                'role' => 'user',
                'content' => $newMessage
            ];

            $response = $this->groq->chat()->completions()->create([
                'model' => config('groq.model', 'llama-3.1-8b-instant'),
                'messages' => $messages,
                'max_tokens' => (int) config('groq.options.max_tokens', 2000),
                'temperature' => (float) config('groq.options.temperature', 0.7),
            ]);

            $aiResponse = $response['choices'][0]['message']['content'] ?? 'Maaf, terjadi kesalahan saat memproses respons.';
            
            // Parse dan tambahkan link produk jika ada rekomendasi
            return $this->enrichWithProductLinks($aiResponse);
            
        } catch (\Exception $e) {
            \Log::error('Groq API Error: ' . $e->getMessage());
            
            $msg = $e->getMessage();
            if (str_contains($msg, 'rate_limit') || str_contains($msg, '429') || str_contains($msg, 'quota') || str_contains($msg, 'insufficient')) {
                return '[AI_UNAVAILABLE]Mohon maaf, layanan konsultasi AI sedang tidak tersedia untuk sementara karena batas pemakaian harian telah tercapai. Silakan coba lagi besok atau hubungi tim SADITA langsung melalui WhatsApp untuk konsultasi.';
            }
            
            return 'Maaf, tidak dapat terhubung ke AI. Silakan coba lagi atau hubungi tim SADITA langsung.';
        }
    }
    
    private function enrichWithProductLinks(string $aiResponse): string
    {
        try {
            $products = \App\Models\Product::where('status', 'active')->get();
            foreach ($products as $product) {
                $productName = $product->name;
                $slug = $product->slug;
                
                if (stripos($aiResponse, $productName) !== false) {
                    // Tambahkan marker untuk product button di frontend
                    $imageUrl = $product->image_url;
                    $pattern = '/(\*\*' . preg_quote($productName, '/') . '.*?\*\*)/i';
                    $replacement = '$1 [PRODUCT:' . $slug . '|' . $imageUrl . '|' . $productName . ']';
                    $aiResponse = preg_replace($pattern, $replacement, $aiResponse, 1);
                }
            }
        } catch (\Throwable $e) {
            \Log::error('Error in enrichWithProductLinks: ' . $e->getMessage());
        }
        
        return $aiResponse;
    }

    private function buildSystemPrompt(): string
    {
        try {
            $setting = AiAgentSetting::active();
            $knowledgeItems = AiKnowledgeBase::query()
                ->where('is_active', true)
                ->orderByDesc('priority')
                ->orderBy('id')
                ->get();
        } catch (\Throwable $e) {
            return $this->systemPrompt;
        }

        if (! $setting && $knowledgeItems->isEmpty()) {
            return $this->systemPrompt;
        }

        $sections = [];

        if ($setting) {
            $sections[] = "AGENT SPEC";
            $sections[] = "Nama: " . ($setting->name ?: 'SaditaCare');
            if ($setting->role) {
                $sections[] = "Peran: {$setting->role}";
            }
            if ($setting->language) {
                $sections[] = "Bahasa: {$setting->language}";
            }
            if ($setting->style) {
                $sections[] = "Style: {$setting->style}";
            }
            if ($setting->tone) {
                $sections[] = "Tone: {$setting->tone}";
            }
            if ($setting->addressing) {
                $sections[] = "Panggilan user: {$setting->addressing}";
            }
            if ($setting->scope_rules) {
                $sections[] = "";
                $sections[] = "BATASAN SCOPE:";
                $sections[] = trim($setting->scope_rules);
            }
            if ($setting->instructions) {
                $sections[] = "";
                $sections[] = "INSTRUKSI WAJIB:";
                $sections[] = trim($setting->instructions);
            }
            if ($setting->response_format) {
                $sections[] = "";
                $sections[] = "FORMAT JAWABAN:";
                $sections[] = trim($setting->response_format);
            }
            if ($setting->contact_label || $setting->contact_value) {
                $sections[] = "";
                $sections[] = "KONTAK:";
                $sections[] = trim(($setting->contact_label ?: 'Kontak') . ': ' . ($setting->contact_value ?: '-'));
            }
        }

        if ($knowledgeItems->isNotEmpty()) {
            $sections[] = "";
            $sections[] = "KNOWLEDGE BASE (gunakan saat menjawab):";
            foreach ($knowledgeItems as $item) {
                $title = $item->title;
                $category = $item->category ? " [{$item->category}]" : '';
                $sections[] = "";
                $sections[] = "- {$title}{$category}";
                $sections[] = trim($item->content);
            }
        }

        $sections[] = "";
        $sections[] = "ATURAN TEKNIS:";
        $sections[] = "- Jika user di luar scope, tolak dengan sopan sesuai scope.";
        $sections[] = "- Utamakan jawaban praktis, jelas, dan aman.";
        $sections[] = "- Jika info kurang, minta klarifikasi singkat.";

        try {
            $products = \App\Models\Product::with('stocks')->where('status', 'active')->get();
            if ($products->isNotEmpty()) {
                $sections[] = "";
                $sections[] = "KATALOG PRODUK SADITA (STATUS REAL-TIME):";
                $sections[] = "PERHATIAN: Gunakan daftar ini untuk merekomendasikan produk. Jangan merekomendasikan obat yang stoknya habis tanpa memberitahu pengguna.";
                foreach ($products as $product) {
                    $stock = $product->totalStock();
                    $status = $stock > 0 ? "Tersedia ({$stock} pcs)" : "STOK HABIS/KOSONG";
                    $dosage = $product->dosage ?? 'Sesuai anjuran';
                    $sections[] = "🔹 {$product->name}";
                    $sections[] = "   - Indikasi: {$product->indication}";
                    $sections[] = "   - Dosis: {$dosage}";
                    $sections[] = "   - Status Stok: {$status}";
                }
                $sections[] = "";
                $sections[] = "ATURAN REKOMENDASI STOK & PRODUK (SANGAT KRITIKAL):";
                $sections[] = "1. HANYA BOLEH merekomendasikan produk yang SECARA EKSPLISIT tercantum di 'KATALOG PRODUK SADITA (STATUS REAL-TIME)' di atas.";
                $sections[] = "2. DILARANG KERAS mengarang, menyarankan, atau menyebutkan produk/obat/merk yang tidak ada di daftar atas, meskipun itu adalah obat yang umum (seperti Levamisole, dll).";
                $sections[] = "3. Jika produk yang paling tepat sedang berstatus 'STOK HABIS/KOSONG', beri tahu pengguna bahwa stok sedang habis.";
                $sections[] = "4. Jika Anda ingin mencari alternatif untuk produk yang habis, alternatif tersebut JUGA WAJIB berasal dari daftar di atas. Jika tidak ada alternatif di daftar atas, katakan saja bahwa saat ini SADITA belum memiliki alternatif lain.";
                $sections[] = "5. JANGAN menyuruh pengguna membeli produk yang sedang kosong.";
            }
        } catch (\Throwable $e) {
            \Log::error('Gagal memuat katalog produk: ' . $e->getMessage());
        }

        return implode("\n", $sections);
    }
}
