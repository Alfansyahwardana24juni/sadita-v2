@props([
    'product',
    'buyUrl' => null,
    'buyLabel' => 'Beli Produk',
    'triggerClass' => 'mt-3 flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-primary bg-white text-sm font-bold text-primary hover:bg-primary/5 active:scale-95 transition-all',
])

@php
    $hasBrochures = false;
    $brochureLinks = [];

    // Helper untuk mengubah Google Drive link ke direct image link
    $getDriveImage = function($url) {
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://lh3.googleusercontent.com/d/' . $matches[1];
        }
        if (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://lh3.googleusercontent.com/d/' . $matches[1];
        }
        return $url;
    };

    if (is_array($product->brochures) && count($product->brochures) > 0) {
        $hasBrochures = true;
        foreach($product->brochures as $b) {
            $brochureLinks[] = $getDriveImage($b['url'] ?? '');
        }
    } elseif ($product->brochure_url) {
        $hasBrochures = true;
        $brochureLinks[] = $product->brochure_url;
    }
@endphp

@if($hasBrochures)
    <button type="button" data-brochure-open
        class="{{ $triggerClass }}">
        <span class="material-symbols-outlined text-[18px]">description</span>
        Lihat Brosur Produk
    </button>

    <div data-brochure-modal hidden
        class="fixed inset-0 z-[130] flex items-center justify-center bg-black/60 p-4 opacity-0 transition-opacity duration-200"
        aria-modal="true" role="dialog">
        <div class="relative flex max-h-[90vh] w-full max-w-[440px] flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-line px-4 py-3">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-muted">Brosur Produk</p>
                    <h3 class="truncate text-sm font-black text-primary">{{ $product->name }}</h3>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" data-brochure-close
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-primary hover:bg-surface" aria-label="Tutup">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto bg-surface">
                @foreach($brochureLinks as $link)
                    <img src="{{ $link }}" alt="Brosur {{ $product->name }}"
                        class="block w-full"
                        onerror="this.onerror=null;this.replaceWith(Object.assign(document.createElement('div'),{className:'p-8 text-center text-sm text-muted',textContent:'Gambar brosur tidak dapat dimuat. Buka di tab baru.'}));">
                @endforeach
            </div>

            @if($buyUrl)
                <div class="border-t border-line p-3">
                    <a href="{{ $buyUrl }}"
                        class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                        {{ $buyLabel }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    @once
        @push('scripts')
            <script>
                document.addEventListener('click', (e) => {
                    const openBtn = e.target.closest('[data-brochure-open]');
                    if (openBtn) {
                        const modal = openBtn.parentElement.querySelector('[data-brochure-modal]')
                            || openBtn.closest('section, main, body').querySelector('[data-brochure-modal]');
                        if (!modal) return;
                        modal.hidden = false;
                        requestAnimationFrame(() => modal.classList.remove('opacity-0'));
                        document.body.style.overflow = 'hidden';
                        return;
                    }
                    const closeEl = e.target.closest('[data-brochure-close]');
                    const backdrop = e.target.matches('[data-brochure-modal]') ? e.target : null;
                    const modal = closeEl ? closeEl.closest('[data-brochure-modal]') : backdrop;
                    if (modal) {
                        modal.classList.add('opacity-0');
                        document.body.style.overflow = '';
                        setTimeout(() => { modal.hidden = true; }, 200);
                    }
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    document.querySelectorAll('[data-brochure-modal]:not([hidden])').forEach((modal) => {
                        modal.classList.add('opacity-0');
                        document.body.style.overflow = '';
                        setTimeout(() => { modal.hidden = true; }, 200);
                    });
                });
            </script>
        @endpush
    @endonce
@endif
