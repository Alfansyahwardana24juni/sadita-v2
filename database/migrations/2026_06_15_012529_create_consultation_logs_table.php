<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('animal_type')->nullable();
            $table->json('messages'); // [{role: 'user'|'model', text: '...'}]
            $table->json('recommended_products')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_logs');
    }
};
