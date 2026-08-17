<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationLog extends Model
{
    protected $fillable = [
        'session_id',
        'animal_type',
        'messages',
        'recommended_products',
        'ip_address',
    ];

    protected $casts = [
        'messages' => 'array',
        'recommended_products' => 'array',
    ];

    public function addMessage(string $role, string $text): void
    {
        $messages = $this->messages ?? [];
        $messages[] = ['role' => $role, 'text' => $text, 'time' => now()->toISOString()];
        $this->messages = $messages;
        $this->save();
    }
}
