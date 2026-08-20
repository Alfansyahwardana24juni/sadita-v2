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
        Schema::table('ai_agent_settings', function (Blueprint $table) {
            // Business Information
            $table->string('business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_website')->nullable();
            $table->string('business_hours')->nullable();
            
            // Additional Identity & Style
            $table->string('allowed_emoji')->nullable();
            $table->string('no_emoji')->nullable();
            $table->string('number_format')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_agent_settings', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'business_address',
                'business_phone',
                'business_email',
                'business_website',
                'business_hours',
                'allowed_emoji',
                'no_emoji',
                'number_format',
            ]);
        });
    }
};
