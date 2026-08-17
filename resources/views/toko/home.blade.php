<x-layouts.toko title="SADITA Toko - Pilih Gudang">
    <div class="pb-24">
        <header class="sticky top-0 z-50 flex h-16 items-center justify-between border-b border-line bg-white/95 px-5 backdrop-blur">
            <a href="{{ route('home') }}" class="flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Kembali"><span class="material-symbols-outlined">arrow_back</span></a>
            <div class="text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-muted">Toko SADITA</p>
                <h1 class="text-base font-black text-primary">Pilih Gudang</h1>
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
            <section class="relative min-h-[250px] overflow-hidden">
                <img class="absolute inset-0 h-full w-full object-cover" alt="Gudang distribusi produk peternakan" src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80">
                <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-primary/90"></div>
                <div class="relative flex min-h-[250px] flex-col justify-end px-6 pb-7 text-white">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/80">Stok dari cabang terdekat</p>
                    <h2 class="mt-2 text-3xl font-black leading-tight">{{ $displaySetting->store_home_title ?: 'Belanja dari gudang yang melayani area anda' }}</h2>
                    <p class="mt-2 text-sm leading-6 text-white/90">{{ $displaySetting->store_home_description ?: 'Pilih cabang terdekat agar harga, stok, dan estimasi pengiriman lebih akurat.' }}</p>
                </div>
            </section>

            <section class="px-5 py-6">
                <div class="mb-4 rounded-2xl border border-line bg-white p-4">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-surface text-primary"><span class="material-symbols-outlined">near_me</span></span>
                        <div>
                            <h3 class="text-sm font-bold">{{ $displaySetting->store_home_info_title ?: 'Pilih gudang sebelum katalog' }}</h3>
                            <p class="mt-1 text-xs leading-5 text-muted">{{ $displaySetting->store_home_info_description ?: 'Harga, ketersediaan stok, dan estimasi pengiriman akan mengikuti gudang yang dipilih.' }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4">
                    @foreach ($warehouses as $warehouse)
                        <form method="POST" action="{{ route('toko.select-warehouse', $warehouse) }}">
                            @csrf
                            <button class="w-full overflow-hidden rounded-2xl border border-line bg-white text-left shadow-sm active:scale-[0.99]">
                                <div class="h-36 w-full overflow-hidden">
                                    <img class="h-36 w-full object-cover bg-slate-100" alt="{{ $warehouse->name }}" src="{{ $warehouse->image_url ?: 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=600&q=80' }}" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=600&q=80';">
                                </div>
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-moss">{{ $warehouse->code }}</p>
                                            <h3 class="mt-1 text-lg font-black text-primary">{{ $warehouse->name }}</h3>
                                        </div>
                                        <span class="rounded-full bg-moss/10 px-3 py-1 text-xs font-bold text-moss">{{ $warehouse->stocks_sum_stock ?? 'Stok' }}</span>
                                    </div>
                                    <p class="mt-3 text-sm leading-6 text-muted">{{ $warehouse->address }}. Melayani {{ $warehouse->service_area }}.</p>
                                    <div class="mt-4 flex items-center justify-between border-t border-line pt-4">
                                        <span class="text-xs font-semibold text-muted">Estimasi kirim {{ $warehouse->delivery_estimate }}</span>
                                        <span class="flex items-center gap-1 text-sm font-bold text-primary">Pilih <span class="material-symbols-outlined text-[18px]">arrow_forward</span></span>
                                    </div>
                                </div>
                            </button>
                        </form>
                    @endforeach
                </div>
            </section>
        </main>
    </div>
</x-layouts.toko>
