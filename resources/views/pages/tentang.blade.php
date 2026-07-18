<x-layouts.app title="Tentang Kami - SADITA">
    <!-- 1. Hero Section -->
    <section class="px-5 pt-7 pb-4">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Tentang Kami</p>
        <h1 class="mt-2 text-3xl font-black leading-tight text-primary">Tentang SADITA</h1>
        <p class="mt-4 text-sm leading-6 text-muted">
            SADITA adalah mitra produk kesehatan hewan yang berfokus pada solusi praktis untuk peternak Indonesia. Kami menghadirkan portofolio produk veteriner, dukungan teknis lapangan, serta distribusi yang cepat dan terukur.
        </p>
    </section>

    <!-- 2. Tentang Perusahaan -->
    <section class="mx-5 mt-6 p-5 rounded-2xl border border-line bg-white shadow-sm">
        <h2 class="text-xl font-black text-primary">PT Satwa Medika Utama</h2>
        <p class="mt-3 text-sm leading-6 text-muted">
            PT Satwa Medika Utama adalah perusahaan yang bergerak di industry Obat Hewan. PT Satwa Medika Utama atau yang juga dikenal dengan Scafha berfokus pada produksi dan juga distributor Obat Hewan. PT Satwa Medika Utama menawarkan berbagai macam produk untuk spesies unggas, numinasia, dan akuatik.</p>
        <div class="mt-5 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('toko.home') }}" class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-bold text-white transition-all hover:bg-primary/90">
                Kunjungi Toko
            </a>
            <a href="https://wa.me/6281234567890" class="inline-flex items-center justify-center rounded-xl border border-line bg-surface px-4 py-2.5 text-sm font-bold text-primary transition-all hover:bg-slate-50">
                Hubungi Kami
            </a>
        </div>
    </section>

    <!-- 3. Statistik Perusahaan -->
    <section class="mt-8 px-5">
        <h2 class="text-xl font-black text-primary">Statistik Kami</h2>
        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="flex flex-col items-center justify-center rounded-2xl border border-line bg-white p-5 text-center shadow-sm">
                <span class="material-symbols-outlined text-4xl text-moss">work_history</span>
                <p class="mt-3 text-2xl font-black text-primary">10+</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-wide text-muted">Tahun Pengalaman</p>
            </div>
            <div class="flex flex-col items-center justify-center rounded-2xl border border-line bg-white p-5 text-center shadow-sm">
                <span class="material-symbols-outlined text-4xl text-moss">inventory_2</span>
                <p class="mt-3 text-2xl font-black text-primary">200+</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-wide text-muted">200+ Berbagai Produk Untuk Unggas, Hewan Besar, & Satwa Akuatik</p>
            </div>
            <div class="flex flex-col items-center justify-center rounded-2xl border border-line bg-white p-5 text-center shadow-sm">
                <span class="material-symbols-outlined text-4xl text-moss">handshake</span>
                <p class="mt-3 text-2xl font-black text-primary">500+</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-wide text-muted">500+ Mitra & Klien Tersebar diseluruh Indonesia

</p>
            </div>
            <div class="flex flex-col items-center justify-center rounded-2xl border border-line bg-white p-5 text-center shadow-sm">
                <span class="material-symbols-outlined text-4xl text-moss">factory</span>
                <p class="mt-3 text-2xl font-black text-primary">100</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-wide text-muted">Ton Produksi/Bulan</p>
            </div>
        </div>
    </section>

    <!-- 4. Video Perusahaan -->
    <section class="mx-5 mt-8 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
        <div class="aspect-video w-full bg-slate-100 relative">
            <iframe class="absolute inset-0 h-full w-full" src="https://www.youtube.com/embed/ElI6Ee4bX2s" title="Video Perusahaan" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="p-4">
            <h3 class="text-sm font-black text-ink">Mengenal Lebih Dekat SADITA</h3>
            <p class="mt-1 text-[11px] text-muted">Saksikan profil perusahaan kami untuk melihat proses produksi dan dedikasi kami terhadap kesehatan hewan.</p>
        </div>
    </section>

    <!-- 5. Timeline Perusahaan -->
    <section class="mt-10 px-5">
        <h2 class="text-xl font-black text-primary">Perjalanan Kami</h2>
        <div class="relative ml-4 mt-6 space-y-6 border-l-2 border-primary/20 pl-6">
            <div class="relative">
                <span class="absolute -left-[35px] top-0 flex h-6 w-6 items-center justify-center rounded-full bg-white border-2 border-primary">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                </span>
                <p class="text-xs font-bold uppercase tracking-wide text-moss">2012</p>
                <h3 class="mt-1 text-sm font-black text-ink">Perusahaan Berdiri</h3>
                <p class="mt-1 text-[12px] text-muted">Pendirian PT Satwa Medika Utama dengan komitmen melayani kebutuhan peternak.</p>
            </div>
            <div class="relative">
                <span class="absolute -left-[35px] top-0 flex h-6 w-6 items-center justify-center rounded-full bg-white border-2 border-primary">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                </span>
                <p class="text-xs font-bold uppercase tracking-wide text-moss">2014</p>
                <h3 class="mt-1 text-sm font-black text-ink">Registrasi Produk</h3>
                <p class="mt-1 text-[12px] text-muted">Melakukan registrasi produk secara resmi sesuai regulasi pemerintah.</p>
            </div>
            <div class="relative">
                <span class="absolute -left-[35px] top-0 flex h-6 w-6 items-center justify-center rounded-full bg-white border-2 border-primary">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                </span>
                <p class="text-xs font-bold uppercase tracking-wide text-moss">2015</p>
                <h3 class="mt-1 text-sm font-black text-ink">Pabrik Berdiri</h3>
                <p class="mt-1 text-[12px] text-muted">Pembangunan fasilitas produksi mandiri untuk meningkatkan kapasitas pasokan.</p>
            </div>
            <div class="relative">
                <span class="absolute -left-[35px] top-0 flex h-6 w-6 items-center justify-center rounded-full bg-white border-2 border-primary">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                </span>
                <p class="text-xs font-bold uppercase tracking-wide text-moss">2018</p>
                <h3 class="mt-1 text-sm font-black text-ink">Sertifikat CPOHB</h3>
                <p class="mt-1 text-[12px] text-muted">Berhasil meraih sertifikat Cara Pembuatan Obat Hewan yang Baik (CPOHB).</p>
            </div>
        </div>
    </section>

    <!-- 6. Sertifikat -->
    <section class="mt-10 px-5" x-data="{ showModal: false, activeImage: '', activeTitle: '' }">
        <h2 class="text-xl font-black text-primary">Sertifikasi & Penghargaan</h2>
        
        <div class="mt-4">
            <!-- Card Horizontal -->
            <div class="flex items-center gap-4 rounded-[20px] border border-line bg-white p-3 shadow-sm">
                <div class="relative w-[120px] shrink-0 overflow-hidden rounded-xl bg-slate-50 aspect-[4/5]">
                    <img src="{{ asset('images/Sertifikat CPOHB.jpg') }}" alt="Sertifikat CPOHB" class="absolute inset-0 h-full w-full object-contain p-1" loading="lazy" />
                </div>
                <div class="flex flex-col">
                    <h3 class="text-sm font-black text-ink leading-tight">Sertifikat CPOHB</h3>
                    <p class="mt-1 text-[11px] text-muted">No: CPOHB/2024/001</p>
                    <p class="text-[11px] text-muted">Berlaku hingga Juni 2029</p>
                    
                    <button @click="activeImage = '{{ asset('images/Sertifikat CPOHB.jpg') }}'; activeTitle = 'Sertifikat CPOHB'; showModal = true" class="mt-3 flex w-max items-center justify-center gap-1 rounded-xl bg-primary/10 px-4 py-2 text-[11px] font-bold text-primary transition-colors hover:bg-primary/20">
                        <span class="material-symbols-outlined text-[16px]">zoom_in</span>
                        Lihat Sertifikat
                    </button>
                </div>
            </div>
        </div>

        <!-- Fullscreen Modal -->
        <div x-show="showModal" 
             style="display: none;" 
             class="fixed inset-0 z-[100] flex flex-col bg-black/95 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Header Modal -->
            <div class="flex items-center justify-between p-4 text-white">
                <h3 class="text-sm font-bold" x-text="activeTitle"></h3>
                <div class="flex items-center gap-4">
                    <a :href="activeImage" download class="flex items-center justify-center text-white/80 hover:text-white">
                        <span class="material-symbols-outlined">download</span>
                    </a>
                    <button @click="showModal = false" class="flex items-center justify-center text-white/80 hover:text-white">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>

            <!-- Image Container -->
            <div class="flex-1 overflow-auto p-4 flex items-center justify-center" @click.self="showModal = false">
                <img :src="activeImage" :alt="activeTitle" class="max-h-full max-w-full object-contain" />
            </div>
        </div>
    </section>

    <!-- 7. Mengapa Memilih SADITA -->
    <section class="mt-12 px-5">
        <h2 class="text-2xl font-black text-primary">Mengapa Memilih SADITA</h2>
        <p class="mt-2 text-sm leading-6 text-muted max-w-2xl">SADITA berkomitmen menghadirkan produk kesehatan hewan berkualitas melalui standar produksi yang ketat, tenaga profesional, dan sistem manajemen mutu yang terpercaya.</p>
        
        <div class="mt-8 flex flex-col gap-5">
            <!-- Card 1 -->
            <div class="group flex flex-col sm:flex-row gap-5 rounded-3xl border border-line bg-white p-4 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="relative h-48 sm:h-32 sm:w-48 shrink-0 overflow-hidden rounded-2xl bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80" alt="SDM Berpengalaman" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-lg font-black text-ink">SDM Berpengalaman</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted">Didukung tenaga profesional yang ahli di bidang kesehatan hewan dan peternakan.</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="group flex flex-col sm:flex-row gap-5 rounded-3xl border border-line bg-white p-4 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="relative h-48 sm:h-32 sm:w-48 shrink-0 overflow-hidden rounded-2xl bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=600&q=80" alt="Standar Produksi Nasional" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-lg font-black text-ink">Standar Produksi Nasional</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted">Produk diproduksi mengikuti standar CPOHB dan SOP perusahaan.</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="group flex flex-col sm:flex-row gap-5 rounded-3xl border border-line bg-white p-4 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="relative h-48 sm:h-32 sm:w-48 shrink-0 overflow-hidden rounded-2xl bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=600&q=80" alt="Produksi Berkualitas" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-lg font-black text-ink">Produksi Berkualitas</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted">Seluruh proses produksi diawasi agar menghasilkan produk yang aman dan konsisten.</p>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="group flex flex-col sm:flex-row gap-5 rounded-3xl border border-line bg-white p-4 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="relative h-48 sm:h-32 sm:w-48 shrink-0 overflow-hidden rounded-2xl bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?auto=format&fit=crop&w=600&q=80" alt="Dokumentasi Lengkap" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-lg font-black text-ink">Dokumentasi Lengkap</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted">Seluruh aktivitas produksi terdokumentasi sesuai standar industri.</p>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="group flex flex-col sm:flex-row gap-5 rounded-3xl border border-line bg-white p-4 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md">
                <div class="relative h-48 sm:h-32 sm:w-48 shrink-0 overflow-hidden rounded-2xl bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=600&q=80" alt="Quality Control" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                </div>
                <div class="flex flex-col justify-center">
                    <h3 class="text-lg font-black text-ink">Quality Control</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted">Setiap produk melewati proses pengawasan mutu sebelum dipasarkan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. CTA -->
    <section class="mx-5 my-10 rounded-3xl bg-primary p-6 text-center text-white shadow-lg relative overflow-hidden">
        <!-- Abstract background pattern -->
        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
        
        <div class="relative z-10">
            <h2 class="text-xl font-black">Ingin Mengetahui Produk Kami?</h2>
            <p class="mt-2 text-sm text-white/80">Temukan solusi terbaik untuk kesehatan ternak Anda sekarang juga.</p>
            <div class="mt-6 flex flex-col gap-3">
                <a href="{{ route('produk') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-bold text-primary transition-all hover:bg-slate-50">
                    <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                    Lihat Produk
                </a>
                <a href="{{ route('toko.home') }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#600000] px-4 py-3 text-sm font-bold text-white transition-all hover:bg-[#500000]">
                    <span class="material-symbols-outlined text-[18px]">storefront</span>
                    Kunjungi Toko
                </a>
                <a href="{{ route('saditacare') }}" class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/20 bg-transparent px-4 py-3 text-sm font-bold text-white transition-all hover:bg-white/10">
                    <span class="material-symbols-outlined text-[18px]">support_agent</span>
                    Konsultasi SaditaCare
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
