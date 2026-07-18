<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'subtitle',
        'description',
        'benefits',
        'specifications',
        'price',
        'original_price',
        'stock',
        'sold_count',
        'condition',
        'image',
        'gallery',
        'seller_name',
        'warehouse',
        'rating',
        'review_count',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'benefits' => 'array',
        'specifications' => 'array',
        'gallery' => 'array',
        'price' => 'integer',
        'original_price' => 'integer',
        'stock' => 'integer',
        'sold_count' => 'integer',
        'rating' => 'decimal:1',
        'review_count' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->original_price || $this->original_price <= $this->price) {
            return 0;
        }

        return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
    }
}
