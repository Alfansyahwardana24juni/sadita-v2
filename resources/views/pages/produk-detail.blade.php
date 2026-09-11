<x-layouts.app :title="$product->name . ' - SADITA'">
    @php
        $units = $product->units->where('is_active', true)
            ->sortBy([['sort_order', 'asc'], ['price', 'asc']])
            ->values();
        if ($units->isEmpty()) {
            $units = $product->units->sortBy('sort_order')->values();
        }
        $range = $product->priceRange();
        $hasRange = $range['min'] !== $range['max'];
    @endphp

    <section class="border-b border-line bg-white px-5 py-5">
        <a href="{{ route('produk.category', $category) }}" class="mb-3 inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wide text-primary">
            <span class="material-symbols-outlined text-[17px]">arrow_back</span>
            Kembali ke {{ $category->name }}
        </a>
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Detail Produk</p>
        <h1 class="mt-1 text-2xl font-black text-primary">{{ $product->name }}</h1>
    </section>

    <section class="bg-white">
        <div class="relative aspect-square border-y border-line bg-surface">
            <img class="h-full w-full object-cover" alt="{{ $product->name }}" src="{{ $product->image_url }}">
            <span class="absolute left-4 top-4 rounded bg-primary px-3 py-1 text-[10px] font-black uppercase tracking-wide text-white">{{ $category->name }}</span>
        </div>
    </section>

    <section class="px-5 py-5">
        <div class="mb-2 flex flex-wrap gap-2">
            <span class="rounded-full bg-primary px-3 py-1 text-[10px] font-black uppercase tracking-wide text-white">Produk SADITA</span>
            <span class="rounded-full bg-surface px-3 py-1 text-[10px] font-black uppercase tracking-wide text-muted">{{ $category->name }}</span>
            <span class="rounded-full bg-moss/10 px-3 py-1 text-[10px] font-black uppercase tracking-wide text-moss">Siap Kirim</span>
            @if($product->totalStock() > 0)
                <span class="rounded-full bg-amber/10 px-3 py-1 text-[10px] font-black uppercase tracking-wide text-amber">{{ number_format($product->totalStock(), 0, ',', '.') }} Tersedia</span>
            @endif
        </div>
        <p class="text-sm leading-6 text-muted">{{ $product->short_description }}</p>

        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="rounded-2xl border border-line bg-white p-3">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Cocok untuk</p>
                <p class="mt-1 text-sm font-black text-primary">{{ $product->animal_type ?: '-' }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-white p-3">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Indikasi</p>
                <p class="mt-1 text-sm font-black text-primary">{{ $product->indication ?: '-' }}</p>
            </div>
        </div>

        {{-- Pilihan unit / kemasan (read-only) --}}
        <div class="mt-5 rounded-2xl border border-line bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-black text-primary">Pilihan Unit / Kemasan</h2>
                <span class="text-xs font-semibold text-muted">
                    @if($hasRange)
                        Rp {{ number_format($range['min'], 0, ',', '.') }} – {{ number_format($range['max'], 0, ',', '.') }}
                    @else
                        Rp {{ number_format($range['min'], 0, ',', '.') }}
                    @endif
                </span>
            </div>
            <ul class="mt-3 divide-y divide-line">
                @forelse($units as $unit)
                    <li class="flex items-center justify-between gap-3 py-3">
                        <span class="text-sm font-bold text-ink">{{ $unit->name }}</span>
                        <span class="text-sm font-black text-primary">Rp {{ number_format($unit->price, 0, ',', '.') }}</span>
                    </li>
                @empty
                    <li class="py-3 text-sm text-muted">Belum ada unit untuk produk ini.</li>
                @endforelse
            </ul>
            <p class="mt-3 text-xs leading-5 text-muted">Harga & ketersediaan stok final mengikuti gudang terpilih di halaman Toko.</p>

            <a href="{{ route('toko.produk.show', $product) }}" class="mt-4 flex h-12 items-center justify-center gap-2 rounded-xl bg-primary text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[19px]">shopping_cart</span>
                Ingin beli produk ini? Beli di Toko
            </a>
            
            <x-brochure-modal :product="$product" :buy-url="route('toko.produk.show', $product)" buy-label="Beli Produk"
                trigger-class="mt-3 flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-primary bg-white text-sm font-bold text-primary hover:bg-primary/5 active:scale-95 transition-all" />

            <a href="{{ route('saditacare') }}" class="mt-3 flex h-11 items-center justify-center gap-2 rounded-xl border border-line bg-white text-sm font-bold text-muted hover:bg-surface active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[18px]">forum</span>
                Konsultasi Produk Dulu
            </a>
        </div>
    </section>

    <section class="mt-6 px-5" x-data="{ activeTab: 'desc' }">
        <div class="grid grid-cols-2 overflow-hidden rounded-2xl border border-line bg-white p-1 text-xs font-bold">
            <button type="button" @click="activeTab = 'desc'" class="rounded-xl px-3 py-3" :class="activeTab === 'desc' ? 'bg-primary text-white' : 'text-muted'">Deskripsi</button>
            <button type="button" @click="activeTab = 'spec'" class="rounded-xl px-3 py-3" :class="activeTab === 'spec' ? 'bg-primary text-white' : 'text-muted'">Spesifikasi</button>
        </div>

        <div class="mt-4 rounded-2xl border border-line bg-white p-4 shadow-sm">
            <div x-show="activeTab === 'desc'">
                <h2 class="text-base font-black">Deskripsi Produk</h2>
                <p class="mt-3 text-sm leading-7 text-muted">{{ $product->description ?: $product->short_description ?: '-' }}</p>
                <div class="mt-4 grid gap-3">
                    @if($product->composition)
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-amber">
                            <span class="material-symbols-outlined text-[20px]">science</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Komposisi:</strong> {{ $product->composition }}</p>
                    </div>
                    @endif
                    @if($product->pharmacology)
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-indigo-500">
                            <span class="material-symbols-outlined text-[20px]">psychiatry</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Cara Kerja Obat:</strong> {{ $product->pharmacology }}</p>
                    </div>
                    @endif
                    @if($product->indication)
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-primary">
                            <span class="material-symbols-outlined text-[20px]">health_and_safety</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Indikasi:</strong> {{ $product->indication }}</p>
                    </div>
                    @endif
                    @if($product->usage_instruction)
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-moss">
                            <span class="material-symbols-outlined text-[20px]">medical_services</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Petunjuk penggunaan:</strong> {{ $product->usage_instruction }}</p>
                    </div>
                    @endif
                    @if($product->dosage)
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-amber">
                            <span class="material-symbols-outlined text-[20px]">medication</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Dosis:</strong> {{ $product->dosage }}</p>
                    </div>
                    @endif
                    @if($product->withdrawal_time)
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-primary">
                            <span class="material-symbols-outlined text-[20px]">event</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Withdrawal time:</strong> {{ $product->withdrawal_time }}</p>
                    </div>
                    @endif
                    @if($product->storage_instruction)
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-slate-500">
                            <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Penyimpanan:</strong> {{ $product->storage_instruction }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div x-show="activeTab === 'spec'" style="display: none;">
                <h2 class="text-base font-black">Spesifikasi Teknis</h2>
                <dl class="mt-4 divide-y divide-line text-sm">
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Cocok Untuk</dt><dd class="text-right font-bold">{{ $product->animal_type ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Komposisi</dt><dd class="text-right font-bold">{{ $product->composition ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Indikasi</dt><dd class="text-right font-bold">{{ $product->indication ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Dosis</dt><dd class="text-right font-bold">{{ $product->dosage ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Withdrawal</dt><dd class="text-right font-bold">{{ $product->withdrawal_time ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Kemasan</dt><dd class="text-right font-bold">{{ $units->pluck('name')->implode(', ') ?: '-' }}</dd></div>
                    
                    @if(is_array($product->extra_specifications))
                        @foreach($product->extra_specifications as $key => $val)
                            <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">{{ $key }}</dt><dd class="text-right font-bold">{{ $val }}</dd></div>
                        @endforeach
                    @endif
                </dl>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="mt-8 px-5 pb-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-black">Produk Terkait</h3>
                <a href="{{ route('produk.category', $category) }}" class="text-xs font-bold uppercase tracking-wide text-primary">Lihat Semua</a>
            </div>
            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                @foreach ($relatedProducts as $relatedProduct)
                    <a href="{{ route('produk.detail', ['category' => $category, 'product' => $relatedProduct]) }}" class="w-40 shrink-0 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                        <div class="h-32 w-full overflow-hidden bg-surface">
                            <img class="h-32 w-full object-cover" alt="{{ $relatedProduct->name }}" src="{{ $relatedProduct->image_url }}">
                        </div>
                        <div class="p-3">
                            <h4 class="line-clamp-2 text-sm font-bold">{{ $relatedProduct->name }}</h4>
                            <p class="mt-2 text-sm font-black text-primary">Mulai Rp {{ number_format($relatedProduct->price_from, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.app>
