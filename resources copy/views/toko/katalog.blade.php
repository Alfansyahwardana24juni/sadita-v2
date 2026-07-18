@extends('layouts.toko')

@section('title', 'Katalog Produk | SADITA Toko')

@section('content')
<!-- Kategori Filter -->
<section class="px-5 py-6 bg-surface-container-low">
    <h2 class="font-bold text-lg mb-3">Kategori</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
        <a href="/toko/katalog" class="px-4 py-2 {{ empty($activeCategory) ? 'bg-primary text-white' : 'bg-white border border-gray-200 hover:bg-gray-50' }} rounded-lg text-sm font-bold text-center">Semua</a>
        @forelse(($categories ?? collect()) as $category)
            <a href="/toko/kategori/{{ $category->slug }}" class="px-4 py-2 {{ ! empty($activeCategory) && $activeCategory->id === $category->id ? 'bg-primary text-white' : 'bg-white border border-gray-200 hover:bg-gray-50' }} rounded-lg text-sm font-bold text-center">{{ $category->name }}</a>
        @empty
            <a href="/toko/kategori/antibiotik" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-center hover:bg-gray-50">Antibiotik</a>
            <a href="/toko/kategori/vitamin" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-center hover:bg-gray-50">Vitamin</a>
            <a href="/toko/kategori/desinfektan" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-center hover:bg-gray-50">Desinfektan</a>
        @endforelse
    </div>
</section>

<!-- Filter & Sort -->
<section class="px-5 py-4 flex gap-2 border-b border-gray-200">
    <button class="flex-1 px-3 py-2 bg-surface-container rounded-lg text-sm font-bold flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-sm">sort</span>
        Sort
    </button>
    <button class="flex-1 px-3 py-2 bg-surface-container rounded-lg text-sm font-bold flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-sm">tune</span>
        Filter
    </button>
</section>

<!-- Products Grid -->
<section class="px-5 py-8">
    <h2 class="font-bold text-lg mb-4">{{ empty($activeCategory) ? 'Semua Produk' : 'Produk ' . $activeCategory->name }}</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $fallbackProducts = collect([
                (object) ['name' => 'Cyprotil', 'slug' => 'cyprotil', 'category' => (object) ['name' => 'Antibiotik'], 'price' => 125000, 'original_price' => 156000, 'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde0b?w=200&h=200&fit=crop'],
                (object) ['name' => 'Tetravet', 'slug' => 'tetravet', 'category' => (object) ['name' => 'Antibiotik'], 'price' => 95000, 'original_price' => 120000, 'image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=200&h=200&fit=crop'],
            ]);
            $catalogProducts = $products ?? $fallbackProducts;
        @endphp
        @forelse($catalogProducts as $product)
            <div class="product-card">
                <div class="relative overflow-hidden bg-gray-100 h-40">
                    <a href="/toko/produk/{{ $product->slug }}">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
                    </a>
                </div>
                <div class="p-3">
                    <a href="/toko/produk/{{ $product->slug }}" class="font-headline-md text-sm line-clamp-2 mb-1 hover:text-primary">{{ $product->name }}</a>
                    <p class="text-xs text-gray-600 line-clamp-1 mb-2">{{ $product->category->name ?? 'Produk' }}</p>
                    <div class="flex items-end justify-between">
                        <div>
                            <span class="font-bold text-primary text-sm">Rp {{ number_format($product->price) }}</span>
                            @if($product->original_price)
                                <span class="text-[10px] text-gray-400 line-through ml-1">Rp {{ number_format($product->original_price) }}</span>
                            @endif
                        </div>
                        <button class="bg-primary text-white p-2 rounded-lg hover:bg-primary-container transition-colors active:scale-90">
                            <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-sm text-gray-600">Belum ada produk untuk kategori ini.</p>
        @endforelse
    </div>

    @if(isset($products) && method_exists($products, 'links'))
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</section>
@endsection
