<x-layouts.app title="Artikel - SADITA">
    <!-- Hero Title -->
    <section class="mb-6 px-5 pt-6">
        <h1 class="mb-2 text-2xl font-black text-primary">Artikel Kesehatan Hewan</h1>
        <p class="text-sm text-muted">Informasi terkini mengenai kesehatan ternak, pencegahan penyakit, dan tips farmasi veteriner.</p>
    </section>

    <!-- Search Bar -->
    <section class="mb-6 px-5">
        <form id="artikel-filter-form" method="GET" action="{{ route('artikel') }}" class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-muted">search</span>
            <input
                name="q"
                value="{{ $search ?? '' }}"
                class="w-full rounded-xl border border-line bg-white py-3 pl-12 pr-20 shadow-sm outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"
                placeholder="Cari topik kesehatan hewan..." type="text" />
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg bg-primary px-3 py-1.5 text-xs font-bold text-white hover:bg-primary/90">
                Cari
            </button>
            @if(!empty($activeCategory))
                <input type="hidden" name="category" value="{{ $activeCategory }}">
            @endif
        </form>
    </section>

    <!-- Filter Tabs -->
    <section class="scrollbar-hide mb-8 flex gap-3 overflow-x-auto px-5">
        <a data-artikel-nav href="{{ route('artikel', ['q' => $search]) }}" class="flex-shrink-0 rounded-full px-6 py-2 text-xs font-bold uppercase tracking-wide {{ empty($activeCategory) ? 'bg-primary text-white' : 'bg-surface text-ink hover:bg-line' }}">
            Semua
        </a>
        @foreach($categories as $cat)
            <a data-artikel-nav href="{{ route('artikel', ['category' => $cat->slug, 'q' => $search]) }}" class="flex-shrink-0 rounded-full px-6 py-2 text-xs font-bold uppercase tracking-wide {{ ($activeCategory ?? '') === $cat->slug ? 'bg-primary text-white' : 'bg-surface text-ink hover:bg-line' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </section>

    <!-- Articles List -->
    <section class="space-y-6 px-5">
        @forelse($articles as $article)
            <article class="group overflow-hidden rounded-xl border border-line bg-white shadow-sm">
                @if($article->featured_image)
                    <a href="{{ route('artikel.show', $article) }}" class="block h-48 w-full overflow-hidden bg-slate-100">
                        <img alt="{{ $article->title }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            src="{{ str_starts_with($article->featured_image, 'http') ? $article->featured_image : Storage::url($article->featured_image) }}"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=800&q=80';" loading="lazy" />
                    </a>
                @else
                    <a href="{{ route('artikel.show', $article) }}" class="block h-48 w-full overflow-hidden bg-slate-100">
                        <img alt="{{ $article->title }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            src="https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=800&q=80" loading="lazy" />
                    </a>
                @endif
                <div class="p-4">
                    <div class="mb-2 flex items-center gap-2">
                        @if($article->category)
                            <span class="rounded bg-primary/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-primary">
                                {{ $article->category->name }}
                            </span>
                        @endif
                        <span class="text-[11px] text-muted">
                            {{ $article->published_at?->translatedFormat('d M Y') ?? $article->created_at->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <a href="{{ route('artikel.show', $article) }}" class="mb-2 block text-base font-bold leading-tight text-ink hover:text-primary">{{ $article->title }}</a>
                    @if($article->excerpt)
                        <p class="line-clamp-2 text-sm text-muted">{{ $article->excerpt }}</p>
                    @endif
                    <a href="{{ route('artikel.show', $article) }}" class="mt-4 inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wide text-primary">
                        <span>Baca Selengkapnya</span>
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </a>
                </div>
            </article>
        @empty
            <div class="rounded-xl border border-line bg-surface p-4 text-center">
                <span class="material-symbols-outlined text-[32px] text-muted">article</span>
                @if(!empty($search) || !empty($activeCategory))
                    <p class="mt-2 text-sm font-bold text-primary">Artikel tidak ditemukan</p>
                    <p class="mt-1 text-xs text-muted">Coba ubah kata kunci atau pilih kategori lain.</p>
                    <a href="{{ route('artikel') }}" class="mt-3 inline-flex h-9 items-center gap-1 rounded-lg bg-primary px-4 text-xs font-bold text-white">
                        Reset Filter
                    </a>
                @else
                    <p class="mt-2 text-sm font-bold text-primary">Belum ada artikel</p>
                    <p class="mt-1 text-xs text-muted">Tambahkan artikel pertama melalui panel admin.</p>
                    @auth
                    <a href="{{ url('/admin/articles/create') }}" class="mt-3 inline-flex h-9 items-center gap-1 rounded-lg bg-primary px-4 text-xs font-bold text-white">
                        Tambah Artikel
                    </a>
                    @endauth
                @endif
            </div>
        @endforelse

        @if($articles->hasPages())
            <div class="pt-2">
                {{ $articles->links() }}
            </div>
        @endif
    </section>

    <section id="artikel-skeleton" class="hidden space-y-4 px-5">
        @for ($i = 0; $i < 4; $i++)
            <div class="overflow-hidden rounded-xl border border-line bg-white p-4 shadow-sm">
                <div class="sadita-skeleton h-40 w-full rounded-xl"></div>
                <div class="mt-3 sadita-skeleton h-3 w-20 rounded"></div>
                <div class="mt-2 sadita-skeleton h-4 w-4/5 rounded"></div>
                <div class="mt-2 sadita-skeleton h-4 w-3/5 rounded"></div>
            </div>
        @endfor
    </section>


    <script>
        (() => {
            const form = document.getElementById('artikel-filter-form');
            const links = document.querySelectorAll('[data-artikel-nav]');
            const skeleton = document.getElementById('artikel-skeleton');
            const listSection = document.querySelector('section.space-y-6.px-5');

            function showSkeleton() {
                if (!skeleton || !listSection) return;
                skeleton.classList.remove('hidden');
                listSection.classList.add('hidden');
            }

            if (form) form.addEventListener('submit', showSkeleton);
            links.forEach(link => link.addEventListener('click', showSkeleton));
        })();
    </script>
</x-layouts.app>
