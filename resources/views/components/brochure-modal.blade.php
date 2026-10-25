@props([
    'product',
    'buyUrl' => null,
    'buyLabel' => 'Beli di Toko Sadita',
    'triggerClass' => 'mt-3 flex h-11 w-full items-center justify-center gap-2 rounded-xl border border-primary bg-white text-sm font-bold text-primary hover:bg-primary/5 active:scale-95 transition-all',
])

@php
    $modalId = 'brochure-modal-' . $product->id . '-' . \Illuminate\Support\Str::random(6);
    $hasBrochures = false;
    $brochureLinks = [];

    // Helper untuk mengubah Google Drive link ke direct image link
    $getDriveImage = function($url) {
        if (!is_string($url) || trim($url) === '') return null;
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://lh3.googleusercontent.com/d/' . $matches[1];
        }
        if (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://lh3.googleusercontent.com/d/' . $matches[1];
        }
        return $url;
    };

    if (is_array($product->brochures) && count($product->brochures) > 0) {
        foreach($product->brochures as $b) {
            $url = $getDriveImage($b['url'] ?? '');
            if ($url) {
                $brochureLinks[] = [
                    'title' => $b['name'] ?? null,
                    'url' => $url,
                ];
            }
        }
    }

    if (empty($brochureLinks) && $product->brochure_url) {
        $brochureLinks[] = [
            'title' => 'Brosur ' . $product->name,
            'url' => $product->brochure_url,
        ];
    }

    $hasBrochures = count($brochureLinks) > 0;
@endphp

@if($hasBrochures)
    {{-- Trigger Button --}}
    <button type="button" data-brochure-open="{{ $modalId }}"
        class="{{ $triggerClass }}">
        <span class="material-symbols-outlined text-[19px]">description</span>
        <span>Lihat Brosur Produk</span>
    </button>

    {{-- Fullscreen Responsive Modal Dialog (Auto-teleported to body) --}}
    <div id="{{ $modalId }}" data-brochure-modal hidden
        class="fixed inset-0 z-[9999] flex flex-col justify-end sm:justify-center items-center bg-black/80 backdrop-blur-sm p-0 sm:p-4 opacity-0 transition-opacity duration-200"
        aria-modal="true" role="dialog" aria-labelledby="{{ $modalId }}-title">
        
        <div class="modal-card relative flex flex-col w-full max-w-full sm:max-w-2xl h-[92vh] sm:h-[88vh] max-h-[92dvh] sm:max-h-[88dvh] rounded-t-[28px] sm:rounded-3xl bg-white shadow-2xl overflow-hidden transition-transform duration-300 transform translate-y-6 sm:translate-y-0">
            
            {{-- Mobile Swipe / Tap to Close Bar --}}
            <div class="sm:hidden w-full pt-2.5 pb-1 flex justify-center bg-white shrink-0 cursor-pointer" data-brochure-close title="Ketuk untuk menutup">
                <div class="w-12 h-1.5 rounded-full bg-slate-300 active:bg-slate-400"></div>
            </div>

            {{-- Sticky Header --}}
            <div class="flex items-center justify-between border-b border-line px-4 py-3 sm:px-6 bg-white shrink-0 z-10">
                <div class="min-w-0 pr-3">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[18px]">description</span>
                        <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.14em] text-muted">Brosur Produk</p>
                    </div>
                    <h3 id="{{ $modalId }}-title" class="truncate text-sm sm:text-base font-black text-ink mt-0.5">
                        {{ $product->name }}
                    </h3>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    {{-- Tombol Buka Tab Baru / Ukuran Asli --}}
                    @if(count($brochureLinks) > 0)
                        <a href="{{ $brochureLinks[0]['url'] }}" target="_blank" rel="noopener noreferrer"
                           title="Buka brosur ukuran asli di tab baru"
                           class="flex h-10 px-3 items-center justify-center gap-1.5 rounded-xl bg-surface hover:bg-slate-200 text-xs font-bold text-slate-700 hover:text-ink active:scale-95 transition-all border border-line/60 shadow-sm"
                           aria-label="Buka ukuran asli">
                            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                            <span class="hidden sm:inline">Ukuran Asli</span>
                        </a>
                    @endif

                    {{-- Big Touch-Friendly Close Button --}}
                    <button type="button" data-brochure-close
                        class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 hover:text-ink active:scale-90 transition-all border border-line/40 shadow-sm"
                        aria-label="Tutup Brosur">
                        <span class="material-symbols-outlined text-[24px] font-bold">close</span>
                    </button>
                </div>
            </div>

            {{-- Scrollable Body --}}
            <div class="modal-scroll-body flex-1 overflow-y-auto overscroll-contain bg-slate-100/90 p-3 sm:p-5 space-y-4 touch-pan-y" style="-webkit-overflow-scrolling: touch; overscroll-behavior: contain;">
                {{-- Hint banner --}}
                <div class="flex items-center justify-between text-[11px] text-muted font-medium px-1">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">swipe_vertical</span>
                        Gulir ke bawah untuk membaca seluruh brosur
                    </span>
                    @if(count($brochureLinks) > 1)
                        <span class="font-bold text-primary bg-primary/10 px-2 py-0.5 rounded text-[10px]">
                            {{ count($brochureLinks) }} Halaman
                        </span>
                    @endif
                </div>

                {{-- Brochure Images --}}
                @foreach($brochureLinks as $index => $item)
                    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md border border-line/80">
                        @if(!empty($item['title']) || count($brochureLinks) > 1)
                            <div class="flex items-center justify-between border-b border-line/40 px-3.5 py-2.5 bg-surface/50 text-xs font-bold text-slate-700">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-primary">auto_stories</span>
                                    <span>{{ $item['title'] ?: ('Halaman ' . ($index + 1)) }}</span>
                                </span>
                                <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-0.5">
                                    Buka Foto <span class="material-symbols-outlined text-[14px]">arrow_outward</span>
                                </a>
                            </div>
                        @endif

                        <div class="p-2 sm:p-3 bg-white flex justify-center">
                            <img src="{{ $item['url'] }}" alt="Brosur {{ $product->name }} {{ $item['title'] ?: ($index + 1) }}"
                                class="block w-full h-auto object-contain mx-auto rounded-lg select-none"
                                loading="lazy"
                                onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\'p-8 text-center text-sm text-muted\'><span class=\'material-symbols-outlined text-4xl text-amber mb-2\'>broken_image</span><p class=\'font-bold text-ink\'>Gambar brosur tidak dapat dimuat</p><a href=\'{{ $item['url'] }}\' target=\'_blank\' class=\'mt-3 inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline\'>Buka tautan langsung <span class=\'material-symbols-outlined text-[14px]\'>open_in_new</span></a></div>';">
                        </div>
                    </div>
                @endforeach

                <div class="h-2"></div>
            </div>

            {{-- Sticky Footer --}}
            <div class="flex items-center gap-2 border-t border-line px-4 py-3 sm:px-6 bg-white shrink-0 z-10 shadow-[0_-4px_16px_rgba(0,0,0,0.04)]">
                {{-- Tombol Tutup Khusus untuk HP & Desktop --}}
                <button type="button" data-brochure-close
                    class="flex-1 sm:flex-initial sm:min-w-[120px] flex h-11 items-center justify-center gap-1.5 rounded-xl border border-line bg-surface hover:bg-slate-200 px-4 text-xs sm:text-sm font-bold text-ink active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    <span>Tutup</span>
                </button>

                @if($buyUrl)
                    <a href="{{ $buyUrl }}"
                        class="flex-[2] sm:flex-1 flex h-11 items-center justify-center gap-2 rounded-xl bg-primary px-4 text-xs sm:text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all truncate text-center">
                        <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                        <span>{{ $buyLabel }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Teleportation and Event Binding Script --}}
    <script>
        (function() {
            const modalId = '{{ $modalId }}';
            const modal = document.getElementById(modalId);
            if (!modal) return;

            // Teleport modal directly to document.body so it escapes all parent containing blocks
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }

            function openModal() {
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
                modal.hidden = false;
                document.body.style.overflow = 'hidden';

                const scrollBody = modal.querySelector('.modal-scroll-body');
                if (scrollBody) scrollBody.scrollTop = 0;

                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0');
                    const card = modal.querySelector('.modal-card');
                    if (card) {
                        card.classList.remove('translate-y-6');
                        card.classList.add('translate-y-0');
                    }
                });
            }

            function closeModal() {
                modal.classList.add('opacity-0');
                const card = modal.querySelector('.modal-card');
                if (card) {
                    card.classList.remove('translate-y-0');
                    card.classList.add('translate-y-6');
                }
                document.body.style.overflow = '';
                setTimeout(() => {
                    modal.hidden = true;
                }, 200);
            }

            // Bind open trigger button
            const openBtn = document.querySelector(`[data-brochure-open="${modalId}"]`);
            if (openBtn) {
                openBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openModal();
                });
            }

            // Bind close triggers inside modal
            modal.querySelectorAll('[data-brochure-close]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    closeModal();
                });
            });

            // Click outside on backdrop
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.hidden) {
                    closeModal();
                }
            });
        })();
    </script>
@endif
