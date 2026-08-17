@extends('layouts.app')

@section('title', 'SADITA - Solusi Sehat Ternak Anda')

@section('content')
<section class="mt-4 relative w-full h-80 overflow-hidden rounded-lg">
    <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=800&h=400&fit=crop" alt="Hero">
    <div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-transparent flex flex-col justify-center px-8">
        <h1 class="text-display-sm text-white leading-tight mb-4 max-w-xs">SOLUSI SEHAT TERNAK ANDA</h1>
        <p class="text-body-md text-white/90 max-w-xs mb-6">Menyediakan produk kesehatan hewan berkualitas tinggi untuk performa maksimal.</p>
        <a href="/toko" class="w-fit px-6 py-2.5 bg-white text-primary font-label-lg rounded-xl shadow-lg active:scale-95 transition-transform">Eksplor Produk</a>
    </div>
</section>

<!-- Kategori -->
<section class="py-8 px-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-headline-md text-primary">Kategori Produk</h2>
        <a href="/kategori" class="text-primary font-label-lg">Lihat Semua</a>
    </div>
    <div class="grid grid-cols-4 gap-3">
        @php
            $categories = [
                ['icon' => 'vaccines', 'name' => 'Antibiotik'],
                ['icon' => 'medication', 'name' => 'Vitamin'],
                ['icon' => 'pets', 'name' => 'Desinfektan'],
                ['icon' => 'agriculture', 'name' => 'Premix'],
            ];
        @endphp
        @foreach($categories as $cat)
            <a href="/kategori/{{ strtolower($cat['name']) }}" class="flex flex-col items-center gap-2 group cursor-pointer">
                <div class="w-14 h-14 rounded-2xl bg-white border border-gray-100 shadow-sm flex items-center justify-center group-hover:bg-primary transition-colors">
                    <span class="material-symbols-outlined text-primary group-hover:text-white">{{ $cat['icon'] }}</span>
                </div>
                <span class="font-label-sm text-center text-xs">{{ $cat['name'] }}</span>
            </a>
        @endforeach
    </div>
</section>

<!-- Stats -->
<section class="py-8 px-5 bg-primary text-white rounded-lg mx-5 mb-8">
    <h2 class="font-headline-md text-center mb-6">Tentang Kami</h2>
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10 flex flex-col items-center text-center">
            <span class="text-2xl font-bold">10+</span>
            <span class="font-label-sm text-white/80 text-xs">Tahun Pengalaman</span>
        </div>
        <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10 flex flex-col items-center text-center">
            <span class="text-2xl font-bold">200+</span>
            <span class="font-label-sm text-white/80 text-xs">Produk</span>
        </div>
        <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10 flex flex-col items-center text-center">
            <span class="text-2xl font-bold">500+</span>
            <span class="font-label-sm text-white/80 text-xs">Klien</span>
        </div>
        <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10 flex flex-col items-center text-center">
            <span class="text-2xl font-bold">100T</span>
            <span class="font-label-sm text-white/80 text-xs">Output/Bulan</span>
        </div>
    </div>
</section>

<!-- Artikel -->
<section class="py-8 px-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-headline-md text-primary">Artikel Terbaru</h2>
        <a href="/artikel" class="text-primary font-label-lg">Baca Semua</a>
    </div>
    <div class="space-y-4">
        <a href="#" class="flex gap-4 bg-white p-3 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <img class="w-24 h-24 object-cover rounded-xl" src="https://images.unsplash.com/photo-1576091160519-112ba08d1232?w=200&h=200&fit=crop" alt="Article">
            <div class="flex-1 flex flex-col justify-center">
                <h3 class="font-headline-md text-sm mb-1">Pencegahan Flu Burung</h3>
                <p class="text-xs text-gray-500 line-clamp-2 mb-2">Langkah strategis menjaga kebersihan kandang di musim penghujan...</p>
                <span class="text-[10px] text-primary font-bold uppercase tracking-wider">Kesehatan</span>
            </div>
        </a>
        <a href="#" class="flex gap-4 bg-white p-3 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <img class="w-24 h-24 object-cover rounded-xl" src="https://images.unsplash.com/photo-1574943320219-553eb213f72d?w=200&h=200&fit=crop" alt="Article">
            <div class="flex-1 flex flex-col justify-center">
                <h3 class="font-headline-md text-sm mb-1">Manajemen Pakan Efisien</h3>
                <p class="text-xs text-gray-500 line-clamp-2 mb-2">Optimalkan pertumbuhan ternak dengan campuran premix yang tepat...</p>
                <span class="text-[10px] text-primary font-bold uppercase tracking-wider">Nutrisi</span>
            </div>
        </a>
    </div>
</section>
@endsection
