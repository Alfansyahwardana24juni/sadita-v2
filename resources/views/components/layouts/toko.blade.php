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
    @include('partials.toast')

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
            const offlineIndicator = document.getElementById('offline-indicator');
            function updateOfflineState() {
                if (!offlineIndicator) return;
                offlineIndicator.classList.toggle('hidden', navigator.onLine);
            }
            window.addEventListener('online', updateOfflineState);
            window.addEventListener('offline', updateOfflineState);
            updateOfflineState();
        })();
    </script>
    @include('partials.cart-script')
    @stack('scripts')
    @livewireScripts
</body>
</html>

