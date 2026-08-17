<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_agent_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('SADITA AI');
            $table->string('role')->nullable();
            $table->string('language')->default('Bahasa Indonesia');
            $table->string('style')->nullable();
            $table->string('tone')->nullable();
            $table->string('addressing')->nullable();
            $table->text('instructions')->nullable();
            $table->text('response_format')->nullable();
            $table->text('scope_rules')->nullable();
            $table->string('contact_label')->nullable();
            $table->string('contact_value')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_agent_settings');
    }
};

