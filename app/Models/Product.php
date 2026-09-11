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

    public $translatable = ['name', 'description', 'short_description', 'composition', 'pharmacology', 'indication', 'usage_instruction', 'dosage', 'storage_instruction'];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'composition',
        'pharmacology',
        'indication',
        'usage_instruction',
        'dosage',
        'storage_instruction',
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
        'brochure_image',
        'brochures',
        'sold_count',
        'status',
        'is_featured',
        'sort_order',
        'extra_specifications',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'compare_at_price' => 'integer',
            'sold_count' => 'integer',
            'is_featured' => 'boolean',
            'extra_specifications' => 'array',
            'brochures' => 'array',
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

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    /**
     * Unit aktif, terurut (dipakai untuk tampilan & harga "mulai dari").
     */
    public function activeUnits()
    {
        return $this->units
            ->where('is_active', true)
            ->sortBy([['sort_order', 'asc'], ['price', 'asc']])
            ->values();
    }

    public function getDefaultUnitAttribute(): ?ProductUnit
    {
        return $this->units->firstWhere('is_default', true)
            ?? $this->activeUnits()->first()
            ?? $this->units->first();
    }

    /**
     * Harga terendah dari unit aktif; fallback ke kolom price produk.
     */
    public function getPriceFromAttribute(): int
    {
        $min = $this->activeUnits()->min('price');

        return (int) ($min ?? $this->price);
    }

    /**
     * @return array{min:int,max:int}
     */
    public function priceRange(): array
    {
        $units = $this->activeUnits();

        if ($units->isEmpty()) {
            return ['min' => (int) $this->price, 'max' => (int) $this->price];
        }

        return ['min' => (int) $units->min('price'), 'max' => (int) $units->max('price')];
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

    public function getBrochureUrlAttribute(): ?string
    {
        if (! $this->brochure_image) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($this->brochure_image, ['http://', 'https://'])) {
            return $this->brochure_image;
        }

        return \Illuminate\Support\Facades\Storage::url($this->brochure_image);
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
