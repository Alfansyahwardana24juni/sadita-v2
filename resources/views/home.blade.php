<x-layouts.app title="SADITA - Solusi Sehat Ternak Anda">

    @php
        $slides = $heroBanners->isNotEmpty() ? $heroBanners : collect([(object) [
            'title' => 'Solusi sehat ternak anda',
            'subtitle' => 'Cari produk, konsultasi gejala ternak, dan pilih gudang terdekat sebelum pesan.',
            'image_url' => 'https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=1200&q=80',
        ]]);
    @endphp

    <!-- Hero Section -->
    <section class="relative min-h-[440px] overflow-hidden hero-slider-container">
        @foreach($slides as $slide)
            <img class="hero-slide-image absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 ease-in-out {{ $loop->first ? 'opacity-100 z-0' : 'opacity-0 -z-10' }}"
                alt="{{ $slide->title }}"
                src="{{ str_starts_with($slide->image_url ?? '', 'http') ? $slide->image_url : \Illuminate\Support\Facades\Storage::url($slide->image_url) }}"
                data-index="{{ $loop->index }}"
                onerror="this.onerror=null;this.src='https://placehold.co/800x600/F1F5F9/94A3B8?text=SADITA';" />
        @endforeach

        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-primary/60 to-primary/95 pointer-events-none z-10"></div>

        <div class="relative flex min-h-[440px] flex-col justify-end px-6 pb-12 text-white z-20">
            @foreach($slides as $slide)
                <div class="hero-slide-content absolute bottom-10 left-6 right-6 transition-all duration-700 ease-out {{ $loop->first ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4 pointer-events-none' }}"
                    data-index="{{ $loop->index }}">
                    @if($slide->subtitle)
                        <p class="mb-3 w-fit rounded-full border border-white/25 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] line-clamp-2 backdrop-blur-sm">
                            {{ $slide->subtitle }}
                        </p>
                    @endif
                    <h1 class="max-w-[360px] text-4xl font-black leading-[1.04] tracking-tight">{{ $slide->title }}</h1>
                    @if($loop->first)
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('produk') }}" class="flex h-12 items-center justify-center gap-2 rounded-xl bg-white px-5 text-sm font-bold text-primary shadow-lg hover:scale-105 active:scale-95 transition-transform pointer-events-auto">
                                <span class="material-symbols-outlined text-[19px]">arrow_forward</span>
                                Lihat Produk
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach

            @if($slides->count() > 1)
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-30">
                    @foreach($slides as $slide)
                        <button class="hero-slide-indicator h-1.5 w-6 rounded-full transition-colors {{ $loop->first ? 'bg-white' : 'bg-white/30' }}" data-index="{{ $loop->index }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if($slides->count() > 1)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const container = document.querySelector('.hero-slider-container');
                if (!container) return;
                const images = container.querySelectorAll('.hero-slide-image');
                const contents = container.querySelectorAll('.hero-slide-content');
                const indicators = container.querySelectorAll('.hero-slide-indicator');
                const total = images.length;
                let current = 0;
                let timer;

                function goToSlide(index) {
                    images[current].classList.remove('opacity-100', 'z-0');
                    images[current].classList.add('opacity-0', '-z-10');
                    contents[current].classList.remove('opacity-100', 'translate-y-0');
                    contents[current].classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                    indicators[current].classList.remove('bg-white');
                    indicators[current].classList.add('bg-white/30');

                    current = index;

                    images[current].classList.remove('opacity-0', '-z-10');
                    images[current].classList.add('opacity-100', 'z-0');
                    contents[current].classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                    contents[current].classList.add('opacity-100', 'translate-y-0');
                    indicators[current].classList.remove('bg-white/30');
                    indicators[current].classList.add('bg-white');
                }

                function startShow() {
                    if (timer) clearInterval(timer);
                    timer = setInterval(() => goToSlide((current + 1) % total), 5000);
                }

                indicators.forEach(indicator => indicator.addEventListener('click', () => {
                    goToSlide(parseInt(indicator.getAttribute('data-index')));
                    startShow();
                }));

                startShow();
            });
        </script>
    @endif

    <!-- Kategori Produk -->
    <section id="produk" class="px-5 py-8">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-primary">Kategori Produk</h2>
            <a href="{{ route('produk') }}" class="text-sm font-medium text-primary">Lihat Semua</a>
        </div>

        <div class="mt-4 flex gap-4 overflow-x-auto scrollbar-hide pb-2 -mx-5 px-5 snap-x snap-mandatory">
            @foreach($categories as $category)
                <div class="flex min-w-0 flex-shrink-0 snap-start flex-col items-center w-20">
                    <a href="{{ route('produk.category', $category) }}" class="group flex flex-col items-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm transition-transform group-active:scale-95">
                            @if($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="h-12 w-12 object-contain" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/100x100/F1F5F9/94A3B8?text=?';">
                            @else
                                <span class="material-symbols-outlined text-[32px] text-primary">category</span>
                            @endif
                        </div>
                        <p class="mt-2 text-center text-xs font-semibold leading-tight text-slate-700">{{ $category->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Tentang -->
    <section id="tentang" class="border-y border-line bg-white px-5 py-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-primary">Tentang Kami</h2>
            <a href="{{ route('tentang') }}" class="text-sm font-medium text-primary">Lihat Semua</a>
        </div>
        <x-about-stats />
    </section>

    <!-- Artikel -->
    @if($latestArticles->isNotEmpty())
        <section id="artikel" class="px-5 py-8">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-primary">Artikel</h2>
                <a href="{{ route('artikel') }}" class="text-sm font-medium text-primary">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @foreach($latestArticles as $article)
                    <a href="{{ route('artikel.show', $article) }}" class="flex gap-4 rounded-2xl border border-line bg-white p-3 shadow-sm">
                        <img class="h-24 w-24 rounded-xl object-cover" alt="{{ $article->title }}"
                            src="{{ $article->featured_image ? (str_starts_with($article->featured_image, 'http') ? $article->featured_image : \Illuminate\Support\Facades\Storage::url($article->featured_image)) : 'https://images.unsplash.com/photo-1500595046891-9c05b5d43e6f?auto=format&fit=crop&w=800&q=80' }}"
                            onerror="this.onerror=null;this.src='https://placehold.co/200x200/F1F5F9/94A3B8?text=No+Image';" />
                        <div class="min-w-0 flex-1">
                            @if($article->category)
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-moss">{{ $article->category->name }}</p>
                            @endif
                            <h3 class="mt-1 line-clamp-2 text-sm font-bold">{{ $article->title }}</h3>
                            <p class="mt-2 line-clamp-2 text-xs leading-5 text-muted">{{ $article->excerpt }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <a href="{{ route('artikel') }}" class="mt-6 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-bold text-white shadow-[0_4px_15px_rgba(128,0,0,0.2)] hover:bg-primary/90 active:scale-95 transition-all">
                Baca Selengkapnya
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </section>
    @endif

    <!-- Bantuan Memilih Produk -->
    <section class="px-5 pb-8">
        <div class="rounded-2xl border border-line bg-white p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary text-white">
                    <span class="material-symbols-outlined">support_agent</span>
                </span>
                <div class="min-w-0 flex-1">
                    <h3 class="text-base font-black text-primary">Tidak yakin produk yang cocok?</h3>
                    <p class="mt-1 text-sm leading-6 text-muted">Kirim gejala, umur ternak, jumlah populasi, dan lokasi kandang ke tim SADITA.</p>
                    <a href="{{ route('saditacare') }}" class="mt-3 inline-flex h-11 items-center gap-2 rounded-xl bg-primary px-4 text-sm font-bold text-white">
                        Tanya SaditaCare
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary px-5 pt-8 pb-[100px] -mb-[100px] text-white">
        <h2 class="mb-4 text-xl font-black">SADITA</h2>
        <div class="mb-6 grid grid-cols-2 gap-4 text-sm">
            <div class="space-y-3">
                <a class="block font-medium text-white/80 hover:text-white" href="{{ route('tentang') }}">Tentang SADITA</a>
                <a class="block font-medium text-white/80 hover:text-white" href="{{ route('produk') }}">Produk</a>
                <a class="block font-medium text-white/80 hover:text-white" href="{{ route('chat') }}">Kontak</a>
            </div>
            <div class="space-y-3">
                <a class="block font-medium text-white/80 hover:text-white" href="{{ route('artikel') }}">Artikel</a>
                <a class="block font-medium text-white/80 hover:text-white" href="{{ route('saditacare') }}">SaditaCare</a>
            </div>
        </div>
        <div class="border-t border-white/15 pt-4 text-center">
            <p class="text-xs text-white/70">&copy; {{ now()->year }} SADITA. All rights reserved.</p>
        </div>
    </footer>

    @include('partials.cart-script')
</x-layouts.app>
