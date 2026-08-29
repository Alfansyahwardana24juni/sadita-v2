<x-layouts.toko title="SADITA Toko - Katalog Produk">
    <div class="pb-24">
        <header class="sticky top-[52px] z-40 border-b border-line bg-white/95 backdrop-blur">
            <div class="flex h-16 items-center justify-between px-5">
                <a href="{{ route('toko.home') }}" class="flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Kembali ke pilih gudang"><span class="material-symbols-outlined">arrow_back</span></a>
                <div class="text-center">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-muted">{{ $warehouse?->name ?? 'SADITA Toko' }}</p>
                    <h1 class="text-base font-black text-primary">{{ $displaySetting->store_katalog_title ?: 'Katalog Produk' }}</h1>
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('toko.orders') }}" class="flex h-10 w-10 items-center justify-center rounded-xl text-primary hover:bg-primary/5 transition-colors" aria-label="Riwayat Pesanan" title="Riwayat Pesanan">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="relative flex h-10 w-10 items-center justify-center rounded-xl text-primary hover:bg-primary/5 transition-colors" aria-label="Keranjang">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        <span data-cart-count class="absolute right-1 top-1 rounded-full bg-primary px-1.5 py-0.5 text-[9px] font-bold text-white">0</span>
                    </a>
                </div>
            </div>
        </header>

        <main class="px-5 pt-5">
            <section class="rounded-2xl border border-line bg-white p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-surface text-primary">
                        <span class="material-symbols-outlined">store</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-muted">{{ $displaySetting->store_katalog_subtitle ?: 'Cabang Terpilih' }}</p>
                        <h2 class="truncate text-sm font-bold">{{ $warehouse?->name ?? 'Belum dipilih' }}</h2>
                    </div>
                    <a href="{{ route('toko.home') }}" class="text-xs font-bold uppercase tracking-wide text-primary">Ubah</a>
                </div>
            </section>

            <form id="katalog-filter-form" method="GET" action="{{ route('toko.katalog') }}" class="mt-4 space-y-4">
                <!-- Search Experience -->
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-muted">search</span>
                    <input name="q" value="{{ $query }}" class="h-12 w-full rounded-2xl border-none bg-surface pl-12 pr-4 text-sm font-medium focus:ring-2 focus:ring-primary/20" placeholder="Cari obat, vitamin, atau gejala..." type="search">
                    @if($query)
                        <a href="{{ route('toko.katalog') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-ink">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </a>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('saditacare') }}" class="flex flex-col items-center justify-center gap-1 rounded-xl border border-line bg-white py-2.5 shadow-sm active:scale-95 transition-transform text-center">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-primary"><span class="material-symbols-outlined text-[16px]">forum</span></span>
                        <span class="block text-[11px] font-bold text-primary">Tanya Dosis AI</span>
                    </a>
                    <a href="{{ route('toko.home') }}" class="flex flex-col items-center justify-center gap-1 rounded-xl border border-line bg-white py-2.5 shadow-sm active:scale-95 transition-transform text-center">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-moss/10 text-moss"><span class="material-symbols-outlined text-[16px]">warehouse</span></span>
                        <span class="block text-[11px] font-bold text-primary">Ganti Gudang</span>
                    </a>
                </div>
                <!-- Sticky Category Filter -->
                <div class="sticky top-16 z-40 -mx-5 bg-white/95 px-5 py-2 backdrop-blur">
                    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide" aria-label="Filter kategori">
                        <a data-katalog-nav href="{{ route('toko.katalog', ['q' => $query, 'sort' => $sort]) }}" class="shrink-0 flex items-center gap-2 rounded-full pr-4 pl-1.5 py-1.5 text-xs font-bold transition-all {{ $activeCategory === '' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface text-muted hover:bg-line/50' }}">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-primary shadow-sm">
                                <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">grid_view</span>
                            </div>
                            Semua
                        </a>
                        @foreach ($categories as $category)
                            <a data-katalog-nav href="{{ route('toko.katalog', ['category' => $category->slug, 'q' => $query, 'sort' => $sort]) }}" class="shrink-0 flex items-center gap-2 rounded-full pr-4 pl-1.5 py-1.5 text-xs font-bold transition-all {{ $activeCategory === $category->slug ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-surface text-muted hover:bg-line/50' }}">
                                @if($category->image)
                                    <img src="{{ str_starts_with($category->image, 'http') ? $category->image : Storage::url($category->image) }}" class="h-6 w-6 rounded-full object-cover bg-transparent shadow-sm" alt="{{ $category->name }}" onerror="this.onerror=null;this.src='https://placehold.co/100x100/F1F5F9/94A3B8?text=?';" />
                                @else
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-primary shadow-sm">
                                        <span class="material-symbols-outlined text-[14px]">category</span>
                                    </div>
                                @endif
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-semibold text-muted">{{ $products->total() }} produk tersedia</p>
                    <label class="flex items-center gap-1 rounded-xl border border-line bg-white px-3 py-2 text-xs font-bold text-primary">
                        <span class="material-symbols-outlined text-[18px]">sort</span>
                        <select name="sort" class="border-0 bg-transparent p-0 text-xs font-bold text-primary focus:ring-0" onchange="this.form.submit()">
                            <option value="popular" @selected($sort === 'popular')>Terpopuler</option>
                            <option value="price-low" @selected($sort === 'price-low')>Harga rendah</option>
                            <option value="price-high" @selected($sort === 'price-high')>Harga tinggi</option>
                            <option value="stock-high" @selected($sort === 'stock-high')>Stok terbanyak</option>
                        </select>
                    </label>
                </div>
            </form>

            @if ($products->isEmpty())
                <section class="mt-5 rounded-2xl border border-dashed border-line bg-white p-6 text-center">
                    <span class="material-symbols-outlined text-4xl text-muted">search_off</span>
                    <h2 class="mt-3 text-lg font-black text-primary">Produk tidak ditemukan</h2>
                    <p class="mt-2 text-sm leading-6 text-muted">Coba kata kunci lain atau pilih kategori Semua.</p>
                </section>
            @else
                <section class="mt-5 grid grid-cols-2 gap-4">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </section>
            @endif
            @if ($products->hasPages())
                <section class="mt-5">
                    {{ $products->links() }}
                </section>
            @endif
            <section id="katalog-skeleton" class="mt-5 hidden grid-cols-2 gap-4">
                @for ($i = 0; $i < 6; $i++)
                    <div class="overflow-hidden rounded-2xl border border-line bg-white p-3 shadow-sm">
                        <div class="sadita-skeleton aspect-square w-full rounded-xl"></div>
                        <div class="mt-3 sadita-skeleton h-4 w-4/5 rounded"></div>
                        <div class="mt-2 sadita-skeleton h-3 w-2/3 rounded"></div>
                        <div class="mt-3 sadita-skeleton h-9 w-full rounded-xl"></div>
                    </div>
                @endfor
            </section>
        </main>
    </div>
    <script>
        (() => {
            const form = document.getElementById('katalog-filter-form');
            const resultSection = document.querySelector('main > section.mt-5.grid');
            const emptySection = document.querySelector('main > section.mt-5.rounded-2xl');
            const skeleton = document.getElementById('katalog-skeleton');
            const navLinks = document.querySelectorAll('[data-katalog-nav]');

            function showSkeleton() {
                if (!skeleton) return;
                skeleton.classList.remove('hidden');
                skeleton.classList.add('grid');
                if (resultSection) resultSection.classList.add('hidden');
                if (emptySection) emptySection.classList.add('hidden');
            }

            if (form) {
                form.addEventListener('submit', showSkeleton);
            }
            navLinks.forEach(link => link.addEventListener('click', showSkeleton));
        })();
    </script>
</x-layouts.toko>
