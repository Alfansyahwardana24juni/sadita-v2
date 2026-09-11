<x-layouts.app :title="$category->name . ' - Produk SADITA'">
    @php
        $bannerUrl = $category->banner_image_url;
    @endphp

    <section class="bg-white">
        <div class="px-5 pt-6">
            <a href="{{ route('produk') }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wide text-primary">
                <span class="material-symbols-outlined text-[17px]">arrow_back</span>
                Kembali ke kategori
            </a>
        </div>

        @if($bannerUrl)
            <div class="mt-4 px-5">
                <div class="overflow-hidden rounded-2xl border border-line bg-surface">
                    <img src="{{ $bannerUrl }}" alt="{{ $category->name }}" class="h-52 w-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/800x400/F1F5F9/94A3B8?text=SADITA';" />
                </div>
            </div>
        @endif

        <div class="px-5 py-5">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Kategori Produk</p>
            <h1 class="mt-1 text-2xl font-black text-primary">{{ $category->name }}</h1>
            @if($category->description)
                <p class="mt-3 text-sm leading-6 text-muted">{{ $category->description }}</p>
            @endif
            <p class="mt-3 inline-flex items-center gap-1 rounded-full bg-surface px-3 py-1 text-xs font-bold text-moss">
                <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                {{ $products->count() }} produk
            </p>
        </div>
    </section>

    <section class="px-5 py-6">
        @if($products->isEmpty())
            <div class="rounded-2xl border border-dashed border-line bg-white p-6 text-center">
                <span class="material-symbols-outlined text-4xl text-muted">inventory_2</span>
                <h2 class="mt-3 text-lg font-black text-primary">Belum ada produk di kategori ini</h2>
                <p class="mt-2 text-sm text-muted">Silakan cek kategori lainnya.</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4">
                @foreach($products as $product)
                    <a href="{{ route('produk.detail', ['category' => $category, 'product' => $product]) }}" class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                        <div class="aspect-square bg-surface">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/400x400/F8FAFC/94A3B8?text=?';" loading="lazy">
                        </div>
                        <div class="p-3">
                            <h3 class="line-clamp-2 text-sm font-bold leading-5 text-ink">{{ $product->name }}</h3>
                            <p class="mt-1 text-xs font-semibold text-muted">{{ $product->units->count() }} pilihan unit</p>
                            <p class="mt-2 text-sm font-black text-primary">Mulai dari Rp {{ number_format($product->price_from, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
