<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\DisplaySetting;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'heroBanners' => \App\Models\HeroBanner::where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'displaySetting' => DisplaySetting::active(),
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take(6)
                ->get(),
            'featuredProducts' => Product::query()
                ->with(['category', 'stocks'])
                ->where('status', 'active')
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->take(4)
                ->get(),
            'activeProductCount' => Product::query()
                ->where('status', 'active')
                ->count(),
            'latestArticles' => Article::published()
                ->with('category')
                ->orderByDesc('published_at')
                ->take(2)
                ->get(),
        ]);
    }
}
