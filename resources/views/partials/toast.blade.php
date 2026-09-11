{{-- Toast global SADITA: slide-up dari bawah, ikon per aksi, auto-dismiss. --}}
<div id="sadita-toast-wrap" class="pointer-events-none fixed inset-x-0 bottom-0 z-[140] flex justify-center px-4 pb-[88px] sm:pb-6">
    <div id="sadita-toast"
        class="pointer-events-auto flex max-w-[420px] translate-y-4 items-center gap-3 rounded-2xl bg-ink/95 px-4 py-3 text-sm font-semibold text-white opacity-0 shadow-[0_12px_40px_rgba(0,0,0,0.28)] backdrop-blur transition-all duration-300 ease-out"
        role="status" aria-live="polite" hidden>
        <span id="sadita-toast-icon" class="material-symbols-outlined text-[20px]">check_circle</span>
        <span id="sadita-toast-msg" class="leading-5"></span>
    </div>
</div>

<script>
    (() => {
        if (window.saditaNotify) return;

        const wrap = document.getElementById('sadita-toast');
        const iconEl = document.getElementById('sadita-toast-icon');
        const msgEl = document.getElementById('sadita-toast-msg');
        let hideTimer = null;
        let removeTimer = null;

        const THEME = {
            success:  { icon: 'check_circle',          bg: 'bg-moss/95' },
            cart:     { icon: 'add_shopping_cart',     bg: 'bg-moss/95' },
            remove:   { icon: 'delete',                bg: 'bg-ink/95' },
            checkout: { icon: 'shopping_cart_checkout', bg: 'bg-primary/95' },
            error:    { icon: 'error',                 bg: 'bg-error/95' },
            info:     { icon: 'info',                  bg: 'bg-ink/95' },
        };
        const BG_CLASSES = Object.values(THEME).map(t => t.bg);

        window.saditaNotify = function (message, type = 'info') {
            if (!wrap || !message) return;
            const theme = THEME[type] || THEME.info;

            iconEl.textContent = theme.icon;
            msgEl.textContent = message;
            BG_CLASSES.forEach(c => wrap.classList.remove(c));
            wrap.classList.add(theme.bg);

            if (hideTimer) clearTimeout(hideTimer);
            if (removeTimer) clearTimeout(removeTimer);

            wrap.hidden = false;
            requestAnimationFrame(() => {
                wrap.classList.remove('opacity-0', 'translate-y-4');
            });

            hideTimer = setTimeout(() => {
                wrap.classList.add('opacity-0', 'translate-y-4');
                removeTimer = setTimeout(() => { wrap.hidden = true; }, 320);
            }, 2400);
        };
    })();

    @if(session('success'))
        window.saditaNotify(@json(session('success')), @json(session('toast_type', 'success')));
    @endif
    @if(session('error'))
        window.saditaNotify(@json(session('error')), 'error');
    @endif
</script>
