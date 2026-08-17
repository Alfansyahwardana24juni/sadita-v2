<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_service_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('title'); // e.g. Dokter Hewan, Admin
            $table->string('experience'); // e.g. 15 Tahun Pengalaman
            $table->string('city'); // e.g. Makassar
            $table->string('whatsapp_number');
            $table->string('working_hours'); // e.g. 08.00 - 17.00 WITA
            $table->enum('status', ['online', 'busy', 'offline'])->default('offline');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_services');
    }
};
