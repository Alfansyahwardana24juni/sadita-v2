<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = [
        'zone_name',
        'cities',
        'shipping_methods',
        'free_shipping_threshold',
        'flat_rate',
        'is_active',
    ];

    protected $casts = [
        'cities' => 'array',
        'shipping_methods' => 'array',
        'free_shipping_threshold' => 'decimal:2',
        'flat_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Check if a city is in this zone
     */
    public function hasCity(string $city): bool
    {
        if (in_array('*', $this->cities)) {
            return true;
        }

        return in_array(strtolower($city), array_map('strtolower', $this->cities));
    }

    /**
     * Check if a shipping method is available in this zone
     */
    public function hasShippingMethod(string $method): bool
    {
        return in_array($method, $this->shipping_methods);
    }
}
