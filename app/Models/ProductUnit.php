<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductUnit extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (ProductUnit $unit) {
            if (! empty($unit->slug)) {
                return;
            }

            $base = Str::slug(trim(
                (Product::find($unit->product_id)?->slug ?? 'unit') . ' ' . $unit->name
            )) ?: 'unit';

            $slug = $base;
            $i = 2;
            while (static::where('slug', $slug)->when($unit->exists, fn ($q) => $q->whereKeyNot($unit->getKey()))->exists()) {
                $slug = $base . '-' . $i++;
            }

            $unit->slug = $slug;
        });
    }

    protected $fillable = [
        'product_id',
        'name',
        'slug',
        'sku',
        'price',
        'compare_at_price',
        'weight',
        'length',
        'width',
        'height',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'compare_at_price' => 'integer',
            'weight' => 'integer',
            'length' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Label lengkap "Nama Produk — Nama Unit" untuk keranjang & snapshot order.
     */
    public function fullName(): string
    {
        $productName = $this->product?->name;

        return $productName && $productName !== $this->name
            ? $productName . ' — ' . $this->name
            : $this->name;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->product?->image_url ?? asset('images/placeholder.jpg');
    }

    /**
     * Berat yang ditagih (maks dari berat aktual & berat volumetrik).
     */
    public function chargeableWeight(): int
    {
        $actualWeight = (int) ($this->weight ?: 500);
        $volumetricWeight = ($this->length > 0 && $this->width > 0 && $this->height > 0)
            ? (int) round(($this->length * $this->width * $this->height) / 6)
            : 0;

        return max($actualWeight, $volumetricWeight);
    }
}
