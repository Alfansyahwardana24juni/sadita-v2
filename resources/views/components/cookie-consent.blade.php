<div id="cookie-consent" class="fixed bottom-24 sm:bottom-28 left-1/2 -translate-x-1/2 z-[100] w-[calc(100%-32px)] max-w-[420px] rounded-2xl bg-ink p-4 text-white shadow-2xl transition-all duration-500 translate-y-8 opacity-0 pointer-events-none">
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-amber text-[28px]">cookie</span>
        <div class="flex-1">
            <p class="text-xs leading-5">SADITA menggunakan cookie/session untuk menyimpan keranjang belanja dan preferensi Anda agar tetap aman saat Anda kembali.</p>
            <div class="mt-3 flex gap-2">
                <button onclick="acceptCookies()" class="flex-1 rounded-lg bg-primary py-2 text-xs font-bold text-white transition-transform active:scale-95">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<script>
    function acceptCookies() {
        localStorage.setItem('sadita_cookie_consent', 'true');
        const el = document.getElementById('cookie-consent');
        el.classList.add('translate-y-8', 'opacity-0');
        setTimeout(() => el.remove(), 500);
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!localStorage.getItem('sadita_cookie_consent')) {
            const el = document.getElementById('cookie-consent');
            if (el) {
                setTimeout(() => {
                    el.classList.remove('translate-y-8', 'opacity-0', 'pointer-events-none');
                }, 1500);
            }
        } else {
            document.getElementById('cookie-consent')?.remove();
        }
    });
</script>
