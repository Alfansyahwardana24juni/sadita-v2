<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('toko.katalog', [
            'categories' => $categories,
            'products' => $products,
            'activeCategory' => null,
        ]);
    }

    public function category(string $slug)
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $activeCategory = Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::query()
            ->with('category')
            ->where('category_id', $activeCategory->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('toko.katalog', [
            'categories' => $categories,
            'products' => $products,
            'activeCategory' => $activeCategory,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::query()
            ->with(['category', 'reviews' => fn ($query) => $query->where('is_published', true)->latest('reviewed_at')])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->limit(4)
            ->get();

        return view('toko.detail-produk', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
