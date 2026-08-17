@extends('layouts.toko')

@section('title', 'Gudang Toko | SADITA Toko')

@section('content')
<section class="px-5 py-8">
    <h2 class="text-2xl font-bold mb-6">Pilih Gudang Terdekat</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="/toko/katalog?gudang=bogor" class="product-card p-6 hover:shadow-lg transition-shadow">
            <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                <img src="https://images.unsplash.com/photo-1599850804949-1b8d55dd8a95?w=400&h=300&fit=crop" alt="Gudang Bogor" class="w-full h-full object-cover">
            </div>
            <h3 class="text-xl font-bold text-primary mb-2">Gudang Indonesia Barat</h3>
            <p class="text-sm text-gray-600 mb-3">📍 Bogor, Jawa Barat</p>
            <p class="text-xs text-gray-500">Pengiriman dari Bogor ke seluruh Jawa, Sumatera, dan Bali</p>
        </a>
        <a href="/toko/katalog?gudang=makassar" class="product-card p-6 hover:shadow-lg transition-shadow">
            <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                <img src="https://images.unsplash.com/photo-1601181286170-0a6cd7accc03?w=400&h=300&fit=crop" alt="Gudang Makassar" class="w-full h-full object-cover">
            </div>
            <h3 class="text-xl font-bold text-primary mb-2">Gudang Indonesia Timur</h3>
            <p class="text-sm text-gray-600 mb-3">📍 Makassar, Sulawesi Selatan</p>
            <p class="text-xs text-gray-500">Pengiriman dari Makassar ke kawasan Timur Indonesia</p>
        </a>
    </div>
</section>
@endsection
