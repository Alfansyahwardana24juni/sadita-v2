<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SADITA Toko' }}</title>

    <meta
        name="description"
        content="{{ $description ?? 'Toko SADITA - Belanja produk kesehatan hewan.' }}"
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    >
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    @livewireStyles
</head>

<body class="m-0 min-h-screen bg-slate-100 text-slate-900">
    <div id="offline-indicator" class="fixed left-1/2 top-3 z-[110] hidden w-[calc(100%-24px)] max-w-[488px] -translate-x-1/2 rounded-xl bg-amber px-4 py-2 text-center text-xs font-bold text-white shadow-lg">
        Anda sedang offline. Beberapa fitur mungkin tidak tersedia.
    </div>
    <div id="global-toast" class="fixed left-1/2 top-14 z-[120] hidden w-[calc(100%-24px)] max-w-[488px] -translate-x-1/2 rounded-xl bg-ink px-4 py-3 text-sm font-semibold text-white opacity-0 transition-opacity duration-300 shadow-xl"></div>

    <div class="main-container-responsive">
        <main class="page-container">
            {{ $slot }}
        </main>
        <div class="fixed bottom-0 sm:bottom-4 left-1/2 -translate-x-1/2 w-full max-w-[420px] sm:max-w-[540px] z-[60]">
            <x-toko-bottom-nav />
        </div>

        <x-saditacare-fab />
        <x-cookie-consent />
    </div>
    <script>
        (() => {
            const toast = document.getElementById('global-toast');
            const offlineIndicator = document.getElementById('offline-indicator');
            let toastTimer = null;

            window.saditaNotify = function(message, type = 'info') {
                if (!message) return;
                
                // Create toast container if it doesn't exist
                let container = document.getElementById('sadita-toast-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'sadita-toast-container';
                    container.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-[9999] flex flex-col items-center gap-2 pointer-events-none w-full px-4 sm:max-w-md';
                    document.body.appendChild(container);
                }

                // Create toast element
                const toast = document.createElement('div');
                
                // Style based on type (Modern dark theme by default, or specific colors)
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
                    <span class="material-symbols-outlined ${iconColor} text-[20px] shrink-0 translate-y-[0.5px]">${icon}</span>
                    <p class="text-[13px] font-medium leading-relaxed tracking-wide text-slate-100 flex-1">${message}</p>
                    <button class="shrink-0 text-slate-400 hover:text-white transition-colors p-1" onclick="this.parentElement.remove()">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                `;

                container.appendChild(toast);

                // Animate in
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        toast.classList.remove('opacity-0', '-translate-y-8');
                        toast.classList.add('opacity-100', 'translate-y-0');
                    });
                });

                // Animate out and remove after 3 seconds
                setTimeout(() => {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', '-translate-y-4');
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 400); // Wait for transition
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
    @include('partials.cart-script')
    @stack('scripts')
    @livewireScripts
</body>
</html>

