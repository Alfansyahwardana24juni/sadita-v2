<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('zone_name'); // "Makassar-Maros", "Luar Kota"
            $table->json('cities'); // ["Makassar", "Maros"] atau ["*"] untuk semua
            $table->json('shipping_methods'); // ["kurir_sadita", "ambil_langsung"]
            $table->decimal('free_shipping_threshold', 10, 2)->nullable(); // 10 untuk 10kg
            $table->decimal('flat_rate', 10, 2)->nullable(); // Harga flat jika di bawah threshold
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};
