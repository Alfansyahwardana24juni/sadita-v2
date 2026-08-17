<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description', 'short_description', 'composition', 'indication', 'usage_instruction', 'dosage'];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'composition',
        'indication',
        'usage_instruction',
        'dosage',
        'withdrawal_time',
        'registration_number',
        'pack',
        'animal_type',
        'symptom_tags',
        'price',
        'compare_at_price',
        'weight',
        'length',
        'width',
        'height',
        'image',
        'rating',
        'reviews_count',
        'sold_count',
        'status',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'compare_at_price' => 'integer',
            'rating' => 'decimal:1',
            'reviews_count' => 'integer',
            'sold_count' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

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

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function totalStock(): int
    {
        return (int) $this->stocks->sum(fn (ProductStock $stock) => max(0, $stock->stock - $stock->reserved_stock));
    }
}
