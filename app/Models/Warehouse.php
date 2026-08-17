<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'address',
        'city',
        'province',
        'postal_code',
        'rajaongkir_city_id',
        'phone',
        'whatsapp',
        'service_area',
        'delivery_estimate',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/placeholder.jpg'); // Provide a fallback if necessary
        }

        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return \Illuminate\Support\Facades\Storage::url($this->image);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get active warehouses
     */
    public static function active()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Get the nearest warehouse to a given city
     */
    public static function nearest(string $city)
    {
        // Simple logic: if city matches warehouse city, return that
        // Otherwise return first active warehouse
        return self::where('is_active', true)
            ->where('city', $city)
            ->first() ?? self::where('is_active', true)->first();
    }
}
