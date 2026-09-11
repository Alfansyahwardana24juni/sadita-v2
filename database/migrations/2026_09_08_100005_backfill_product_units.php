<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Buat 1 unit default untuk tiap produk lama, lalu tautkan stok & order item
     * yang sudah ada ke unit tersebut. Aman dijalankan saat tabel kosong.
     */
    public function up(): void
    {
        $now = now();
        $usedSlugs = DB::table('product_units')->pluck('slug')->all();
        $usedSlugs = array_flip($usedSlugs);

        DB::table('products')->orderBy('id')->chunkById(200, function ($products) use ($now, &$usedSlugs) {
            foreach ($products as $product) {
                if (DB::table('product_units')->where('product_id', $product->id)->exists()) {
                    continue;
                }

                $slug = $product->slug ?: Str::slug($product->name) ?: ('produk-' . $product->id);
                if (isset($usedSlugs[$slug])) {
                    $slug .= '-' . $product->id;
                }
                $usedSlugs[$slug] = true;

                $unitId = DB::table('product_units')->insertGetId([
                    'product_id' => $product->id,
                    'name' => $product->pack ?: $product->name,
                    'slug' => $slug,
                    'sku' => null,
                    'price' => (int) $product->price,
                    'compare_at_price' => $product->compare_at_price !== null ? (int) $product->compare_at_price : null,
                    'weight' => (int) ($product->weight ?? 500),
                    'length' => (int) ($product->length ?? 0),
                    'width' => (int) ($product->width ?? 0),
                    'height' => (int) ($product->height ?? 0),
                    'is_default' => true,
                    'is_active' => ($product->status ?? 'active') === 'active',
                    'sort_order' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('product_stocks')
                    ->where('product_id', $product->id)
                    ->whereNull('product_unit_id')
                    ->update(['product_unit_id' => $unitId]);

                DB::table('order_items')
                    ->where('product_id', $product->id)
                    ->whereNull('product_unit_id')
                    ->update(['product_unit_id' => $unitId]);
            }
        });
    }

    public function down(): void
    {
        DB::table('product_stocks')->update(['product_unit_id' => null]);
        DB::table('order_items')->update(['product_unit_id' => null]);
        DB::table('product_units')->delete();
    }
};
