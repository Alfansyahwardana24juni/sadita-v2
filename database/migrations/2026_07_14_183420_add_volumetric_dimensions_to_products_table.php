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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('weight')->default(500)->after('price')->comment('Berat aktual dalam gram');
            $table->integer('length')->default(0)->after('weight')->comment('Panjang dalam cm');
            $table->integer('width')->default(0)->after('length')->comment('Lebar dalam cm');
            $table->integer('height')->default(0)->after('width')->comment('Tinggi dalam cm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight', 'length', 'width', 'height']);
        });
    }
};
