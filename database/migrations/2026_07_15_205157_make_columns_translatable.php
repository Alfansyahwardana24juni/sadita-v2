<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Convert existing records for Categories
        $categories = DB::table('categories')->get();
        foreach ($categories as $cat) {
            DB::table('categories')->where('id', $cat->id)->update([
                'name' => json_encode(['id' => $cat->name, 'en' => $cat->name]),
                'description' => $cat->description ? json_encode(['id' => $cat->description, 'en' => $cat->description]) : null,
            ]);
        }

        // 2. Convert existing records for Products
        $products = DB::table('products')->get();
        foreach ($products as $prod) {
            DB::table('products')->where('id', $prod->id)->update([
                'name' => json_encode(['id' => $prod->name, 'en' => $prod->name]),
                'description' => $prod->description ? json_encode(['id' => $prod->description, 'en' => $prod->description]) : null,
                'short_description' => $prod->short_description ? json_encode(['id' => $prod->short_description, 'en' => $prod->short_description]) : null,
                'composition' => $prod->composition ? json_encode(['id' => $prod->composition, 'en' => $prod->composition]) : null,
                'indication' => $prod->indication ? json_encode(['id' => $prod->indication, 'en' => $prod->indication]) : null,
                'usage_instruction' => $prod->usage_instruction ? json_encode(['id' => $prod->usage_instruction, 'en' => $prod->usage_instruction]) : null,
                'dosage' => $prod->dosage ? json_encode(['id' => $prod->dosage, 'en' => $prod->dosage]) : null,
            ]);
        }

        // 3. Convert existing records for Articles
        $articles = DB::table('articles')->get();
        foreach ($articles as $art) {
            DB::table('articles')->where('id', $art->id)->update([
                'title' => json_encode(['id' => $art->title, 'en' => $art->title]),
                'excerpt' => $art->excerpt ? json_encode(['id' => $art->excerpt, 'en' => $art->excerpt]) : null,
                'content' => $art->content ? json_encode(['id' => $art->content, 'en' => $art->content]) : null,
            ]);
        }

        // 4. Change column types to JSON
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->nullable()->change();
            $table->json('short_description')->nullable()->change();
            $table->json('composition')->nullable()->change();
            $table->json('indication')->nullable()->change();
            $table->json('usage_instruction')->nullable()->change();
            $table->json('dosage')->nullable()->change();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('excerpt')->nullable()->change();
            $table->json('content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For brevity in rollback, we don't fully extract JSON strings back.
    }
};
