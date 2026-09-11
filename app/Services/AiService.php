<?php

namespace App\Services;

use App\Models\AiAgentSetting;
use App\Models\AiKnowledgeBase;
use LucianoTonet\GroqPHP\Groq;

class AiService
{
    private Groq $groq;
    
    private string $systemPrompt = <<<'PROMPT'
Kamu adalah SaditaCare, asisten AI kesehatan ternak dari SADITA.
Tugasmu: membantu peternak Indonesia menganalisis gejala ternak DAN merekomendasikan produk dari SADITA.

ATURAN KRITIS:
1. JANGAN PERNAH mengarang nama produk sendiri. SELALU gunakan nama produk persis dari KATALOG PRODUK yang diberikan.
2. Saat merekomendasikan produk, WAJIB sebutkan nama produk dengan bold (**nama produk**) dan cantumkan stok per cabang.
3. LANGSUNG rekomendasikan produk dari katalog di setiap jawaban terkait gejala ternak, tanpa menunggu data lengkap.
4. JANGAN gunakan simbol # untuk heading. Gunakan bold (**teks**) saja.
5. Jawab singkat, padat, langsung ke inti.

FORMAT JAWABAN:
**Analisis Awal:** (singkat)
**Kemungkinan Penyebab:** (list singkat)
**Rekomendasi Produk SADITA:** (WAJIB ada, gunakan nama dari katalog, cantumkan stok cabang)
**Langkah Awal:** (list singkat)
**Kapan ke Dokter Hewan:** (singkat)

Gunakan bahasa Indonesia yang ramah dan mudah dipahami peternak.
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
                'model' => config('groq.model', 'openai/gpt-oss-20b'),
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
                    $pattern = '/(\*\*)?(' . preg_quote($productName, '/') . ')(\*\*)?/i';
                    $replacement = '**$2** [PRODUCT:' . $slug . '|' . $imageUrl . '|' . $productName . ']';
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
        $setting = null;
        $knowledgeItems = collect();

        try {
            $setting = AiAgentSetting::active();
            $knowledgeItems = AiKnowledgeBase::query()
                ->where('is_active', true)
                ->orderByDesc('priority')
                ->orderBy('id')
                ->get();
        } catch (\Throwable $e) {
            // continue without settings
        }

        // Always start with the base system prompt
        $sections[] = $this->systemPrompt;
        $sections[] = "";

        $sections[] = "WAKTU LOKAL PENGGUNA SAAT INI:";
        $sections[] = now()->timezone('Asia/Makassar')->translatedFormat('l, d F Y H:i:s') . ' WITA (Makassar)';
        $sections[] = "Sesuaikan sapaan dengan waktu di atas (pagi/siang/sore/malam).";
        $sections[] = "";

        if ($setting) {
            $sections[] = "AGENT SPEC";
            $sections[] = "Nama: " . ($setting->name ?: 'SaditaCare');
            if ($setting->role) $sections[] = "Peran: {$setting->role}";
            if ($setting->language) $sections[] = "Bahasa: {$setting->language}";
            if ($setting->style) $sections[] = "Style: {$setting->style}";
            if ($setting->tone) $sections[] = "Tone: {$setting->tone}";
            if ($setting->addressing) $sections[] = "Panggilan user: {$setting->addressing}";
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
            $sections[] = "KNOWLEDGE BASE:";
            foreach ($knowledgeItems as $item) {
                $category = $item->category ? " [{$item->category}]" : '';
                $sections[] = "";
                $sections[] = "- {$item->title}{$category}";
                $sections[] = trim($item->content);
            }
        }

        try {
            $products = \App\Models\Product::with('stocks.warehouse')->where('status', 'active')->get();
            if ($products->isNotEmpty()) {
                // Step 1: Inject strict list of allowed product names FIRST
                $allowedNames = $products->pluck('name')->implode(', ');
                $sections[] = "";
                $sections[] = "=================================================================";
                $sections[] = "NAMA PRODUK YANG DIIZINKAN (WAJIB DIBACA SEBELUM MENJAWAB):";
                $sections[] = $allowedNames;
                $sections[] = "DILARANG KERAS menyebut nama produk selain daftar di atas.";
                $sections[] = "Contoh nama yang DILARANG karena tidak ada: 'SADITA Feed Booster', 'SADITA Nutrient Booster', 'SADITA Vitamin C', 'SADITA Parasite Control', dll.";
                $sections[] = "=================================================================";
                $sections[] = "";

                // Step 2: Full catalog with stock per warehouse
                $sections[] = "KATALOG PRODUK SADITA (STOK REAL-TIME PER CABANG):";
                $sections[] = "Saat merekomendasikan, WAJIB sebut nama produk dengan **bold** dan cantumkan stok cabangnya.";
                foreach ($products as $product) {
                    $stockDetails = [];
                    $totalStock = 0;
                    foreach ($product->stocks as $stock) {
                        $available = max(0, $stock->stock - $stock->reserved_stock);
                        if ($available > 0) {
                            $wName = $stock->warehouse ? $stock->warehouse->name : 'Gudang Utama';
                            $stockDetails[] = "{$wName}: {$available} pcs";
                            $totalStock += $available;
                        }
                    }
                    $statusStr = $totalStock > 0
                        ? "Tersedia {$totalStock} pcs (" . implode(', ', $stockDetails) . ")"
                        : "STOK HABIS";

                    $sections[] = "🔹 {$product->name}";
                    $sections[] = "   Indikasi: {$product->indication}";
                    $sections[] = "   Dosis: " . ($product->dosage ?? 'Sesuai anjuran');
                    $sections[] = "   Stok: {$statusStr}";
                }
                $sections[] = "";
                $sections[] = "ATURAN REKOMENDASI:";
                $sections[] = "1. Pilih produk HANYA dari nama yang ada di daftar NAMA PRODUK YANG DIIZINKAN di atas.";
                $sections[] = "2. Sebutkan stok & cabangnya langsung dalam jawaban.";
                $sections[] = "3. Tulis nama produk dengan **bold** agar sistem bisa menampilkan tombol beli.";
                $sections[] = "4. Jika stok habis, beritahu user dan cari alternatif dari daftar yang sama.";
            }
        } catch (\Throwable $e) {
            \Log::error('Gagal memuat katalog produk: ' . $e->getMessage());
        }

        return implode("\n", $sections);
    }
}
