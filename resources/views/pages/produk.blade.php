<x-layouts.app title="Produk - SADITA">
    <section class="border-b border-line bg-white px-5 py-6">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Kategori</p>
        <h1 class="mt-1 text-2xl font-black text-primary">Produk Kesehatan Hewan</h1>
        <p class="mt-3 text-sm leading-6 text-muted">Pilih kategori produk sesuai kebutuhan ternak Anda. Kami
            menyediakan produk berkualitas untuk mendukung kesehatan dan performa ternak.</p>
            
        <form method="GET" action="{{ route('produk') }}" class="relative mt-5">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-muted">search</span>
            <input
                name="q"
                value="{{ $search ?? '' }}"
                class="w-full rounded-xl border border-line bg-white py-3 pl-12 pr-20 shadow-sm outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"
                placeholder="Cari produk..." type="text" />
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg bg-primary px-3 py-1.5 text-xs font-bold text-white hover:bg-primary/90">
                Cari
            </button>
        </form>

    </section>

    @if(!empty($search) && isset($products))
    <section class="px-5 py-8">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-black text-primary">Hasil Pencarian: "{{ $search }}"</h2>
            <a href="{{ route('produk') }}" class="text-xs font-bold text-moss hover:text-primary">Reset</a>
        </div>
        
        @if($products->isEmpty())
            <div class="rounded-2xl border border-dashed border-line bg-white p-6 text-center">
                <span class="material-symbols-outlined text-4xl text-muted">search_off</span>
                <h2 class="mt-3 text-lg font-black text-primary">Produk tidak ditemukan</h2>
                <p class="mt-2 text-sm text-muted">Coba gunakan kata kunci lain untuk mencari produk.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4">
                @foreach($products as $product)
                    <a href="{{ route('produk.detail', ['category' => $product->category, 'product' => $product]) }}" class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                        <div class="aspect-square bg-surface">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/400x400/F8FAFC/94A3B8?text=?';" loading="lazy">
                        </div>
                        <div class="p-3">
                            <h3 class="line-clamp-2 text-sm font-bold leading-5 text-ink">{{ $product->name }}</h3>
                            <p class="mt-1 text-xs font-semibold text-muted">{{ $product->units->count() }} pilihan unit</p>
                            <p class="mt-2 text-sm font-black text-primary">Mulai dari Rp {{ number_format($product->price_from, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            
            @if($products->hasPages())
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </section>
    @else
    <section class="px-5 py-8">
        <h2 class="mb-5 text-lg font-black text-primary">Kategori Produk</h2>
        <div class="grid grid-cols-2 gap-4">
            @foreach($categories as $category)
                @php
                    $slug = strtolower((string) $category->slug);
                    $icon = match (true) {
                        str_contains($slug, 'antibiotik') => 'medication',
                        str_contains($slug, 'vitamin') => 'nutrition',
                        str_contains($slug, 'coccidia') => 'vaccines',
                        str_contains($slug, 'parasit') => 'bug_report',
                        str_contains($slug, 'disinfektan') => 'cleaning_services',
                        str_contains($slug, 'premix') => 'science',
                        str_contains($slug, 'akuatik') => 'water_drop',
                        str_contains($slug, 'pmk') => 'health_and_safety',
                        default => 'inventory_2',
                    };
                @endphp
                <a href="{{ route('produk.category', $category) }}" class="group flex flex-col rounded-2xl border border-line bg-white p-4 shadow-sm transition-transform active:scale-[0.98]">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}" class="h-12 w-12 object-contain" alt="{{ $category->name }}" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/100x100/F1F5F9/94A3B8?text=?';" />
                        @else
                            <span class="material-symbols-outlined text-[30px] text-primary">{{ $icon }}</span>
                        @endif
                    </div>
                    <h3 class="mt-3 text-sm font-black text-primary">{{ $category->name }}</h3>
                    @if($category->description)
                        <p class="mt-1 line-clamp-2 text-xs leading-5 text-muted">{{ $category->description }}</p>
                    @endif
                    <div class="mt-3 flex items-center gap-1 text-[11px] font-bold text-moss">
                        <span class="material-symbols-outlined text-[15px]">check_circle</span>
                        {{ number_format($category->active_products_count ?? 0, 0, ',', '.') }} produk
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    <section class="border-t border-line bg-white px-5 py-8">
        <div class="rounded-2xl border border-line bg-surface p-4">
            <div class="flex items-start gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
                    <span class="material-symbols-outlined">support_agent</span>
                </span>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base font-black text-primary">Butuh Bantuan Memilih?</h3>
                    <p class="mt-1 text-sm leading-6 text-muted">Konsultasikan kebutuhan ternak Anda dengan tim
                        SADITA untuk rekomendasi produk yang tepat.</p>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <a href="{{ route('saditacare') }}"
                            class="flex h-11 items-center justify-center gap-2 rounded-xl border border-primary bg-white px-3 text-sm font-bold text-primary">
                            <span class="material-symbols-outlined text-[18px]">forum</span>
                            SaditaCare
                        </a>
                        <a href="{{ route('chat') }}"
                            class="flex h-11 items-center justify-center gap-2 rounded-xl bg-primary px-3 text-sm font-bold text-white">
                            <span class="material-symbols-outlined text-[18px]">chat_bubble</span>
                            Chat Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

