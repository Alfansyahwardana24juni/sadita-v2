<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (ProductStock $stock) {
            if ($stock->product_unit_id && ! $stock->product_id) {
                $stock->product_id = ProductUnit::whereKey($stock->product_unit_id)->value('product_id');
            }
        });
    }

    protected $fillable = [
        'product_id',
        'product_unit_id',
        'warehouse_id',
        'stock',
        'reserved_stock',
        'low_stock_threshold',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'reserved_stock' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
