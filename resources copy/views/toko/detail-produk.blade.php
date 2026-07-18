@extends('layouts.toko')

@section('title', 'Cyprotil - Produk Kesehatan Unggas | SADITA Toko')

@section('content')
@php
    $product = $product ?? (object) [
        'name' => 'Cyprotil',
        'slug' => 'cyprotil',
        'subtitle' => 'Antibiotik Spektrum Luas untuk Unggas',
        'description' => 'Cyprotil adalah antibiotik spektrum luas yang efektif digunakan untuk mengatasi berbagai penyakit bakteri pada unggas. Produk ini telah terbukti meningkatkan performa ternak dengan mengurangi kejadian penyakit infeksi bakteri.',
        'benefits' => [
            'Spektrum luas untuk berbagai jenis bakteri gram positif dan gram negatif',
            'Absorpsi cepat dan efektif dalam tubuh ternak',
            'Meningkatkan feed conversion ratio (FCR)',
            'Aman untuk semua jenis unggas',
            'Harga kompetitif dengan kualitas premium',
        ],
        'specifications' => [
            'Nama Produk' => 'Cyprotil (Cyprofloxacin)',
            'Kandungan Aktif' => 'Cyprofloxacin 10%',
            'Bentuk' => 'Powder',
            'Kemasan' => '25 kg/bag',
            'Dosis' => '2-3 gram/liter air minum selama 3-5 hari',
            'Indikasi' => 'Kolibaksilosis, Pasteurellosis, Salmonellosis',
            'Garansi' => '100% original dari produsen',
        ],
        'price' => 125000,
        'original_price' => 156000,
        'discount_percent' => 20,
        'stock' => 1850,
        'sold_count' => 3200,
        'condition' => 'Baru',
        'image' => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde0b?w=500&h=500&fit=crop',
        'gallery' => [
            'https://images.unsplash.com/photo-1587854692152-cbe660dbde0b?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1631217314997-dc41ceaa2014?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1576091160640-112b8217b85b?w=500&h=500&fit=crop',
        ],
        'seller_name' => 'PT. Satwa Medika Utama',
        'warehouse' => 'Bogor, Jawa Barat',
        'review_count' => 248,
        'category' => (object) ['name' => 'Antibiotik', 'slug' => 'antibiotik'],
        'reviews' => collect([
            (object) ['customer_name' => 'Budi Santoso', 'rating' => 5, 'comment' => 'Produk sangat bagus, ayam saya cepat pulih dari penyakit. Harga terjangkau dan kualitas terjamin.', 'reviewed_at' => now()->subWeeks(2)],
            (object) ['customer_name' => 'Siti Nurhaliza', 'rating' => 5, 'comment' => 'Efektif untuk mengatasi penyakit pada ternak. Rekomendasi dari tetangga dan tidak mengecewakan!', 'reviewed_at' => now()->subMonth()],
        ]),
    ];
    $gallery = $product->gallery ?: [$product->image];
    $discountPercent = $product->discount_percent ?? 0;
    $saving = $product->original_price ? $product->original_price - $product->price : 0;
@endphp

<!-- Breadcrumb -->
<nav class="px-5 py-3 text-sm text-gray-600">
    <a href="/toko" class="text-primary hover:underline">Beranda</a> / 
    <a href="/toko/kategori/{{ $product->category->slug ?? 'antibiotik' }}" class="text-primary hover:underline">{{ $product->category->name ?? 'Produk' }}</a> / 
    <span class="text-on-surface">{{ $product->name }}</span>
</nav>

<!-- Product Detail Grid -->
<section class="px-5 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Image Gallery -->
        <div class="space-y-3">
            <!-- Main Image -->
            <div class="relative bg-gray-100 rounded-xl overflow-hidden h-96 flex items-center justify-center group">
                <img id="mainImage" src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @if($discountPercent > 0)
                    <div class="absolute top-3 right-3 bg-primary text-white px-3 py-1 rounded-lg text-xs font-bold">-{{ $discountPercent }}%</div>
                @endif
            </div>
            <!-- Thumbnail Gallery -->
            <div class="grid grid-cols-4 gap-2">
                @foreach($gallery as $image)
                    <img onclick="document.getElementById('mainImage').src = this.src" src="{{ $image }}" alt="Thumbnail {{ $product->name }}" class="h-20 w-full object-cover rounded-lg cursor-pointer border-2 {{ $loop->first ? 'border-primary' : 'border-gray-200 hover:border-primary' }}">
                @endforeach
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-3xl font-bold mb-2 text-on-surface">{{ $product->name }}</h1>
            <p class="text-sm text-gray-600 mb-2">{{ $product->subtitle }}</p>
            
            <!-- Rating -->
            <div class="flex items-center gap-2 mb-4">
                <div class="flex gap-1">
                    @for($i = 0; $i < 5; $i++)
                        <span class="text-yellow-400 material-symbols-outlined text-xl">star</span>
                    @endfor
                </div>
                <span class="text-sm text-gray-600">({{ number_format($product->review_count) }} review)</span>
            </div>

            <!-- Price -->
            <div class="mb-6">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-bold text-primary">Rp {{ number_format($product->price) }}</span>
                    @if($product->original_price)
                        <span class="text-xl text-gray-400 line-through">Rp {{ number_format($product->original_price) }}</span>
                    @endif
                    @if($discountPercent > 0)
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm font-bold">-{{ $discountPercent }}%</span>
                    @endif
                </div>
                @if($saving > 0)
                    <p class="text-xs text-gray-500 mt-2">Hemat Rp {{ number_format($saving) }}</p>
                @endif
            </div>

            <!-- Stok & Kondisi -->
            <div class="mb-6 space-y-2 pb-6 border-b">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Stok:</span>
                    <span class="font-bold text-primary">{{ number_format($product->stock) }}+ pcs tersedia</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Terjual:</span>
                    <span class="font-bold">{{ number_format($product->sold_count) }}+ bulan lalu</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Kondisi:</span>
                    <span class="font-bold">{{ $product->condition }}</span>
                </div>
            </div>

            <!-- Quantity & CTA -->
            <div class="mb-6 space-y-4">
                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg w-fit">
                    <button class="w-8 h-8 flex items-center justify-center bg-white rounded border hover:bg-gray-100">−</button>
                    <input type="number" value="1" min="1" class="w-12 text-center border-0 bg-gray-50 font-bold">
                    <button class="w-8 h-8 flex items-center justify-center bg-white rounded border hover:bg-gray-100">+</button>
                </div>

                <div class="space-y-3">
                    <button class="w-full btn-primary">
                        <span class="material-symbols-outlined inline mr-2">shopping_cart</span>
                        Tambah ke Keranjang
                    </button>
                    <button class="w-full btn-secondary">
                        <span class="material-symbols-outlined inline mr-2">favorite</span>
                        Tambah ke Wishlist
                    </button>
                </div>
            </div>

            <!-- Seller Info -->
            <div class="bg-surface-container-low p-4 rounded-lg mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold">{{ $product->seller_name }}</p>
                        <p class="text-xs text-gray-600">Gudang: {{ $product->warehouse }}</p>
                    </div>
                    <button class="px-4 py-2 text-primary border border-primary rounded-lg text-sm font-bold hover:bg-primary hover:text-white transition-colors">Follow</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-12 border-b border-gray-200">
        <div class="flex gap-6">
            <button class="tab-btn px-0 py-3 border-b-2 border-primary text-primary font-bold" data-tab="deskripsi">Deskripsi</button>
            <button class="tab-btn px-0 py-3 border-b-2 border-transparent text-gray-600 font-bold hover:text-primary" data-tab="spesifikasi">Spesifikasi</button>
            <button class="tab-btn px-0 py-3 border-b-2 border-transparent text-gray-600 font-bold hover:text-primary" data-tab="review">Review ({{ number_format($product->review_count) }})</button>
        </div>
    </div>

    <!-- Tab: Deskripsi -->
    <div id="deskripsi-content" class="py-6 space-y-4">
        <h3 class="font-bold text-lg">Tentang Produk</h3>
        <p class="text-sm text-gray-700 leading-relaxed">
            {{ $product->description }}
        </p>
        <h3 class="font-bold text-lg mt-4">Keunggulan Produk</h3>
        <ul class="text-sm text-gray-700 space-y-2 list-disc list-inside">
            @foreach(($product->benefits ?? []) as $benefit)
                <li>{{ $benefit }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Tab: Spesifikasi -->
    <div id="spesifikasi-content" class="py-6 hidden">
        <h3 class="font-bold text-lg mb-4">Spesifikasi Produk</h3>
        <table class="w-full text-sm border border-gray-200">
            @foreach(($product->specifications ?? []) as $label => $value)
                <tr class="border-b last:border-b-0">
                    <td class="p-3 font-bold bg-gray-50 w-1/3">{{ $label }}</td>
                    <td class="p-3">{{ $value }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Tab: Review -->
    <div id="review-content" class="py-6 hidden">
        <h3 class="font-bold text-lg mb-4">Review Pembeli ({{ number_format($product->review_count) }})</h3>
        <div class="space-y-4">
            @forelse(($product->reviews ?? collect()) as $review)
                <div class="border border-gray-200 p-4 rounded-lg">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-bold">{{ $review->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $review->reviewed_at ? $review->reviewed_at->diffForHumans() : '-' }}</p>
                        </div>
                        <div class="flex gap-1">
                            @for($i = 0; $i < $review->rating; $i++)
                                <span class="text-yellow-400 material-symbols-outlined text-lg">star</span>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-600">Belum ada review untuk produk ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Related Products -->
    <section class="mt-12">
        <h2 class="text-2xl font-bold mb-6 text-on-surface">Produk Terkait</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse(($relatedProducts ?? collect()) as $relatedProduct)
                @include('layouts.components.product-card', [
                    'name' => $relatedProduct->name,
                    'slug' => $relatedProduct->slug,
                    'category' => $relatedProduct->category->name ?? 'Produk',
                    'price' => $relatedProduct->price,
                    'originalPrice' => $relatedProduct->original_price,
                    'image' => $relatedProduct->image
                ])
            @empty
                <p class="col-span-full text-sm text-gray-600">Produk terkait akan tampil setelah data katalog tersedia.</p>
            @endforelse
        </div>
    </section>
</section>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            document.querySelectorAll('[id$="-content"]').forEach(el => el.classList.add('hidden'));
            document.getElementById(tab + '-content').classList.remove('hidden');
            
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-primary', 'text-primary');
                b.classList.add('border-transparent', 'text-gray-600');
            });
            btn.classList.remove('border-transparent', 'text-gray-600');
            btn.classList.add('border-primary', 'text-primary');
        });
    });
</script>
@endsection
