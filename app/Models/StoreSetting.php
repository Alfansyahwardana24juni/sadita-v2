<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'name',
        'whatsapp_number',
        'payment_methods',
    ];

    protected $casts = [
        'payment_methods' => 'array',
    ];

    public static function active(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            ['name' => 'Pengaturan Toko', 'whatsapp_number' => config('sadita.whatsapp_number')]
        );
    }
}
