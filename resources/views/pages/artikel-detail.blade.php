<x-layouts.app :title="$article->meta_title ?: ($article->title . ' - SADITA')">
    <section class="border-b border-line bg-white px-5 py-5">
        <a href="{{ route('artikel') }}" class="mb-3 inline-flex h-9 items-center gap-1 rounded-lg px-2 text-xs font-bold uppercase tracking-wide text-primary">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke artikel
        </a>
        <h1 class="text-2xl font-black leading-tight text-primary">{{ $article->title }}</h1>
        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-muted">
            @if($article->category)
                <span class="rounded bg-primary/10 px-2 py-1 font-bold uppercase tracking-wider text-primary">{{ $article->category->name }}</span>
            @endif
            <span>{{ $article->published_at?->translatedFormat('d M Y') ?? $article->created_at->translatedFormat('d M Y') }}</span>
            <span>&middot;</span>
            <span>{{ number_format($article->views_count, 0, ',', '.') }} views</span>
        </div>
    </section>

    @if($article->featured_image)
        <section class="mt-6">
            <img
                src="{{ str_starts_with($article->featured_image, 'http') ? $article->featured_image : Storage::url($article->featured_image) }}"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=800&q=80';"
                alt="{{ $article->title }}"
                class="h-56 w-full object-cover bg-slate-100"
            />
        </section>
    @else
        <section class="mt-6">
            <img
                src="https://images.unsplash.com/photo-1548550023-2bdb3c5beed7?auto=format&fit=crop&w=800&q=80"
                alt="{{ $article->title }}"
                class="h-56 w-full object-cover bg-slate-100"
            />
        </section>
    @endif

    <section class="bg-white px-5 py-6">
        @if($article->excerpt)
            <p class="mb-4 rounded-xl border border-line bg-surface p-3 text-sm leading-6 text-muted">
                {{ $article->excerpt }}
            </p>
        @endif

        <article class="space-y-4 text-sm leading-7 text-ink">
            {!! $article->content !!}
        </article>
    </section>

    @if($relatedArticles->isNotEmpty())
        <section class="border-t border-line px-5 py-8">
            <h2 class="mb-4 text-lg font-black text-primary">Artikel Terkait</h2>
            <div class="space-y-3">
                @foreach($relatedArticles as $related)
                    <a href="{{ route('artikel.show', $related) }}" class="block rounded-xl border border-line bg-white p-3 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-moss">{{ $related->category?->name ?? 'Artikel' }}</p>
                        <h3 class="mt-1 text-sm font-bold leading-5 text-ink">{{ $related->title }}</h3>
                        @if($related->excerpt)
                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-muted">{{ $related->excerpt }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.app>
