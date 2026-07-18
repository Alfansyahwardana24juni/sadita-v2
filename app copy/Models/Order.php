<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'warehouse',
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'total' => 'integer',
    ];
}
