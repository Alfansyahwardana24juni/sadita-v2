<x-layouts.toko title="{{ $product->name }} - SADITA Toko">
    @php
        $stock = $warehouse
            ? ($product->stocks->firstWhere('warehouse_id', $warehouse->id)?->stock ?? $product->stocks->sum('stock'))
            : $product->stocks->sum('stock');
        $detailProductPayload = json_encode([
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

    <div class="pb-32">
        <header class="sticky top-[52px] z-40 flex h-16 items-center justify-between border-b border-line bg-white/95 px-5 backdrop-blur">
            <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('toko.katalog') }}'" class="flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Kembali ke katalog"><span class="material-symbols-outlined">arrow_back</span></button>
            <div class="text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-muted">Detail Produk</p>
                <h1 class="text-base font-black text-primary">SADITA Toko</h1>
            </div>
            <div class="flex items-center gap-1">
                <a href="{{ route('toko.track-order') }}" class="flex h-10 w-10 items-center justify-center rounded-xl text-primary hover:bg-primary/5 transition-colors" aria-label="Riwayat Pesanan" title="Riwayat Pesanan">
                    <span class="material-symbols-outlined">receipt_long</span>
                </a>
                <a href="{{ route('cart.index') }}" class="relative flex h-10 w-10 items-center justify-center rounded-xl text-primary hover:bg-primary/5 transition-colors" aria-label="Keranjang">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <span data-cart-count class="absolute right-1 top-1 rounded-full bg-primary px-1.5 py-0.5 text-[9px] font-bold text-white">0</span>
                </a>
            </div>
        </header>

        <main>
            <nav class="flex items-center gap-1 overflow-x-auto px-5 py-3 text-xs font-semibold text-muted scrollbar-hide" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('toko.home') }}">Toko</a>
                <span>/</span>
                <a href="{{ route('toko.katalog', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                <span>/</span>
                <span class="shrink-0 text-primary">{{ $product->name }}</span>
            </nav>

            <section class="bg-white">
                <div class="relative aspect-square border-y border-line bg-surface">
                    <img id="mainProductImage" class="h-full w-full object-cover" alt="{{ $product->name }}" src="{{ $product->image_url }}">
                    <span class="absolute left-4 top-4 rounded bg-primary px-3 py-1 text-[10px] font-black uppercase tracking-wide text-white">{{ $product->category->name }}</span>
                </div>
            </section>

            <section class="px-5 py-5">
                <div class="mb-2 flex flex-wrap gap-2">
                    <span class="rounded-full bg-primary px-3 py-1 text-[10px] font-black uppercase tracking-wide text-white">Terlaris</span>
                    <span class="rounded-full bg-surface px-3 py-1 text-[10px] font-black uppercase tracking-wide text-muted">{{ $product->category->name }}</span>
                    <span class="rounded-full bg-moss/10 px-3 py-1 text-[10px] font-black uppercase tracking-wide text-moss">Siap Kirim</span>
                </div>
                <h2 class="text-3xl font-black leading-tight tracking-tight">{{ $product->name }}</h2>
                <p class="mt-2 text-sm leading-6 text-muted">{{ $product->short_description }}</p>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-line bg-white p-3">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Cocok untuk</p>
                        <p class="mt-1 text-sm font-black text-primary">{{ $product->animal_type }}</p>
                    </div>
                    <div class="rounded-2xl border border-line bg-white p-3">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Kategori</p>
                        <p class="mt-1 text-sm font-black text-primary">{{ $product->category->name }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-3">
                    <div class="flex items-center gap-1 text-sm font-bold text-amber">
                        <span class="material-symbols-outlined text-[19px]" style="font-variation-settings:'FILL' 1;">star</span>
                        {{ $product->rating }}
                    </div>
                    <span class="h-4 w-px bg-line"></span>
                    <a href="#reviews" class="text-sm font-semibold text-primary">{{ number_format($product->reviews_count, 0, ',', '.') }} review</a>
                    <span class="h-4 w-px bg-line"></span>
                    <span class="text-sm font-semibold text-muted">Terjual {{ number_format($product->sold_count, 0, ',', '.') }}+</span>
                </div>
                <div class="mt-5 rounded-2xl border border-line bg-white p-4 shadow-sm">
                    <div class="flex items-end gap-2">
                        <p class="text-3xl font-black text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @if ($product->compare_at_price)
                            <p class="pb-1 text-sm font-semibold text-muted line-through">Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-3">
                        <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">{{ $product->pack }}</span>
                        <div class="flex flex-col items-end gap-1">
                            @if($stock > 0 && $stock <= 5)
                                <span class="animate-pulse rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-black text-red-600 border border-red-200 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">local_fire_department</span>
                                    Sisa stok tinggal {{ $stock }}!
                                </span>
                            @endif
                            <span class="text-xs font-bold text-moss">Stok: {{ number_format($stock, 0, ',', '.') }} pcs</span>
                        </div>
                    </div>
                    <a href="{{ route('saditacare') }}" class="mt-4 flex h-11 items-center justify-center gap-2 rounded-xl border border-primary bg-white text-sm font-bold text-primary hover:bg-primary/5 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">forum</span>
                        Tanya dosis sebelum beli
                    </a>
                </div>
                <div class="mt-4 rounded-2xl border border-line bg-white p-4 shadow-sm">
                    <h3 class="text-base font-black text-primary">Informasi aman pemakaian</h3>
                    <div class="mt-3 grid gap-3 text-sm">
                        <div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary"><span class="material-symbols-outlined text-[19px]">assignment_turned_in</span></span><p class="leading-6"><strong>Nomor registrasi:</strong> {{ $product->registration_number }}</p></div>
                        <div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber/10 text-amber"><span class="material-symbols-outlined text-[19px]">warning</span></span><p class="leading-6"><strong>Perhatian:</strong> {{ $product->usage_instruction }}</p></div>
                        <div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-moss/10 text-moss"><span class="material-symbols-outlined text-[19px]">event</span></span><p class="leading-6"><strong>Withdrawal time:</strong> {{ $product->withdrawal_time }}</p></div>
                    </div>
                </div>
            </section>

            <section class="px-5">
                <div class="rounded-2xl border border-line bg-white p-4 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm font-black">Kemasan</h3>
                        <span class="text-xs font-semibold text-muted">{{ $warehouse?->name ?? 'SADITA' }}</span>
                    </div>
                    <div class="rounded-xl border-2 border-primary bg-primary/5 p-3 text-left">
                        <span class="block text-sm font-black">{{ $product->pack }}</span>
                        <span class="text-[11px] font-semibold text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-5 flex items-center justify-between">
                        <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Jumlah</p><p class="mt-1 text-xs text-muted">Maks. 20 pcs per transaksi</p></div>
                        <div class="flex h-11 items-center rounded-xl border border-line bg-white">
                            <button id="qtyMinus" class="flex h-11 w-11 items-center justify-center text-primary hover:bg-surface rounded-l-xl active:bg-line transition-all" aria-label="Kurangi jumlah"><span class="material-symbols-outlined">remove</span></button>
                            <span id="qtyValue" class="w-8 text-center text-sm font-black">1</span>
                            <button id="qtyPlus" class="flex h-11 w-11 items-center justify-center text-primary hover:bg-surface rounded-r-xl active:bg-line transition-all" aria-label="Tambah jumlah"><span class="material-symbols-outlined">add</span></button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-6 px-5">
                <div class="rounded-2xl border border-line bg-white p-4 shadow-sm">
                    <h3 class="text-base font-black">Deskripsi Produk</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">{{ $product->description }}</p>
                    <dl class="mt-4 divide-y divide-line text-sm">
                        <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Komposisi</dt><dd class="text-right font-bold">{{ $product->composition ?? '-' }}</dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Indikasi</dt><dd class="text-right font-bold">{{ $product->indication ?? '-' }}</dd></div>
                        <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Dosis</dt><dd class="text-right font-bold">{{ $product->dosage ?? '-' }}</dd></div>
                    </dl>
                </div>
            </section>

            <section id="reviews" class="mt-6 px-5">
                <div class="rounded-2xl border border-line bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div><h3 class="text-base font-black">Review Pembeli</h3><p class="mt-1 text-xs font-semibold text-muted">{{ $product->reviews->count() }} review contoh</p></div>
                        <p class="text-2xl font-black text-primary">{{ $product->rating }}</p>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse ($product->reviews as $review)
                            <article class="border-t border-line pt-4">
                                <div class="flex items-center justify-between"><p class="text-sm font-bold">{{ $review->customer_name }}</p><p class="text-xs font-semibold text-muted">{{ $review->location }}</p></div>
                                <p class="mt-2 text-sm leading-6 text-muted">{{ $review->comment }}</p>
                            </article>
                        @empty
                            <p class="border-t border-line pt-4 text-sm text-muted">Belum ada review untuk produk ini.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            @if ($relatedProducts->isNotEmpty())
                <section class="mt-8 px-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-black">Produk Terkait</h3>
                        <a href="{{ route('toko.katalog', ['category' => $product->category->slug]) }}" class="text-xs font-bold uppercase tracking-wide text-primary">Lihat Semua</a>
                    </div>
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach ($relatedProducts as $relatedProduct)
                            <a href="{{ route('toko.produk.show', $relatedProduct) }}" class="w-40 shrink-0 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                                <img class="h-32 w-full object-cover" alt="{{ $relatedProduct->name }}" src="{{ $relatedProduct->image_url }}" loading="lazy">
                                <div class="p-3"><h4 class="line-clamp-2 text-sm font-bold">{{ $relatedProduct->name }}</h4><p class="mt-2 text-sm font-black text-primary">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p></div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        <div class="fixed bottom-0 left-1/2 z-50 flex -translate-x-1/2 gap-3 border-t border-line bg-white/95 p-4 backdrop-blur shadow-[0_-4px_10px_rgba(0,0,0,0.03)] fixed-container-responsive">
            <a href="{{ route('saditacare') }}" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-line text-primary hover:bg-surface active:scale-95 transition-all" aria-label="Chat seller"><span class="material-symbols-outlined">forum</span></a>
            @if($stock > 0)
                <button id="addDetailCart" class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl border border-primary bg-white px-4 text-sm font-black text-primary hover:bg-primary/5 active:scale-95 transition-all"><span class="material-symbols-outlined text-[19px]">add_shopping_cart</span>Keranjang</button>
                <button id="buyNow" class="flex h-12 flex-1 items-center justify-center rounded-xl bg-primary px-4 text-sm font-black text-white shadow-lg shadow-primary/20 hover:shadow-xl hover:bg-primary/90 active:scale-95 transition-all">Beli Langsung</button>
            @else
                <button disabled class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl bg-slate-200 px-4 text-sm font-black text-slate-500 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[19px]">inventory_2</span>Habis di Lokasi Ini
                </button>
            @endif
        </div>
    </div>

    <script>
        let qty = 1;
        const qtyValue = document.getElementById("qtyValue");
        const detailProduct = {!! $detailProductPayload !!};

        document.getElementById("qtyMinus").addEventListener("click", () => {
            qty = Math.max(1, qty - 1);
            qtyValue.textContent = qty;
        });
        document.getElementById("qtyPlus").addEventListener("click", () => {
            qty = Math.min(20, qty + 1);
            qtyValue.textContent = qty;
        });
        document.getElementById("addDetailCart")?.addEventListener("click", () => saditaAddToCart(detailProduct, qty));
        document.getElementById("buyNow")?.addEventListener("click", async () => {
            const button = document.getElementById("buyNow");
            const originalLabel = button.innerHTML;
            button.disabled = true;
            button.classList.add('opacity-70');
            button.textContent = 'Memproses...';

            try {
                await saditaAddToCart(detailProduct, qty);
                window.location.href = @json(route('checkout'));
            } catch (error) {
                button.disabled = false;
                button.classList.remove('opacity-70');
                button.innerHTML = originalLabel;
            }
        });
    </script>
</x-layouts.toko>
