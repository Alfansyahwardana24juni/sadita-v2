@props(['product'])

@php
    $stock = $product->stocks->first()?->stock ?? $product->stocks->sum('stock');
    $defaultUnit = $product->default_unit;
    $priceFrom = $product->price_from;
    $range = $product->priceRange();
    $hasRange = $range['min'] !== $range['max'];
    $productPayload = $defaultUnit ? json_encode([
        'id' => $defaultUnit->slug,
        'name' => $product->name,
        'category' => $product->category->name,
        'pack' => $defaultUnit->name,
        'price' => $defaultUnit->price,
        'stock' => $stock,
        'image' => $product->image,
        'href' => route('toko.produk.show', $product),
    ], JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT) : null;
@endphp

<article class="flex flex-col overflow-hidden rounded-xl border border-line/50 bg-white shadow-sm transition-transform active:scale-[0.98]">
    <a href="{{ route('toko.produk.show', $product) }}" class="block relative aspect-square w-full bg-surface">
        <img class="absolute inset-0 h-full w-full object-cover" alt="{{ $product->name }}" src="{{ $product->image_url }}" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/400x400/F1F5F9/94A3B8?text=No+Image';">
        <div class="absolute left-0 top-0">
            <span class="rounded-br-lg bg-primary/90 px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-white backdrop-blur-sm">
                {{ $product->category->name }}
            </span>
        </div>
    </a>

    <div class="flex flex-1 flex-col p-2">
        <a href="{{ route('toko.produk.show', $product) }}" class="block flex-1">
            <h3 class="line-clamp-2 min-h-[32px] text-xs leading-4 text-ink">{{ $product->name }}</h3>

            <div class="mt-1 flex flex-wrap items-baseline gap-x-1">
                <p class="text-sm font-extrabold text-primary">
                    @if($hasRange)Mulai @endif Rp{{ number_format($priceFrom, 0, ',', '.') }}
                </p>
            </div>

            <div class="mt-1 flex items-center gap-1 text-[10px] text-muted">
                <span>{{ $product->units->where('is_active', true)->count() ?: 1 }} pilihan unit</span>
                <span>|</span>
                <span>Terjual {{ $product->sold_count ?? 0 }}+</span>
            </div>
        </a>

        <div class="mt-2.5">
            @if($defaultUnit && $stock > 0)
                <button type="button" class="add-cart flex h-7 w-full items-center justify-center gap-1 rounded border border-primary text-[10px] font-bold text-primary transition-all hover:bg-primary/5 active:scale-95"
                    data-product='{{ $productPayload }}'>
                    + Keranjang
                </button>
            @elseif($defaultUnit)
                <button type="button" disabled class="flex h-7 w-full items-center justify-center gap-1 rounded bg-surface text-[10px] font-bold text-muted cursor-not-allowed">
                    Habis
                </button>
            @else
                <a href="{{ route('toko.produk.show', $product) }}" class="flex h-7 w-full items-center justify-center gap-1 rounded border border-primary text-[10px] font-bold text-primary">
                    Lihat Pilihan
                </a>
            @endif
        </div>
    </div>
</article>
