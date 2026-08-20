<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiAgentSetting extends Model
{
    protected $fillable = [
        'name',
        'role',
        'language',
        'style',
        'tone',
        'addressing',
        'instructions',
        'response_format',
        'scope_rules',
        'contact_label',
        'contact_value',
        'is_active',
        // Business Info
        'business_name',
        'business_address',
        'business_phone',
        'business_email',
        'business_website',
        'business_hours',
        // Additional Identity
        'allowed_emoji',
        'no_emoji',
        'number_format',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function active(): ?self
    {
        return static::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }
}

