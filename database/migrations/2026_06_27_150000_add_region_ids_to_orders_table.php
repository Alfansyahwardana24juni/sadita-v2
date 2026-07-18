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
        Schema::table('orders', function (Blueprint $table) {
            $table->char('province_id', 2)->nullable()->after('customer_city')->comment('ID provinsi dari data wilayah');
            $table->char('regency_id', 4)->nullable()->after('province_id')->comment('ID kota/kabupaten dari data wilayah');
            $table->char('district_id', 7)->nullable()->after('regency_id')->comment('ID kecamatan dari data wilayah');
            $table->char('village_id', 10)->nullable()->after('district_id')->comment('ID kelurahan dari data wilayah');
            
            // Add foreign key constraints
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('set null');
            
            // Add indexes for performance
            $table->index('province_id');
            $table->index('regency_id');
            $table->index('district_id');
            $table->index('village_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignKey(['province_id']);
            $table->dropForeignKey(['regency_id']);
            $table->dropForeignKey(['district_id']);
            $table->dropForeignKey(['village_id']);
            
            $table->dropIndex(['province_id']);
            $table->dropIndex(['regency_id']);
            $table->dropIndex(['district_id']);
            $table->dropIndex(['village_id']);
            
            $table->dropColumn([
                'province_id',
                'regency_id',
                'district_id',
                'village_id',
            ]);
        });
    }
};
