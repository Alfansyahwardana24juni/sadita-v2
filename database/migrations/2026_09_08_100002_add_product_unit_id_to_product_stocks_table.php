<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->foreignId('product_unit_id')
                ->nullable()
                ->after('product_id')
                ->constrained()
                ->cascadeOnDelete();
        });

        // Stok kini per unit/SKU, bukan per produk: ganti unique key
        // (unique lama dipakai oleh FK product_id, jadi FK-nya dilepas & dipasang ulang).
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropUnique('product_stocks_product_id_warehouse_id_unique');
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->unique(['product_unit_id', 'warehouse_id']);
        });
    }

    public function down(): void
    {
        // Lepas kedua FK dulu supaya index yang mereka pakai bisa dihapus.
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropForeign(['product_unit_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropUnique(['product_unit_id', 'warehouse_id']);
            $table->dropIndex(['product_id']);
            $table->dropColumn('product_unit_id');
        });

        // Stok per-unit tidak bisa dipetakan balik 1:1 ke stok per-produk tanpa
        // kehilangan data: rapatkan baris duplikat (product_id, warehouse_id),
        // simpan baris dengan id terkecil, sebelum unique lama dipasang lagi.
        $duplicateIds = DB::table('product_stocks as ps')
            ->select('ps.id')
            ->join(DB::raw('(SELECT product_id, warehouse_id, MIN(id) AS keep_id FROM product_stocks GROUP BY product_id, warehouse_id) grp'), function ($join) {
                $join->on('ps.product_id', '=', 'grp.product_id')
                    ->on('ps.warehouse_id', '=', 'grp.warehouse_id')
                    ->on('ps.id', '!=', 'grp.keep_id');
            })
            ->pluck('ps.id');

        if ($duplicateIds->isNotEmpty()) {
            DB::table('product_stocks')->whereIn('id', $duplicateIds)->delete();
        }

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->unique(['product_id', 'warehouse_id']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }
};
