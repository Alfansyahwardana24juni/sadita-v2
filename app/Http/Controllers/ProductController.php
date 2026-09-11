<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DisplaySetting;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $warehouse = $this->selectedWarehouse();
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->get();
        $sort = $request->string('sort')->toString() ?: 'popular';

        $unitPriceSubquery = ProductUnit::query()
            ->select('price')
            ->whereColumn('product_units.product_id', 'products.id')
            ->where('is_active', true)
            ->orderBy('price')
            ->limit(1);

        $productsQuery = Product::query()
            ->with([
                'category',
                'units',
                'stocks' => fn ($query) => $query->where('warehouse_id', $warehouse?->id),
            ])
            ->where('status', 'active')
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $request->string('category')));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%' . $request->string('q')->lower() . '%';
                $query->where(function ($searchQuery) use ($keyword) {
                    $searchQuery
                        ->whereRaw('LOWER(name) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(short_description) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(symptom_tags) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(indication) LIKE ?', [$keyword]);
                });
            })
            ->when($sort === 'price-low', fn ($query) => $query->orderBy($unitPriceSubquery))
            ->when($sort === 'price-high', fn ($query) => $query->orderByDesc($unitPriceSubquery))
            ->when($sort === 'popular', fn ($query) => $query->orderByDesc('sold_count'))
            ->when(! in_array($sort, ['price-low', 'price-high', 'popular', 'stock-high'], true), fn ($query) => $query->orderBy('sort_order'));

        if ($sort === 'stock-high') {
            $productsQuery
                ->leftJoin('product_stocks as sort_stocks', function ($join) use ($warehouse) {
                    $join->on('sort_stocks.product_id', '=', 'products.id');
                    if ($warehouse?->id) {
                        $join->where('sort_stocks.warehouse_id', '=', $warehouse->id);
                    }
                })
                ->select('products.*')
                ->selectRaw('COALESCE(sort_stocks.stock - sort_stocks.reserved_stock, 0) as available_stock_sort')
                ->orderByDesc('available_stock_sort')
                ->orderBy('products.sort_order');
        }

        $products = $productsQuery
            ->paginate(12)
            ->withQueryString();

        return view('toko.katalog', [
            'displaySetting' => DisplaySetting::active(),
            'categories' => $categories,
            'products' => $products,
            'warehouse' => $warehouse,
            'activeCategory' => $request->string('category')->toString(),
            'query' => $request->string('q')->toString(),
            'sort' => $sort,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'units.stocks.warehouse', 'stocks.warehouse']);

        $relatedProducts = Product::query()
            ->with(['category', 'units'])
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->orderByDesc('sold_count')
            ->take(4)
            ->get();

        return view('toko.detail-produk', [
            'product' => $product,
            'warehouse' => $this->selectedWarehouse(),
            'relatedProducts' => $relatedProducts,
        ]);
    }

    private function selectedWarehouse(): ?Warehouse
    {
        $warehouseId = session('warehouse_id');

        return Warehouse::query()
            ->where('is_active', true)
            ->when($warehouseId, fn ($query) => $query->where('id', $warehouseId))
            ->first() ?? Warehouse::query()->where('is_active', true)->orderBy('id')->first();
    }
}
