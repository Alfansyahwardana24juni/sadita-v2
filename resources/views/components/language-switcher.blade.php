@php
    $languages = config('translate.languages', []);
    $byCode = collect($languages)->keyBy('code');
    $popular = collect(config('translate.popular', []))
        ->map(fn ($code) => $byCode->get($code))
        ->filter()
        ->values()
        ->all();
@endphp

<div
    {{ $attributes->merge(['class' => 'relative notranslate']) }}
    translate="no"
    x-data="languageSwitcher(@js(array_values($languages)), @js($popular))"
    x-on:click.outside="open = false"
    x-on:keydown.escape.window="open = false"
>
    <button
        type="button"
        x-on:click="toggle()"
        class="flex items-center gap-1.5 rounded-full border border-line bg-white/95 px-2.5 py-1.5 text-xs font-bold text-slate-700 shadow-sm backdrop-blur transition hover:text-primary"
        :aria-expanded="open"
        aria-label="Pilih bahasa"
    >
        <span class="text-sm leading-none" x-text="current.flag"></span>
        <span x-text="badge"></span>
        <span class="material-symbols-outlined text-[16px] transition-transform" :class="open && 'rotate-180'">expand_more</span>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="absolute right-0 z-[80] mt-2 w-[16.5rem] overflow-hidden rounded-2xl border border-line bg-white shadow-xl"
        style="display: none;"
    >
        <div class="border-b border-line p-2">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-[18px] text-muted">search</span>
                <input
                    x-ref="search"
                    x-model="q"
                    type="search"
                    placeholder="Cari bahasa..."
                    class="w-full rounded-xl border border-line bg-surface py-2 pl-9 pr-3 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
                >
            </div>
        </div>

        <div class="max-h-[19rem] overflow-y-auto p-1">
            <template x-if="!q.trim() && popular.length">
                <div>
                    <p class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-[0.14em] text-muted">Populer</p>
                    <template x-for="lang in popular" :key="'pop-' + lang.code">
                        <button type="button" x-on:click="choose(lang.code)" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left transition hover:bg-surface">
                            <span class="text-lg leading-none" x-text="lang.flag"></span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-semibold text-ink" x-text="lang.name"></span>
                                <span class="block truncate text-xs text-muted" x-text="lang.english"></span>
                            </span>
                            <span class="material-symbols-outlined text-[18px] text-primary" x-show="lang.code === current.code">check</span>
                        </button>
                    </template>
                    <div class="my-1 border-t border-line"></div>
                </div>
            </template>

            <template x-for="lang in filtered" :key="lang.code">
                <button type="button" x-on:click="choose(lang.code)" class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left transition hover:bg-surface">
                    <span class="text-lg leading-none" x-text="lang.flag"></span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm font-semibold text-ink" x-text="lang.name"></span>
                        <span class="block truncate text-xs text-muted" x-text="lang.english"></span>
                    </span>
                    <span class="material-symbols-outlined text-[18px] text-primary" x-show="lang.code === current.code">check</span>
                </button>
            </template>

            <p x-show="filtered.length === 0" class="px-3 py-6 text-center text-xs text-muted">Bahasa tidak ditemukan.</p>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            function languageSwitcher(languages, popular) {
                return {
                    open: false,
                    q: '',
                    languages: languages,
                    popular: popular,
                    current: { code: @json(config('translate.source', 'id')), flag: '🌐', name: '', english: '' },
                    init() {
                        this.sync();
                        window.addEventListener('google-translate:change', () => this.sync());
                        window.addEventListener('google-translate:ready', () => this.sync());
                    },
                    sync() {
                        const code = (window.currentTranslateLang && window.currentTranslateLang()) ||
                            @json(config('translate.source', 'id'));
                        this.current = this.languages.find((l) => l.code === code) ||
                            { code: code, flag: '🌐', name: code, english: code };
                    },
                    get badge() {
                        const c = this.current.code || '';
                        return c.includes('-') ? c.split('-')[0].toUpperCase() : c.toUpperCase();
                    },
                    get filtered() {
                        const q = this.q.trim().toLowerCase();
                        if (!q) return this.languages;
                        return this.languages.filter((l) =>
                            l.name.toLowerCase().includes(q) ||
                            l.english.toLowerCase().includes(q) ||
                            l.code.toLowerCase().includes(q)
                        );
                    },
                    toggle() {
                        this.open = !this.open;
                        if (this.open) {
                            this.$nextTick(() => this.$refs.search && this.$refs.search.focus());
                        }
                    },
                    choose(code) {
                        this.open = false;
                        window.applyLanguage(code);
                    },
                };
            }
        </script>
    @endpush
@endonce
