<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SADITA' }}</title>

    <meta name="description" content="{{ $description ?? 'SADITA - Solusi Sehat Ternak Anda. Kami menyediakan obat-obatan, vitamin, dan konsultasi kesehatan hewan ternak terlengkap.' }}">

    <!-- OpenGraph (Facebook/WhatsApp) -->
    <meta property="og:title" content="{{ $title ?? 'SADITA - Solusi Sehat Ternak Anda' }}">
    <meta property="og:description" content="{{ $description ?? 'SADITA - Solusi Sehat Ternak Anda. Kami menyediakan obat-obatan, vitamin, dan konsultasi kesehatan hewan ternak terlengkap.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/logo sadita.png') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="website">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'SADITA - Solusi Sehat Ternak Anda' }}">
    <meta name="twitter:description" content="{{ $description ?? 'SADITA - Solusi Sehat Ternak Anda. Kami menyediakan obat-obatan, vitamin, dan konsultasi kesehatan hewan ternak terlengkap.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/logo sadita.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    @livewireStyles
    @stack('styles')
    <style>
        /* ── Hide modern Google Translate toolbar ── */
        .VIpgJd-ZVi9od-ORHb-OEVmcd, 
        .goog-te-banner-frame,
        iframe.skiptranslate,
        .skiptranslate > iframe,
        .goog-te-balloon-frame,
        #goog-gt-tt { display: none !important; visibility: hidden !important; }
        body { top: 0 !important; position: static !important; }
        .goog-tooltip, .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; box-shadow: none !important; }
        
        /* ── Style Google Translate Widget ── */
        #google_translate_element .goog-te-gadget {
            font-size: 0 !important; 
            color: transparent !important;
            display: flex;
            align-items: center;
        }
        #google_translate_element .goog-te-gadget .goog-te-combo {
            appearance: none;
            -webkit-appearance: none;
            background: transparent url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 4px center;
            background-size: 14px 14px;
            border: none;
            padding: 4px 24px 4px 4px;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            font-family: Inter, sans-serif;
            cursor: pointer;
            outline: none;
            margin: 0;
            height: 32px;
        }
        #google_translate_element .goog-te-gadget .goog-te-combo:hover {
            border-color: #cbd5e1;
        }
        #google_translate_element .goog-te-gadget .goog-te-combo:focus {
            border-color: #800000;
        }
        #google_translate_element .goog-logo-link { display: none !important; }
        #google_translate_element .goog-te-gadget img { display: none !important; }
    </style>
</head>

<body class="m-0 min-h-screen bg-[#F5F5F5] text-slate-900">

    <div id="offline-indicator" class="fixed left-1/2 top-3 z-[110] hidden w-[calc(100%-24px)] max-w-[488px] -translate-x-1/2 rounded-xl bg-amber px-4 py-2 text-center text-xs font-bold text-white shadow-lg">
        Anda sedang offline. Beberapa fitur mungkin tidak tersedia.
    </div>

    <div class="main-container-responsive bg-white">
        <main class="page-container">
            {{ $slot }}
        </main>

        <div class="fixed bottom-0 sm:bottom-4 left-1/2 -translate-x-1/2 w-full max-w-[420px] sm:max-w-[540px] z-[60]">
            <x-bottom-nav />
        </div>

        <x-saditacare-fab />
        <x-cookie-consent />
    </div>

    <script>
        (() => {
            const offlineIndicator = document.getElementById('offline-indicator');

            window.saditaNotify = function(message, type = 'info') {
                if (!message) return;
                
                let container = document.getElementById('sadita-toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'sadita-toast-container';
                    container.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex flex-col items-center gap-2 pointer-events-none w-full px-4 sm:max-w-md';
                    document.body.appendChild(container);
                }

                const toast = document.createElement('div');
                let icon = 'info';
                let iconColor = 'text-blue-400';
                
                if (type === 'success') {
                    icon = 'check_circle';
                    iconColor = 'text-green-400';
                } else if (type === 'error') {
                    icon = 'error';
                    iconColor = 'text-red-400';
                }

                toast.className = `flex items-center gap-3 w-full bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-2xl ring-1 ring-white/10 transform transition-all duration-400 opacity-0 -translate-y-8 pointer-events-auto`;
                
                toast.innerHTML = `
                    <span class="material-symbols-outlined notranslate ${iconColor} text-[20px] shrink-0 translate-y-[0.5px]">${icon}</span>
                    <p class="text-[13px] font-medium leading-relaxed tracking-wide text-slate-100 flex-1">${message}</p>
                    <button class="shrink-0 text-slate-400 hover:text-white transition-colors p-1" onclick="this.parentElement.remove()">
                        <span class="material-symbols-outlined notranslate text-[16px]">close</span>
                    </button>
                `;

                container.appendChild(toast);

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        toast.classList.remove('opacity-0', '-translate-y-8');
                        toast.classList.add('opacity-100', 'translate-y-0');
                    });
                });

                setTimeout(() => {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', '-translate-y-4');
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 400);
                }, 3000);
            };

            function updateOfflineState() {
                if (!offlineIndicator) return;
                offlineIndicator.classList.toggle('hidden', navigator.onLine);
            }

            window.addEventListener('online', updateOfflineState);
            window.addEventListener('offline', updateOfflineState);
            updateOfflineState();

            @if(session('success'))
                window.saditaNotify(@json(session('success')), 'success');
            @endif
            @if(session('error'))
                window.saditaNotify(@json(session('error')), 'error');
            @endif
        })();
    </script>
    <div id="google_translate_element" style="display:none;"></div>
    <script>
        // Prevent Google Translate from translating material icons
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.material-symbols-outlined').forEach(function(icon) {
                icon.classList.add('notranslate');
                icon.setAttribute('translate', 'no');
            });
        });

        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'id,en,zh-CN,ar,ms,af,sq,am,hy,az,eu,be,bn,bs,bg,ca,ceb,zh-TW,co,hr,cs,da,nl,eo,et,fi,fr,fy,gl,ka,de,el,gu,ht,ha,haw,iw,hi,hmn,hu,is,ig,ga,it,ja,jw,kn,kk,km,ko,ku,ky,lo,la,lv,lt,lb,mk,mg,ml,mt,mi,mr,mn,my,ne,no,ny,ps,fa,pl,pt,pa,ro,ru,sm,gd,sr,st,sn,sd,si,sk,sl,so,es,su,sv,tl,tg,ta,te,th,tr,uk,ur,uz,vi,cy,xh,yi,yo,zu',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>