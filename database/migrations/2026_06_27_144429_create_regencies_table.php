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
        Schema::create('regencies', function (Blueprint $table) {
            $table->char('id', 4)->primary(); // Kode kota/kab 4 digit
            $table->char('province_id', 2);
            $table->string('name');
            $table->integer('rajaongkir_city_id')->nullable()->comment('ID dari RajaOngkir API untuk hitung ongkir');
            $table->timestamps();
            
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->index('province_id');
            $table->index('name');
            $table->index('rajaongkir_city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regencies');
    }
};
