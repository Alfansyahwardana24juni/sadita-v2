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

        $range = ['min' => (int) $units->min('price'), 'max' => (int) $units->max('price')];
    @endphp

    <div class="pb-40">
        <header class="sticky top-0 z-50 flex h-16 items-center justify-between border-b border-line bg-white/95 px-5 backdrop-blur">
            <button onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('toko.katalog') }}'" class="flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Kembali ke katalog"><span class="material-symbols-outlined">arrow_back</span></button>
            <div class="text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-muted">Detail Produk</p>
                <h1 class="text-base font-black text-primary">SADITA Toko</h1>
            </div>
            <a href="{{ route('checkout') }}" class="relative flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Keranjang">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span data-cart-count class="absolute right-1 top-1 rounded-full bg-primary px-1.5 py-0.5 text-[9px] font-bold text-white">0</span>
            </a>
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
                    <span class="text-sm font-semibold text-muted">Terjual {{ number_format($product->sold_count, 0, ',', '.') }}+</span>
                    <span class="h-4 w-px bg-line"></span>
                    <span class="text-sm font-semibold text-muted">{{ $units->count() }} pilihan unit</span>
                </div>
                <div class="mt-5 rounded-2xl border border-line bg-white p-4 shadow-sm">
                    <div class="flex items-end gap-2">
                        <p class="text-3xl font-black text-primary" id="selectedPrice">Rp {{ number_format($defaultUnit?->price ?? 0, 0, ',', '.') }}</p>
                        <p class="pb-1 text-sm font-semibold text-muted line-through" id="selectedCompare" @if(!$defaultUnit?->compare_at_price) style="display:none" @endif>
                            Rp {{ number_format($defaultUnit?->compare_at_price ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-3">
                        <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary" id="selectedUnitLabel">{{ $defaultUnit?->name }}</span>
                        <span class="text-xs font-bold text-moss" id="selectedStockLabel">Stok: {{ number_format($stock, 0, ',', '.') }} pcs</span>
                    </div>
                    <div class="mt-4 grid gap-2 @if($product->brochure_url) grid-cols-2 @endif">
                        <a href="{{ route('saditacare') }}" class="flex h-11 items-center justify-center gap-2 rounded-xl border border-primary bg-white text-sm font-bold text-primary hover:bg-primary/5 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[18px]">forum</span>
                            Tanya dosis
                        </a>
                        <x-brochure-modal :product="$product" :buy-url="null" trigger-class="flex h-11 items-center justify-center gap-2 rounded-xl border border-primary bg-white text-sm font-bold text-primary hover:bg-primary/5 active:scale-95 transition-all" />
                    </div>
                </div>
                <div class="mt-4 rounded-2xl border border-line bg-white p-4 shadow-sm">
                    <h3 class="text-base font-black text-primary">Informasi aman pemakaian</h3>
                    <div class="mt-3 grid gap-3 text-sm">
                        <div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary"><span class="material-symbols-outlined text-[19px]">medication</span></span><p class="leading-6"><strong>Dosis &amp; aturan pakai:</strong> {{ $product->dosage ?: 'Ikuti label produk / konsultasikan ke dokter hewan.' }}</p></div>
                        <div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber/10 text-amber"><span class="material-symbols-outlined text-[19px]">warning</span></span><p class="leading-6"><strong>Perhatian:</strong> {{ $product->usage_instruction }}</p></div>
                        <div class="flex gap-3"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-moss/10 text-moss"><span class="material-symbols-outlined text-[19px]">event</span></span><p class="leading-6"><strong>Withdrawal time:</strong> {{ $product->withdrawal_time }}</p></div>
                    </div>
                </div>
            </section>

            <section class="px-5">
                <div class="rounded-2xl border border-line bg-white p-4 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-sm font-black">Pilih Unit / Kemasan</h3>
                        <span class="text-xs font-semibold text-muted">{{ $warehouse?->name ?? 'SADITA' }}</span>
                    </div>
                    <div class="space-y-2" id="unitOptions">
                        @foreach ($units as $unit)
                            @php($unitAvailable = $availableFor($unit))
                            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border-2 p-3 transition-all {{ $unit->id === $defaultUnit?->id ? 'border-primary bg-primary/5' : 'border-line bg-white' }} {{ $unitAvailable <= 0 ? 'opacity-50' : '' }}">
                                <span class="flex items-center gap-3">
                                    <input type="radio" name="unit" value="{{ $unit->slug }}"
                                        class="unit-radio h-4 w-4 accent-[#800000]"
                                        data-price="{{ $unit->price }}"
                                        data-compare="{{ $unit->compare_at_price }}"
                                        data-stock="{{ $unitAvailable }}"
                                        data-name="{{ $unit->name }}"
                                        @checked($unit->id === $defaultUnit?->id)
                                        @disabled($unitAvailable <= 0)>
                                    <span>
                                        <span class="block text-sm font-black">{{ $unit->name }}</span>
                                        <span class="text-[11px] font-semibold text-muted">
                                            @if($unitAvailable > 0) Stok {{ number_format($unitAvailable, 0, ',', '.') }} pcs @else Stok habis @endif
                                        </span>
                                    </span>
                                </span>
                                <span class="text-sm font-black text-primary">Rp {{ number_format($unit->price, 0, ',', '.') }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-5 flex items-center justify-between">
                        <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Jumlah</p><p class="mt-1 text-xs text-muted">Maks. 20 pcs per transaksi</p></div>
                        <div class="flex h-11 items-center rounded-xl border border-line bg-white">
                            <button id="qtyMinus" class="flex h-11 w-11 items-center justify-center text-primary hover:bg-surface rounded-l-xl active:bg-line transition-all" aria-label="Kurangi jumlah"><span class="material-symbols-outlined">remove</span></button>
                            <span id="qtyValue" class="w-8 text-center text-sm font-black">1</span>
                            <button id="qtyPlus" class="flex h-11 w-11 items-center justify-center text-primary hover:bg-surface rounded-r-xl active:bg-line transition-all" aria-label="Tambah jumlah"><span class="material-symbols-outlined">add</span></button>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <button type="button" data-add-cart class="flex h-12 items-center justify-center gap-2 rounded-xl border border-primary bg-white px-4 text-sm font-black text-primary hover:bg-primary/5 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[19px]">add_shopping_cart</span>
                            <span data-add-label>Keranjang</span>
                        </button>
                        <button type="button" data-buy-now class="flex h-12 items-center justify-center rounded-xl bg-primary px-4 text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all">
                            Beli Langsung
                        </button>
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
                                <div class="p-3"><h4 class="line-clamp-2 text-sm font-bold">{{ $relatedProduct->name }}</h4><p class="mt-2 text-sm font-black text-primary">Mulai Rp {{ number_format($relatedProduct->price_from, 0, ',', '.') }}</p></div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        <div class="fixed bottom-[70px] sm:bottom-[92px] left-1/2 z-40 flex -translate-x-1/2 gap-3 rounded-2xl border border-line bg-white/95 p-3 backdrop-blur shadow-[0_8px_30px_rgba(0,0,0,0.12)] fixed-container-responsive">
            <a href="{{ route('saditacare') }}" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-line text-primary hover:bg-surface active:scale-95 transition-all" aria-label="Chat seller"><span class="material-symbols-outlined">forum</span></a>
            <button type="button" data-add-cart class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl border border-primary bg-white px-4 text-sm font-black text-primary hover:bg-primary/5 active:scale-95 transition-all"><span class="material-symbols-outlined text-[19px]">add_shopping_cart</span><span data-add-label>Keranjang</span></button>
            <button type="button" data-buy-now class="flex h-12 flex-1 items-center justify-center rounded-xl bg-primary px-4 text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all">Beli Langsung</button>
        </div>
    </div>

    <script>
        (() => {
            const units = {!! $unitsPayload->toJson() !!};
            let qty = 1;
            let selected = units.find(u => u.stock > 0) || units[0] || null;
            const checkedRadio = document.querySelector('.unit-radio:checked');
            if (checkedRadio) {
                selected = units.find(u => u.id === checkedRadio.value) || selected;
            }

            const qtyValue = document.getElementById('qtyValue');
            const selectedPrice = document.getElementById('selectedPrice');
            const selectedCompare = document.getElementById('selectedCompare');
            const selectedUnitLabel = document.getElementById('selectedUnitLabel');
            const selectedStockLabel = document.getElementById('selectedStockLabel');
            const addBtns = Array.from(document.querySelectorAll('[data-add-cart]'));
            const buyBtns = Array.from(document.querySelectorAll('[data-buy-now]'));

            const fmt = (n) => 'Rp ' + Number(n || 0).toLocaleString('id-ID');
            const maxQty = () => Math.min(20, selected ? selected.stock : 0);

            function render() {
                const soldOut = !selected || selected.stock <= 0;
                if (selected) {
                    selectedPrice.textContent = fmt(selected.price);
                    selectedUnitLabel.textContent = selected.name;
                    selectedStockLabel.textContent = soldOut ? 'Stok habis' : ('Stok: ' + Number(selected.stock).toLocaleString('id-ID') + ' pcs');
                    if (selected.compare_at_price) {
                        selectedCompare.textContent = fmt(selected.compare_at_price);
                        selectedCompare.style.display = '';
                    } else {
                        selectedCompare.style.display = 'none';
                    }
                }
                if (qty > maxQty()) qty = Math.max(1, maxQty());
                qtyValue.textContent = qty;

                [...addBtns, ...buyBtns].forEach(btn => {
                    btn.disabled = soldOut;
                    btn.classList.toggle('opacity-50', soldOut);
                    btn.classList.toggle('cursor-not-allowed', soldOut);
                });
                document.querySelectorAll('[data-add-label]').forEach(el => {
                    el.textContent = soldOut ? 'Stok Habis' : 'Keranjang';
                });
            }

            document.querySelectorAll('.unit-radio').forEach(radio => {
                radio.addEventListener('change', () => {
                    selected = units.find(u => u.id === radio.value) || selected;
                    qty = 1;
                    document.querySelectorAll('#unitOptions label').forEach(l => {
                        const r = l.querySelector('.unit-radio');
                        l.classList.toggle('border-primary', r.checked);
                        l.classList.toggle('bg-primary/5', r.checked);
                        l.classList.toggle('border-line', !r.checked);
                        l.classList.toggle('bg-white', !r.checked);
                    });
                    render();
                });
            });

            document.getElementById('qtyMinus').addEventListener('click', () => { qty = Math.max(1, qty - 1); render(); });
            document.getElementById('qtyPlus').addEventListener('click', () => { qty = Math.min(maxQty(), qty + 1); render(); });

            addBtns.forEach(btn => btn.addEventListener('click', () => {
                if (!selected || selected.stock <= 0) return;
                saditaAddToCart(selected, qty);
            }));

            buyBtns.forEach(btn => btn.addEventListener('click', async () => {
                if (!selected || selected.stock <= 0) return;
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

            render();
        })();
    </script>
</x-layouts.toko>
