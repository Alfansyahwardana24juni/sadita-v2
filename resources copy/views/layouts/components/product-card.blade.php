<div class="product-card">
    <div class="relative overflow-hidden bg-gray-100 h-48">
        <a href="/toko/produk/{{ $slug ?? 'cyprotil' }}">
            <img src="{{ $image ?? 'https://via.placeholder.com/200x200' }}" alt="{{ $name ?? 'Product' }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
        </a>
    </div>
    <div class="p-3">
        <a href="/toko/produk/{{ $slug ?? 'cyprotil' }}" class="block font-headline-md text-sm line-clamp-2 mb-1 hover:text-primary">{{ $name ?? 'Product Name' }}</a>
        <p class="text-xs text-gray-600 line-clamp-1 mb-2">{{ $category ?? 'Category' }}</p>
        <div class="flex items-end justify-between">
            <div>
                <span class="font-bold text-primary">Rp {{ number_format($price ?? 0) }}</span>
                @if($originalPrice ?? null)
                    <span class="text-xs text-gray-400 line-through ml-1">Rp {{ number_format($originalPrice) }}</span>
                @endif
            </div>
            <button class="bg-primary text-white p-2 rounded-lg hover:bg-primary-container transition-colors">
                <span class="material-symbols-outlined text-sm">add_shopping_cart</span>
            </button>
        </div>
    </div>
</div>
