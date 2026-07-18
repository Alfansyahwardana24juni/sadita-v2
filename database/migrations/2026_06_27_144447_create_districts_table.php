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
        Schema::create('districts', function (Blueprint $table) {
            $table->char('id', 7)->primary(); // Kode kecamatan 7 digit
            $table->char('regency_id', 4);
            $table->string('name');
            $table->timestamps();
            
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');
            $table->index('regency_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
