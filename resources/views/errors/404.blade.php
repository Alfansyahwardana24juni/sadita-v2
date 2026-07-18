<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan</title>
    @vite(['resources/css/app.css'])
</head>
<body class="m-0 min-h-screen bg-slate-100 text-slate-900">
    <main class="main-container-responsive flex items-center justify-center px-6 text-center">
        <section>
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Error 404</p>
            <h1 class="mt-2 text-3xl font-black text-primary">Halaman tidak ditemukan</h1>
            <p class="mt-3 text-sm leading-6 text-muted">Maaf, halaman yang Anda cari tidak tersedia atau sudah dipindahkan.</p>
            <div class="mt-6 grid grid-cols-1 gap-3">
                <a href="{{ route('home') }}" class="inline-flex h-11 items-center justify-center rounded-xl bg-primary px-5 text-sm font-bold text-white">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('toko.home') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-primary px-5 text-sm font-bold text-primary">
                    Buka Toko SADITA
                </a>
            </div>
        </section>
    </main>
</body>
</html>
