<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Category;
use App\Models\DisplaySetting;
use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function tentang(): View
    {
        return view('pages.tentang', [
            'displaySetting' => DisplaySetting::active(),
        ]);
    }

    public function produk(Request $request): View
    {
        $search = trim($request->string('q')->toString());

        $categories = Category::query()
            ->where('is_active', true)
            ->withCount([
                'products as active_products_count' => fn ($query) => $query->where('status', 'active'),
            ])
            ->orderBy('sort_order')
            ->get();

        $products = null;
        if ($search !== '') {
            $keyword = '%' . mb_strtolower($search) . '%';
            $products = Product::query()
                ->where('status', 'active')
                ->with('category')
                ->where(function ($query) use ($keyword) {
                    $query->whereRaw('LOWER(name) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(short_description) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(symptom_tags) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(indication) LIKE ?', [$keyword]);
                })
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->paginate(12)
                ->withQueryString();
        }

        return view('pages.produk', compact('categories', 'search', 'products'));
    }

    public function produkKategori(Category $category): View
    {
        $products = Product::query()
            ->with('category')
            ->where('status', 'active')
            ->where('category_id', $category->id)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        return view('pages.produk-kategori', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function produkDetail(Category $category, Product $product): View
    {
        abort_if($product->category_id !== $category->id, 404);

        $product->load(['category', 'reviews', 'stocks']);

        $relatedProducts = Product::query()
            ->with('category')
            ->where('status', 'active')
            ->where('category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('is_featured')
            ->orderByDesc('reviews_count')
            ->take(4)
            ->get();

        return view('pages.produk-detail', [
            'category' => $category,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'stock' => $product->stocks->sum('stock'),
        ]);
    }

    public function artikel(Request $request): View
    {
        $activeCategory = $request->string('category')->toString();
        $search = trim($request->string('q')->toString());

        $articles = Article::published()
            ->with('category')
            ->when($activeCategory !== '', function ($query) use ($activeCategory) {
                $query->whereHas('category', function ($categoryQuery) use ($activeCategory) {
                    $categoryQuery->where('slug', $activeCategory);
                });
            })
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . mb_strtolower($search) . '%';
                $query->where(function ($searchQuery) use ($keyword) {
                    $searchQuery
                        ->whereRaw('LOWER(title) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(excerpt) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(content) LIKE ?', [$keyword]);
                });
            })
            ->orderByDesc('published_at')
            ->paginate(8)
            ->withQueryString();

        $categories = ArticleCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.artikel', compact('articles', 'categories', 'activeCategory', 'search'));
    }

    public function artikelShow(Article $article): View
    {
        $isPublishedAndVisible = $article->status === 'published'
            && (! $article->published_at || $article->published_at->isPast());
        abort_unless($isPublishedAndVisible, 404);

        $article->load('category');
        $article->increment('views_count');

        $relatedArticles = Article::published()
            ->with('category')
            ->where('id', '!=', $article->id)
            ->when($article->category_id, function ($query) use ($article) {
                $query->where('category_id', $article->category_id);
            })
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('pages.artikel-detail', [
            'article' => $article->fresh(['category']),
            'relatedArticles' => $relatedArticles,
            'title' => $article->title . ' - Artikel SADITA',
            'description' => \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->content), 160),
            'ogImage' => str_starts_with($article->featured_image, 'http') ? $article->featured_image : \Illuminate\Support\Facades\Storage::url($article->featured_image),
        ]);
    }

    public function chat(): View
    {
        return view('pages.chat');
    }

    public function saditacare(Request $request): View
    {
        $log = \App\Models\ConsultationLog::where('session_id', $request->session()->getId())
            ->whereDate('created_at', now()->startOfDay())
            ->first();
            
        $chatHistory = $log ? ($log->messages ?? []) : [];
        $animalType = $log ? $log->animal_type : null;

        return view('pages.saditacare', compact('chatHistory', 'animalType'));
    }

    public function checkout(): View
    {
        return view('toko.checkout');
    }
}
