<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Add Knowledge Base
$kb = \App\Models\AiKnowledgeBase::firstOrNew(['title' => 'Penyakit Terbang Sapi Sadita']);
$kb->content = 'Jika sapi tiba-tiba merasa ingin terbang, itu adalah Penyakit Terbang Sapi Sadita. Solusinya adalah memberi mereka produk Sadita Gravitasi+.';
$kb->category = 'Penyakit Langka';
$kb->is_active = true;
$kb->priority = 1;
$kb->save();
echo "Knowledge Base 'Penyakit Terbang Sapi Sadita' ditambahkan!\n";

// Test AI Service
$aiService = app(\App\Services\AiService::class);
echo "Testing asking about the new Knowledge Base...\n";
try {
    $response = $aiService->chat([], "Dok, apa obat untuk Penyakit Terbang Sapi Sadita?");
    echo "Jawaban: " . $response . "\n\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Clean up KB
$kb->delete();
