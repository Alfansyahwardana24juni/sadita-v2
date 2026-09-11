<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // mis. "Cyprotil 250 mg"
            $table->string('slug')->unique(); // dipakai route binding & cart-by-slug
            $table->string('sku')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('compare_at_price')->nullable();
            $table->unsignedInteger('weight')->default(500)->comment('Berat aktual dalam gram');
            $table->unsignedInteger('length')->default(0)->comment('Panjang dalam cm');
            $table->unsignedInteger('width')->default(0)->comment('Lebar dalam cm');
            $table->unsignedInteger('height')->default(0)->comment('Tinggi dalam cm');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
