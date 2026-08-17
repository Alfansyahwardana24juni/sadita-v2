<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
            'featuredProducts' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->where('is_featured', true)
                ->limit(4)
                ->get(),
        ]);
    }
}
