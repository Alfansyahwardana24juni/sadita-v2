<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'SADITA - Solusi Sehat Ternak Anda' }}</title>
    <meta name="description" content="{{ $description ?? 'Partner produk kesehatan hewan dan peternakan untuk peternak Indonesia.' }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: Inter, sans-serif; }
        .material-symbols-outlined { font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24; }
    </style>
    
    @stack('styles')
    @livewireStyles
</head>
<body class="h-[100dvh] overflow-hidden bg-[#F5F5F5] text-ink">
    <div class="main-container-responsive bg-white">
        <!-- Content -->
        <main class="page-container">
            {{ $slot }}
        </main>
        
        <!-- Bottom Navigation -->
        <div class="shrink-0 relative z-[60]">
            @include('layouts.partials.navbar')
        </div>
    </div>
    
    <x-saditacare-fab />

    @stack('scripts')
    @livewireScripts
</body>
</html>
