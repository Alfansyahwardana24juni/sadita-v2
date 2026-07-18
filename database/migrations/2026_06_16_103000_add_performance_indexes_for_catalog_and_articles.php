<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['status', 'category_id', 'sort_order'], 'products_status_category_sort_idx');
            $table->index(['status', 'price'], 'products_status_price_idx');
            $table->index(['status', 'reviews_count'], 'products_status_reviews_idx');
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->index(['warehouse_id', 'product_id'], 'product_stocks_warehouse_product_idx');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->index(['status', 'published_at'], 'articles_status_published_idx');
            $table->index(['category_id', 'published_at'], 'articles_category_published_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_status_category_sort_idx');
            $table->dropIndex('products_status_price_idx');
            $table->dropIndex('products_status_reviews_idx');
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropIndex('product_stocks_warehouse_product_idx');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_status_published_idx');
            $table->dropIndex('articles_category_published_idx');
        });
    }
};

