<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'rating',
        'comment',
        'reviewed_at',
        'is_published',
    ];

    protected $casts = [
        'rating' => 'integer',
        'reviewed_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
