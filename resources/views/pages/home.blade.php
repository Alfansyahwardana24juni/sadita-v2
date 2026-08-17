<x-layouts.app>
    <!-- Header HANYA untuk Beranda -->
    <x-header />

    <!-- Hero Section -->
    <section class="relative min-h-[440px] overflow-hidden">
            <img class="absolute inset-0 h-full w-full object-cover" alt="Peternakan unggas sehat" src="https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=1200&q=80">
            <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-primary/45 to-primary/95"></div>
            <div class="relative flex min-h-[440px] flex-col justify-end px-6 pb-8 text-white">
                <p class="mb-3 w-fit rounded-full border border-white/25 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em]">Sadita Animal Health</p>
                <h1 class="max-w-[360px] text-4xl font-black leading-[1.04] tracking-tight">{{ __('Solusi sehat ternak anda') }}</h1>
                <p class="mt-4 max-w-[330px] text-base leading-7 text-white/90">Cari produk, konsultasi gejala ternak, dan pilih gudang terdekat sebelum pesan.</p>
                <div class="mt-6 grid grid-cols-1 gap-3">
                    <a href="{{ route('toko.katalog') }}" class="flex h-12 items-center justify-center gap-2 rounded-xl bg-white px-5 text-sm font-bold text-primary shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[19px]">search</span>
                        {{ __('Cari Produk') }}
                    </a>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('saditacare') }}" class="flex h-12 items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/10 px-3 text-sm font-bold backdrop-blur hover:bg-white/20 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[19px]">forum</span>
                            {{ __('AI Konsultasi') }}
                        </a>
                        <a href="{{ route('toko.home') }}" class="flex h-12 items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/10 px-3 text-sm font-bold backdrop-blur hover:bg-white/20 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[19px]">warehouse</span>
                            {{ __('Gudang') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kategori Produk -->
        <section class="px-5 py-8">
            <div class="mb-4 flex items-end justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Kebutuhan Lapangan</p>
                    <h2 class="mt-1 text-2xl font-black text-primary">Mulai dari kondisi ternak</h2>
                </div>
                <a href="{{ route('toko.katalog') }}" class="text-xs font-bold uppercase tracking-wide text-primary">Produk</a>
            </div>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($categories as $category)
                    <a href="{{ route('toko.katalog', ['category' => $category->slug]) }}" class="group rounded-2xl border border-line bg-white p-4 shadow-sm hover:shadow-md hover:border-primary/30 active:scale-[0.98] transition-all">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all">
                            <span class="material-symbols-outlined">{{ $loop->iteration === 1 ? 'pulmonology' : ($loop->iteration === 2 ? 'nutrition' : ($loop->iteration === 3 ? 'cleaning_services' : 'scale')) }}</span>
                        </span>
                        <span class="mt-3 block text-base font-black">{{ $category->name }}</span>
                        <span class="mt-1 block text-sm leading-5 text-muted">{{ $category->description }}</span>
                    </a>
                @endforeach
            </div>
            <div class="mt-5 rounded-2xl border border-line bg-white p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary text-white"><span class="material-symbols-outlined">support_agent</span></span>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-black text-primary">Tidak yakin produk yang cocok?</h3>
                        <p class="mt-1 text-sm leading-6 text-muted">Kirim gejala, umur ternak, jumlah populasi, dan lokasi kandang ke tim SADITA.</p>
                        <a href="{{ route('saditacare') }}" class="mt-3 inline-flex h-11 items-center gap-2 rounded-xl bg-primary px-4 text-sm font-bold text-white shadow-md hover:bg-primary-container hover:shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all">
                            Tanya SADITA AI
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tentang Kami -->
        <section class="border-y border-line bg-white px-5 py-8">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Tentang Kami</p>
            <h2 class="mt-1 text-2xl font-black text-primary">Mitra kesehatan ternak untuk peternak Indonesia</h2>
            <p class="mt-4 text-sm leading-6 text-muted">SADITA menyediakan produk kesehatan hewan, nutrisi, dan sanitasi kandang untuk membantu peternak menjaga performa ternak secara konsisten.</p>
            <div class="mt-6 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-surface p-4"><p class="text-2xl font-black text-primary">10+</p><p class="mt-1 text-xs font-semibold text-muted">Tahun pengalaman</p></div>
                <div class="rounded-2xl bg-surface p-4"><p class="text-2xl font-black text-moss">{{ $featuredProducts->count() }}+</p><p class="mt-1 text-xs font-semibold text-muted">Produk unggulan</p></div>
                <div class="rounded-2xl bg-surface p-4"><p class="text-2xl font-black text-amber">500+</p><p class="mt-1 text-xs font-semibold text-muted">Mitra peternak</p></div>
                <div class="rounded-2xl bg-surface p-4"><p class="text-2xl font-black text-slate-700">2</p><p class="mt-1 text-xs font-semibold text-muted">Gudang layanan</p></div>
            </div>
        </section>

        <!-- Produk Unggulan -->
        <section class="px-5 py-8">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-primary">Produk Unggulan</h2>
                <a href="{{ route('toko.katalog') }}" class="text-xs font-bold uppercase tracking-wide text-primary">Lihat Semua</a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-primary px-5 py-8 text-white">
            <h2 class="text-xl font-black">SADITA</h2>
            <p class="mt-2 max-w-[340px] text-sm leading-6 text-white/80">Solusi produk kesehatan hewan dan peternakan untuk mendukung performa ternak Indonesia.</p>
            <p class="mt-6 border-t border-white/15 pt-4 text-xs text-white/65">&copy; 2026 SADITA. All rights reserved.</p>
        </footer>
    </main>

    @include('partials.cart-script')
</x-layouts.app>
