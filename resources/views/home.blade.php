<x-layouts.app title="SADITA - Solusi Sehat Ternak Anda">
    <x-header />
    <!-- Hero Section -->
    <!-- Hero Section -->
    @if(isset($heroBanners) && $heroBanners->count() > 0)
    <section class="relative min-h-[440px] overflow-hidden hero-slider-container">
        <!-- Slider Images -->
        @foreach($heroBanners as $index => $banner)
            <img class="hero-slide-image absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 z-0' : 'opacity-0 -z-10' }}" 
                alt="{{ $banner->title }}"
                src="{{ str_starts_with($banner->image_url, 'http') ? $banner->image_url : asset('storage/' . $banner->image_url) }}" 
                data-index="{{ $index }}" />
        @endforeach
        
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-primary/60 to-primary/95 pointer-events-none z-10"></div>
        
        <!-- Slider Content -->
        <div class="relative flex min-h-[440px] flex-col justify-end px-6 pb-12 text-white z-20">
            @foreach($heroBanners as $index => $banner)
                <div class="hero-slide-content absolute bottom-10 left-6 right-6 transition-all duration-700 ease-out {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4 pointer-events-none' }}"
                     data-index="{{ $index }}">
                     
                    @if($banner->subtitle)
                    <p class="mb-3 w-fit rounded-full border border-white/25 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] line-clamp-2 backdrop-blur-sm">
                        {{ $banner->subtitle }}
                    </p>
                    @endif
                    
                    <h1 class="max-w-[360px] text-4xl font-black leading-[1.04] tracking-tight">{{ $banner->title }}</h1>
                    
                    <div class="mt-6 flex flex-wrap gap-3">
                        @if($banner->button_text && $banner->link_url)
                        <a href="{{ $banner->link_url }}" class="flex h-12 items-center justify-center gap-2 rounded-xl bg-white px-5 text-sm font-bold text-primary shadow-lg hover:scale-105 active:scale-95 transition-transform pointer-events-auto">
                            <span class="material-symbols-outlined text-[19px]">arrow_forward</span>
                            {{ $banner->button_text }}
                        </a>
                        @endif
                    </div>
                </div>
            @endforeach
            
            <!-- Indicators -->
            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-30">
                @foreach($heroBanners as $index => $banner)
                    <button class="hero-slide-indicator h-1.5 w-6 rounded-full transition-colors {{ $index === 0 ? 'bg-white' : 'bg-white/30' }}"
                            data-index="{{ $index }}"></button>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.hero-slider-container');
            if (!container) return;

            const images = container.querySelectorAll('.hero-slide-image');
            const contents = container.querySelectorAll('.hero-slide-content');
            const indicators = container.querySelectorAll('.hero-slide-indicator');
            const totalSlides = images.length;
            let currentSlide = 0;
            let slideInterval;

            function goToSlide(index) {
                // Remove active classes from old slide
                images[currentSlide].classList.remove('opacity-100', 'z-0');
                images[currentSlide].classList.add('opacity-0', '-z-10');
                
                contents[currentSlide].classList.remove('opacity-100', 'translate-y-0');
                contents[currentSlide].classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                
                indicators[currentSlide].classList.remove('bg-white');
                indicators[currentSlide].classList.add('bg-white/30');

                currentSlide = index;

                // Add active classes to new slide
                images[currentSlide].classList.remove('opacity-0', '-z-10');
                images[currentSlide].classList.add('opacity-100', 'z-0');
                
                contents[currentSlide].classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                contents[currentSlide].classList.add('opacity-100', 'translate-y-0');
                
                indicators[currentSlide].classList.remove('bg-white/30');
                indicators[currentSlide].classList.add('bg-white');
            }

            function nextSlide() {
                goToSlide((currentSlide + 1) % totalSlides);
            }

            // Start auto-play
            function startSlideShow() {
                if (slideInterval) clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            }

            // Indicator click events
            indicators.forEach(indicator => {
                indicator.addEventListener('click', () => {
                    const idx = parseInt(indicator.getAttribute('data-index'));
                    goToSlide(idx);
                    startSlideShow(); // Reset timer on manual click
                });
            });

            startSlideShow();
        });
    </script>
    @else
    <!-- Fallback Hero Section -->
    <section class="relative min-h-[440px] overflow-hidden">
        <img class="absolute inset-0 h-full w-full object-cover" alt="Peternakan unggas sehat" src="https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=1200&q=80">
        <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-primary/45 to-primary/95"></div>
        <div class="relative flex min-h-[440px] flex-col justify-end px-6 pb-8 text-white">
            <p class="mb-3 w-fit rounded-full border border-white/25 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em]">Sadita Animal Health</p>
            <h1 class="max-w-[360px] text-4xl font-black leading-[1.04] tracking-tight">Solusi sehat ternak anda</h1>
            <p class="mt-4 max-w-[330px] text-base leading-7 text-white/90">Cari produk, konsultasi gejala ternak, dan pilih gudang terdekat sebelum pesan.</p>
            <div class="mt-6 grid grid-cols-1 gap-3">
                <a href="{{ route('toko.katalog') }}" class="flex h-12 items-center justify-center gap-2 rounded-xl bg-white px-5 text-sm font-bold text-primary shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[19px]">search</span>
                    Cari Produk
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Kebutuhan Lapangan Section -->
    <section id="produk" class="px-5 py-8">
        <div class="mb-4 flex items-end justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Kebutuhan Lapangan</p>
                <h2 class="mt-1 text-2xl font-black text-primary">Mulai dari kondisi ternak</h2>
            </div>
            <a href="{{ route('toko.katalog') }}" class="text-xs font-bold uppercase tracking-wide text-primary">PRODUK</a>
        </div>
        <div class="grid grid-cols-2 gap-3">
            @foreach ($categories as $category)
                @php
                    $slug = strtolower((string) $category->slug);

                    $icon = match (true) {
                        str_contains($slug, 'antibiotik') => 'medication',
                        str_contains($slug, 'vitamin') => 'nutrition',
                        str_contains($slug, 'coccidia') => 'vaccines',
                        str_contains($slug, 'anti-coccidia') => 'vaccines',
                        str_contains($slug, 'antiparasit') => 'bug_report',
                        str_contains($slug, 'disinfektan') => 'cleaning_services',
                        str_contains($slug, 'premix') => 'science',
                        default => 'inventory_2',
                    };

                    $iconClasses = match (true) {
                        str_contains($slug, 'antibiotik') => 'bg-primary/10 text-primary',
                        str_contains($slug, 'vitamin') => 'bg-moss/10 text-moss',
                        str_contains($slug, 'coccidia') => 'bg-amber/10 text-amber',
                        str_contains($slug, 'anti-coccidia') => 'bg-amber/10 text-amber',
                        str_contains($slug, 'antiparasit') => 'bg-slate-100 text-slate-700',
                        str_contains($slug, 'disinfektan') => 'bg-primary/10 text-primary',
                        str_contains($slug, 'premix') => 'bg-moss/10 text-moss',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp

                <a
                    href="{{ route('toko.katalog', ['category' => $category->slug]) }}"
                    class="group rounded-2xl border border-line bg-white p-4 shadow-sm active:scale-[0.99]"
                >
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $iconClasses }}">
                        <span class="material-symbols-outlined text-[24px]">{{ $icon }}</span>
                    </span>
                    <span class="mt-3 block text-base font-black">{{ $category->name }}</span>
                    <span class="mt-1 block text-sm leading-5 text-muted line-clamp-2">{{ $category->description }}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-5 rounded-2xl border border-line bg-white p-4 shadow-sm">
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

    <!-- Tentang Section -->
    <section id="tentang" class="border-y border-line bg-white px-5 py-8">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Tentang Kami</p>
        <h2 class="mt-1 text-2xl font-black text-primary">{{ $displaySetting->home_about_title ?: 'Mitra kesehatan ternak untuk peternak Indonesia' }}</h2>
        <p class="mt-4 text-sm leading-6 text-muted">{{ $displaySetting->home_about_description ?: 'SADITA menyediakan produk kesehatan hewan, nutrisi, dan sanitasi kandang untuk membantu peternak menjaga performa ternak secara konsisten. Kami fokus pada produk yang relevan untuk kebutuhan lapangan, distribusi yang jelas, dan layanan konsultasi yang mudah diakses.' }}</p>
        <div class="mt-6 grid grid-cols-2 gap-3">
            <div class="rounded-2xl bg-surface p-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-[22px]">workspace_premium</span>
                    </span>
                    <div>
                        <p class="text-2xl font-black text-primary">{{ $displaySetting->home_stat_experience_value ?: '10+' }}</p>
                        <p class="mt-1 text-xs font-semibold text-muted">{{ $displaySetting->home_stat_experience_label ?: 'Tahun pengalaman' }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-surface p-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-moss/10 text-moss">
                        <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                    </span>
                    <div>
                        <p class="text-2xl font-black text-moss">{{ $displaySetting->home_stat_product_value ?: (($activeProductCount ?? 200) . '+') }}</p>
                        <p class="mt-1 text-xs font-semibold text-muted">{{ $displaySetting->home_stat_product_label ?: 'Berbagai produk' }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-surface p-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber/10 text-amber">
                        <span class="material-symbols-outlined text-[22px]">groups</span>
                    </span>
                    <div>
                        <p class="text-2xl font-black text-amber">{{ $displaySetting->home_stat_partner_value ?: '500+' }}</p>
                        <p class="mt-1 text-xs font-semibold text-muted">{{ $displaySetting->home_stat_partner_label ?: 'Mitra & klien' }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-surface p-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                        <span class="material-symbols-outlined text-[22px]">local_shipping</span>
                    </span>
                    <div>
                        <p class="text-2xl font-black text-slate-700">{{ $displaySetting->home_stat_volume_value ?: '100 Ton' }}</p>
                        <p class="mt-1 text-xs font-semibold text-muted">{{ $displaySetting->home_stat_volume_label ?: 'Produk setiap bulan' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Artikel Section -->
    <section id="artikel" class="px-5 py-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-primary">Artikel Terbaru</h2>
            <a href="{{ route('artikel') }}" class="text-xs font-bold uppercase tracking-wide text-primary">Baca Semua</a>
        </div>
        <div class="space-y-4">
            @forelse($latestArticles as $article)
                <a href="{{ route('artikel.show', $article) }}" class="flex gap-4 rounded-2xl border border-line bg-white p-3 shadow-sm">
                    @if($article->featured_image)
                        <img class="h-24 w-24 rounded-xl object-cover" alt="{{ $article->title }}"
                            src="{{ str_starts_with($article->featured_image, 'http') ? $article->featured_image : Storage::url($article->featured_image) }}" 
                            onerror="this.onerror=null;this.src='https://placehold.co/400x400/F1F5F9/94A3B8?text=No+Image';" />
                    @else
                        <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-xl bg-surface text-muted">
                            <span class="material-symbols-outlined text-[32px]">article</span>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        @if($article->category)
                            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-moss">{{ $article->category->name }}</p>
                        @endif
                        <h3 class="mt-1 line-clamp-2 text-sm font-bold">{{ $article->title }}</h3>
                        @if($article->excerpt)
                            <p class="mt-2 line-clamp-2 text-xs leading-5 text-muted">{{ $article->excerpt }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <a href="{{ route('artikel') }}" class="flex gap-4 rounded-2xl border border-line bg-white p-3 shadow-sm">
                    <img class="h-24 w-24 rounded-xl object-cover" alt="Dokter hewan memeriksa ayam"
                        src="https://images.unsplash.com/photo-1563460716037-460a3ad24ba9?auto=format&fit=crop&w=600&q=80" />
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-moss">Berita</p>
                        <h3 class="mt-1 line-clamp-2 text-sm font-bold">Pencegahan CRD kompleks pada ayam broiler</h3>
                        <p class="mt-2 line-clamp-2 text-xs leading-5 text-muted">Langkah praktis menjaga kualitas air minum, sanitasi, dan monitoring gejala awal.</p>
                    </div>
                </a>
                <a href="{{ route('artikel') }}" class="flex gap-4 rounded-2xl border border-line bg-white p-3 shadow-sm">
                    <img class="h-24 w-24 rounded-xl object-cover" alt="Peternakan ayam modern"
                        src="https://images.unsplash.com/photo-1589923188900-85dae523342b?auto=format&fit=crop&w=600&q=80" />
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-moss">Berita</p>
                        <h3 class="mt-1 line-clamp-2 text-sm font-bold">Distribusi produk kesehatan ternak makin dekat</h3>
                        <p class="mt-2 line-clamp-2 text-xs leading-5 text-muted">Update layanan gudang untuk membantu peternak mendapatkan produk lebih cepat.</p>
                    </div>
                </a>
            @endforelse
        </div>
    </section>

    <!-- CTA Section -->
    <section class="mx-5 mb-8 rounded-3xl bg-amber/10 p-6 text-center border border-amber/20">
        <h2 class="text-lg font-black text-ink">Masih bingung menentukan produk?</h2>
        <p class="mt-2 text-sm leading-6 text-muted">Konsultasikan gejala ternak Anda dengan SaditaCare dan dapatkan rekomendasi produk yang sesuai.</p>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('saditacare') }}" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 text-sm font-bold text-white shadow-[0_4px_15px_rgba(128,0,0,0.2)] hover:bg-primary/90 active:scale-95 transition-all sm:w-auto">
                <span class="material-symbols-outlined text-[20px]">smart_toy</span>
                Tanya SaditaCare
            </a>
            <a href="{{ route('chat') }}" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl border-2 border-primary bg-white px-6 text-sm font-bold text-primary active:scale-95 transition-all sm:w-auto">
                <span class="material-symbols-outlined text-[20px]">medical_services</span>
                Konsultasi Dokter Hewan
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary px-5 pt-8 pb-[100px] -mb-[100px] text-white">
        <h2 class="text-xl font-black mb-4">SADITA</h2>
        
        <div class="grid grid-cols-2 gap-4 text-sm mb-6">
            <div class="space-y-3">
                <a class="block text-white/80 hover:text-white font-medium" href="{{ route('tentang') }}">Tentang SADITA</a>
                <a class="block text-white/80 hover:text-white font-medium" href="{{ route('produk') }}">Navigasi</a>
                <a class="block text-white/80 hover:text-white font-medium" href="{{ route('chat') }}">Kontak</a>
            </div>
            <div class="space-y-3">
                <a class="block text-white/80 hover:text-white font-medium" href="#">Kebijakan Privasi</a>
                <a class="block text-white/80 hover:text-white font-medium" href="#">Syarat & Ketentuan</a>
            </div>
        </div>

        <div class="border-t border-white/15 pt-4 text-center">
            <p class="text-xs text-white/70">&copy; {{ date('Y') }} SADITA. All rights reserved.</p>
        </div>
    </footer>

    @push('styles')
    <style>
        .hero-track { animation: heroSlide 24s linear infinite; }
        @keyframes heroSlide {
            0%, 20% { transform: translateX(0); }
            25%, 45% { transform: translateX(-100%); }
            50%, 70% { transform: translateX(-200%); }
            75%, 95% { transform: translateX(-300%); }
            100% { transform: translateX(0); }
        }
    </style>
    @endpush
</x-layouts.app>
