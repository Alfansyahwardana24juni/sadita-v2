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
        Schema::create('villages', function (Blueprint $table) {
            $table->char('id', 10)->primary(); // Kode kelurahan 10 digit
            $table->char('district_id', 7);
            $table->string('name');
            $table->timestamps();
            
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->index('district_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
