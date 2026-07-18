<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds composite and covering indexes to optimize cascading
     * region dropdown queries (province → regency → district → village).
     * 
     * Performance Optimization Strategy:
     * - Single FK indexes: Handle joins and WHERE clauses efficiently
     * - Composite indexes: Optimize multi-column filters and sorts
     * - Covering indexes: Allow query execution without table lookups
     * 
     * Query Patterns Optimized:
     * 1. Regencies by province: Regency::where('province_id', $id)->orderBy('name')
     * 2. Districts by regency: District::where('regency_id', $id)->orderBy('name')
     * 3. Villages by district: Village::where('district_id', $id)->orderBy('name')
     * 4. Orders by region cascade: For shipping/delivery queries
     */
    public function up(): void
    {
        // Composite index for regencies: province_id + name (covering index)
        // Optimizes: WHERE province_id = ? ORDER BY name
        Schema::table('regencies', function (Blueprint $table) {
            $table->index(['province_id', 'name'], 'idx_regencies_province_name');
        });

        // Composite index for districts: regency_id + name (covering index)
        // Optimizes: WHERE regency_id = ? ORDER BY name
        Schema::table('districts', function (Blueprint $table) {
            $table->index(['regency_id', 'name'], 'idx_districts_regency_name');
        });

        // Composite index for villages: district_id + name (covering index)
        // Optimizes: WHERE district_id = ? ORDER BY name
        Schema::table('villages', function (Blueprint $table) {
            $table->index(['district_id', 'name'], 'idx_villages_district_name');
        });

        // Composite indexes for orders - optimize cascading region filters
        // These help when filtering orders by specific regions
        Schema::table('orders', function (Blueprint $table) {
            // For queries filtering by province and status/payment
            $table->index(['province_id', 'payment_status'], 'idx_orders_province_payment');

            // For queries filtering by regency and payment
            $table->index(['regency_id', 'payment_status'], 'idx_orders_regency_payment');

            // For cascading region filter: province → regency → district
            $table->index(['province_id', 'regency_id', 'district_id'], 'idx_orders_region_cascade');

            // For full region hierarchy lookup
            $table->index(['province_id', 'regency_id', 'district_id', 'village_id'], 'idx_orders_region_hierarchy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regencies', function (Blueprint $table) {
            $table->dropIndex('idx_regencies_province_name');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropIndex('idx_districts_regency_name');
        });

        Schema::table('villages', function (Blueprint $table) {
            $table->dropIndex('idx_villages_district_name');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_province_payment');
            $table->dropIndex('idx_orders_regency_payment');
            $table->dropIndex('idx_orders_region_cascade');
            $table->dropIndex('idx_orders_region_hierarchy');
        });
    }
};
