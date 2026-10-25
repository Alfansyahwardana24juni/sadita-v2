<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('display_settings', function (Blueprint $table) {
            $table->string('store_whatsapp_number')->nullable()->after('store_katalog_subtitle');
            $table->string('store_whatsapp_message')->nullable()->after('store_whatsapp_number');
        });
    }

    public function down(): void
    {
        Schema::table('display_settings', function (Blueprint $table) {
            $table->dropColumn(['store_whatsapp_number', 'store_whatsapp_message']);
        });
    }
};
