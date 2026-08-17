<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SADITA Toko - Belanja Produk Kesehatan Hewan')</title>
    <meta name="description" content="@yield('description', 'Beli produk kesehatan hewan dan peternakan berkualitas langsung dari gudang SADITA terdekat.')">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite('resources/css/app.css')
</head>
<body class="bg-background text-on-surface flex justify-center min-h-screen">
    <div class="w-full max-w-container bg-background min-h-screen pb-32 relative">
        @include('layouts.components.navbar-toko')
        
        <main class="pt-20">
            @yield('content')
        </main>
        
        @include('layouts.components.footer')
    </div>
    
    @vite('resources/js/app.js')
</body>
</html>
