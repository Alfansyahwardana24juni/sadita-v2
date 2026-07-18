<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('display_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Default');

            // Home
            $table->string('home_hero_badge')->nullable();
            $table->string('home_hero_title')->nullable();
            $table->text('home_hero_description')->nullable();
            $table->string('home_primary_cta_label')->nullable();
            $table->string('home_primary_cta_url')->nullable();
            $table->string('home_secondary_cta_label')->nullable();
            $table->string('home_secondary_cta_url')->nullable();
            $table->string('home_warehouse_cta_label')->nullable();
            $table->string('home_warehouse_cta_url')->nullable();
            $table->string('home_about_title')->nullable();
            $table->text('home_about_description')->nullable();

            $table->string('home_stat_experience_value')->nullable();
            $table->string('home_stat_experience_label')->nullable();
            $table->string('home_stat_product_value')->nullable();
            $table->string('home_stat_product_label')->nullable();
            $table->string('home_stat_partner_value')->nullable();
            $table->string('home_stat_partner_label')->nullable();
            $table->string('home_stat_volume_value')->nullable();
            $table->string('home_stat_volume_label')->nullable();

            // Toko
            $table->string('store_home_title')->nullable();
            $table->text('store_home_description')->nullable();
            $table->string('store_home_info_title')->nullable();
            $table->text('store_home_info_description')->nullable();
            $table->string('store_katalog_title')->nullable();
            $table->string('store_katalog_subtitle')->nullable();

            // Tentang
            $table->string('about_company_title')->nullable();
            $table->text('about_company_description')->nullable();
            $table->text('about_vision')->nullable();
            $table->text('about_mission_points')->nullable(); // 1 point per line
            $table->text('about_certification_points')->nullable(); // 1 point per line

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('display_settings');
    }
};

