<x-layouts.toko title="{{ $product->name }} - SADITA Toko">
    @php
        $units = $product->units->where('is_active', true)
            ->sortBy([['sort_order', 'asc'], ['price', 'asc']])
            ->values();
        if ($units->isEmpty()) {
            $units = $product->units->sortBy('sort_order')->values();
        }

        $availableFor = function ($unit) use ($warehouse) {
            $row = $warehouse
                ? $unit->stocks->firstWhere('warehouse_id', $warehouse->id)
                : null;
            if ($row) {
                return max(0, (int) $row->stock - (int) $row->reserved_stock);
            }
            return $warehouse ? 0 : (int) $unit->stocks->sum('stock');
        };

        $defaultUnit = $units->firstWhere('is_default', true) ?? $units->first();
        $stock = $defaultUnit ? $availableFor($defaultUnit) : 0;

        $unitsPayload = $units->map(fn ($unit) => [
            'id' => $unit->slug,
            'name' => $unit->name,
            'category' => $product->category->name,
            'pack' => $unit->name,
            'price' => (int) $unit->price,
            'compare_at_price' => $unit->compare_at_price ? (int) $unit->compare_at_price : null,
            'stock' => $availableFor($unit),
            'image' => $product->image,
            'href' => route('toko.produk.show', $product),
        ])->values();

        $range = $product->priceRange();
        $hasRange = $range['min'] !== $range['max'];

        $whatsappUrl = $displaySetting?->whatsappUrl($product->name);
        $kemasan = $product->pack ?: ($units->isNotEmpty() ? $units->pluck('name')->implode(', ') : null);
    @endphp

    <div class="pb-32">
        {{-- Header --}}
        <header class="sticky top-0 z-50 flex h-16 items-center justify-between border-b border-line bg-white/95 px-5 backdrop-blur">
            <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('toko.katalog') }}'" class="flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Kembali ke katalog"><span class="material-symbols-outlined">arrow_back</span></button>
            <div class="text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-muted">Detail Produk</p>
                <h1 class="text-base font-black text-primary">SADITA Toko</h1>
            </div>
            <a href="{{ route('cart.index') }}" class="relative flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Keranjang">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span data-cart-count class="absolute right-1 top-1 rounded-full bg-primary px-1.5 py-0.5 text-[9px] font-bold text-white">0</span>
            </a>
        </header>

        <main>
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1 overflow-x-auto px-5 py-3 text-xs font-semibold text-muted scrollbar-hide" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('toko.home') }}">Toko</a>
                <span>/</span>
                <a href="{{ route('toko.katalog', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                <span>/</span>
                <span class="shrink-0 text-primary">{{ $product->name }}</span>
            </nav>

            {{-- 1. Gambar Produk / Flyer --}}
            <section class="bg-white">
                <div class="relative aspect-square border-y border-line bg-surface">
                    <img id="mainProductImage" class="h-full w-full object-cover" alt="{{ $product->name }}" src="{{ $product->image_url }}">
                    <span class="absolute left-4 top-4 rounded bg-primary px-3 py-1 text-[10px] font-black uppercase tracking-wide text-white">{{ $product->category->name }}</span>
                </div>
            </section>

            {{-- 2. Nama Produk & Kategori & Rentang Harga (Tanpa card duplikasi Cocok Untuk / Indikasi) --}}
            <section class="px-5 pt-5">
                <div class="mb-2 flex flex-wrap gap-2">
                    <span class="rounded-full bg-primary px-3 py-1 text-[10px] font-black uppercase tracking-wide text-white">{{ $product->category->name }}</span>
                    @if($product->animal_type)
                        <span class="rounded-full bg-surface px-3 py-1 text-[10px] font-black uppercase tracking-wide text-muted">{{ $product->animal_type }}</span>
                    @endif
                </div>

                <h2 class="text-2xl font-black leading-tight tracking-tight">{{ $product->name }}</h2>

                @if($product->short_description)
                    <p class="mt-2 text-sm leading-6 text-muted">{{ $product->short_description }}</p>
                @endif

                {{-- Rentang Harga saja dekat judul (tanpa rincian varian) --}}
                <div class="mt-3">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Rentang Harga</p>
                    <p class="mt-1 text-2xl font-black text-primary">
                        Rp {{ number_format($range['min'], 0, ',', '.') }}@if($hasRange) - Rp {{ number_format($range['max'], 0, ',', '.') }}@endif
                    </p>
                </div>
            </section>

            {{-- 3. Container Scrollable Box Detail Produk (max-h-96 flex-col dengan tombol brosur sticky di bawah) --}}
            <section class="mt-4 px-5">
                <div class="rounded-xl border border-line bg-white shadow-sm overflow-hidden flex flex-col max-h-96 sm:max-h-[28rem]">
                    <div class="border-b border-line px-4 py-3 bg-surface/50 shrink-0">
                        <h3 class="text-sm font-black text-primary">Informasi & Detail Produk</h3>
                    </div>

                    {{-- Konten teks yang dapat di-scroll --}}
                    <div class="flex-1 overflow-y-auto overscroll-contain p-4 space-y-4 text-sm divide-y divide-line/60" id="productDetailScroll">

                        {{-- a. Indikasi --}}
                        @if($product->indication)
                            <div class="pt-3 first:pt-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-primary">medical_information</span>
                                    <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Indikasi</h4>
                                </div>
                                <p class="text-sm font-medium leading-relaxed text-ink pl-6">{{ $product->indication }}</p>
                            </div>
                        @endif

                        {{-- b. Komposisi --}}
                        @if($product->composition)
                            <div class="pt-3 first:pt-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-amber">science</span>
                                    <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Komposisi</h4>
                                </div>
                                <p class="text-sm font-medium leading-relaxed text-ink pl-6">{{ $product->composition }}</p>
                            </div>
                        @endif

                        {{-- c. Cara Kerja Obat --}}
                        @if($product->pharmacology)
                            <div class="pt-3 first:pt-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-blue-600">biotech</span>
                                    <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Cara Kerja Obat</h4>
                                </div>
                                <p class="text-sm font-medium leading-relaxed text-ink pl-6">{{ $product->pharmacology }}</p>
                            </div>
                        @endif

                        {{-- d. Dosis dan Aturan Pakai --}}
                        @if($product->dosage || $product->usage_instruction)
                            <div class="pt-3 first:pt-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-moss">medication</span>
                                    <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Dosis dan Aturan Pakai</h4>
                                </div>
                                <div class="text-sm font-medium leading-relaxed text-ink pl-6 space-y-1">
                                    @if($product->dosage)
                                        <p><strong class="text-muted font-bold">Dosis:</strong> {{ $product->dosage }}</p>
                                    @endif
                                    @if($product->usage_instruction)
                                        <p><strong class="text-muted font-bold">Aturan Pakai:</strong> {{ $product->usage_instruction }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- e. Penyimpanan --}}
                        @if($product->storage_instruction)
                            <div class="pt-3 first:pt-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-purple-600">inventory_2</span>
                                    <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Penyimpanan</h4>
                                </div>
                                <p class="text-sm font-medium leading-relaxed text-ink pl-6">{{ $product->storage_instruction }}</p>
                            </div>
                        @endif

                        {{-- f. Kemasan --}}
                        @if($kemasan)
                            <div class="pt-3 first:pt-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="material-symbols-outlined text-[18px] text-teal-600">package_2</span>
                                    <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Kemasan</h4>
                                </div>
                                <p class="text-sm font-medium leading-relaxed text-ink pl-6">{{ $kemasan }}</p>
                            </div>
                        @endif

                    </div>

                    {{-- g. [Tombol Brosur]: Sticky di bagian bawah dalam box detail --}}
                    <div class="sticky bottom-0 shrink-0 bg-white/95 backdrop-blur border-t border-line/60 px-4 py-2.5 z-10">
                        <x-brochure-modal :product="$product" :buy-url="null"
                            trigger-class="flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-primary bg-white text-sm font-bold text-primary hover:bg-primary/5 active:scale-95 transition-all shadow-sm" />
                    </div>
                </div>
            </section>

            {{-- 4. Tombol Aksi Utama (Di luar box scrollable) --}}
            <section class="mt-5 px-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Button 1: "Beli di Toko Sadita" --}}
                    @if($defaultUnit && $stock > 0)
                        <button type="button" data-buy-now class="flex h-12 items-center justify-center gap-2 rounded-xl bg-primary px-4 text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[19px]">shopping_cart</span>
                            Beli di Toko Sadita
                        </button>
                    @else
                        <button type="button" data-add-cart class="flex h-12 items-center justify-center gap-2 rounded-xl bg-primary px-4 text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[19px]">shopping_cart</span>
                            Beli di Toko Sadita
                        </button>
                    @endif

                    {{-- Button 2: "Konsultasi ke WhatsApp" --}}
                    @if($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="flex h-12 items-center justify-center gap-2 rounded-xl bg-[#25D366] px-4 text-sm font-black text-white shadow-lg shadow-[#25D366]/20 hover:bg-[#22c55e] active:scale-95 transition-all">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Konsultasi ke WhatsApp
                        </a>
                    @else
                        <a href="{{ route('saditacare') }}" class="flex h-12 items-center justify-center gap-2 rounded-xl bg-[#25D366] px-4 text-sm font-black text-white shadow-lg shadow-[#25D366]/20 hover:bg-[#22c55e] active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[19px]">forum</span>
                            Konsultasi ke WhatsApp
                        </a>
                    @endif
                </div>
            </section>

            {{-- 5. Section Rekomendasi / Produk Terkait --}}
            @if ($relatedProducts->isNotEmpty())
                <section class="mt-8 px-5 pb-8">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-black">Produk Terkait</h3>
                        <a href="{{ route('toko.katalog', ['category' => $product->category->slug]) }}" class="text-xs font-bold uppercase tracking-wide text-primary">Lihat Semua</a>
                    </div>
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach ($relatedProducts as $relatedProduct)
                            @php
                                $relRange = $relatedProduct->priceRange();
                                $relHasRange = $relRange['min'] !== $relRange['max'];
                            @endphp
                            <a href="{{ route('toko.produk.show', $relatedProduct) }}" class="w-40 shrink-0 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                                <div class="h-32 w-full overflow-hidden bg-surface">
                                    <img class="h-32 w-full object-cover" alt="{{ $relatedProduct->name }}" src="{{ $relatedProduct->image_url }}" loading="lazy">
                                </div>
                                <div class="p-3">
                                    <h4 class="line-clamp-2 text-sm font-bold">{{ $relatedProduct->name }}</h4>
                                    <p class="mt-2 text-sm font-black text-primary">
                                        Rp {{ number_format($relRange['min'], 0, ',', '.') }}@if($relHasRange) - Rp {{ number_format($relRange['max'], 0, ',', '.') }}@endif
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </div>

    <script>
        (() => {
            const units = {!! $unitsPayload->toJson() !!};
            let qty = 1;
            let selected = units.find(u => u.stock > 0) || units[0] || null;

            const addBtns = Array.from(document.querySelectorAll('[data-add-cart]'));
            const buyBtns = Array.from(document.querySelectorAll('[data-buy-now]'));

            addBtns.forEach(btn => btn.addEventListener('click', () => {
                if (!selected) return;
                saditaAddToCart(selected, qty);
            }));

            buyBtns.forEach(btn => btn.addEventListener('click', async () => {
                if (!selected) return;
                const original = btn.innerHTML;
                btn.disabled = true;
                btn.classList.add('opacity-70');
                btn.textContent = 'Memproses...';
                try {
                    await saditaAddToCart(selected, qty);
                    window.location.href = @json(route('checkout'));
                } catch (e) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-70');
                    btn.innerHTML = original;
                }
            }));
        })();
    </script>
</x-layouts.toko>
