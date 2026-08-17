@props(['product'])

@php
    $stock = $product->stocks->first()?->stock ?? $product->stocks->sum('stock');
    $productPayload = json_encode([
        'id' => $product->slug,
        'name' => $product->name,
        'category' => $product->category->name,
        'pack' => $product->pack,
        'price' => $product->price,
        'stock' => $stock,
        'image' => $product->image,
        'href' => route('toko.produk.show', $product),
    ], JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
@endphp

<article class="flex flex-col overflow-hidden rounded-xl border border-line/50 bg-white shadow-sm transition-transform active:scale-[0.98]">
    <a href="{{ route('toko.produk.show', $product) }}" class="block relative aspect-square w-full bg-surface">
        <img class="absolute inset-0 h-full w-full object-cover" alt="{{ $product->name }}" src="{{ $product->image_url }}" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/400x400/F1F5F9/94A3B8?text=No+Image';">
        <div class="absolute left-0 top-0">
            <span class="rounded-br-lg bg-primary/90 px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-white backdrop-blur-sm">
                {{ $product->category->name }}
            </span>
        </div>
        @if($product->compare_at_price > $product->price)
            <div class="absolute right-0 top-0">
                <span class="rounded-bl-lg bg-amber px-1.5 py-1 text-[9px] font-black text-white shadow-sm">
                    -{{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}%
                </span>
            </div>
        @endif
    </a>
    
    <div class="flex flex-1 flex-col p-2">
        <a href="{{ route('toko.produk.show', $product) }}" class="block flex-1">
            <h3 class="line-clamp-2 min-h-[32px] text-xs leading-4 text-ink">{{ $product->name }}</h3>
            
            <div class="mt-1 flex flex-wrap items-baseline gap-x-1">
                <p class="text-sm font-extrabold text-primary">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                @if($product->compare_at_price > $product->price)
                    <p class="text-[9px] text-muted line-through">Rp{{ number_format($product->compare_at_price, 0, ',', '.') }}</p>
                @endif
            </div>

            <div class="mt-1 flex items-center gap-1 text-[10px] text-muted">
                <span class="material-symbols-outlined text-[12px] text-amber" style="font-variation-settings:'FILL' 1;">star</span>
                <span>{{ number_format($product->rating, 1) }}</span>
                <span>|</span>
                <span>Terjual {{ $product->sold_count ?? 0 }}+</span>
            </div>
        </a>
        
        <div class="mt-2.5">
            @if($stock > 0)
                <button type="button" class="add-cart flex h-7 w-full items-center justify-center gap-1 rounded border border-primary text-[10px] font-bold text-primary transition-all hover:bg-primary/5 active:scale-95"
                    data-product='{{ $productPayload }}'>
                    + Keranjang
                </button>
            @else
                <button type="button" disabled class="flex h-7 w-full items-center justify-center gap-1 rounded bg-surface text-[10px] font-bold text-muted cursor-not-allowed">
                    Habis
                </button>
            @endif
        </div>
    </div>
</article>
