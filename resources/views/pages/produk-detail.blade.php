<x-layouts.app :title="$product->name . ' - SADITA'">
    <section class="border-b border-line bg-white px-5 py-5">
        <a href="{{ route('produk.category', $category) }}" class="mb-3 inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wide text-primary">
            <span class="material-symbols-outlined text-[17px]">arrow_back</span>
            Kembali ke {{ $category->name }}
        </a>
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss">Detail Produk</p>
        <h1 class="mt-1 text-2xl font-black text-primary">{{ $product->name }}</h1>
    </section>

    <section class="bg-white">
        <div class="relative aspect-square border-y border-line bg-surface">
            <img class="h-full w-full object-cover" alt="{{ $product->name }}" src="{{ $product->image_url }}">
            <span class="absolute left-4 top-4 rounded bg-primary px-3 py-1 text-[10px] font-black uppercase tracking-wide text-white">{{ $category->name }}</span>
        </div>
    </section>

    <section class="px-5 py-5">
        <div class="mb-2 flex flex-wrap gap-2">
            <span class="rounded-full bg-primary px-3 py-1 text-[10px] font-black uppercase tracking-wide text-white">Produk SADITA</span>
            <span class="rounded-full bg-surface px-3 py-1 text-[10px] font-black uppercase tracking-wide text-muted">{{ $category->name }}</span>
        </div>
        <p class="text-sm leading-6 text-muted">{{ $product->short_description }}</p>

        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="rounded-2xl border border-line bg-white p-3">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Cocok untuk</p>
                <p class="mt-1 text-sm font-black text-primary">{{ $product->animal_type ?: '-' }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-white p-3">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Stok</p>
                <p class="mt-1 text-sm font-black text-primary">{{ number_format($stock, 0, ',', '.') }} pcs</p>
            </div>
        </div>

        <div class="mt-5 rounded-2xl border border-line bg-white p-4 shadow-sm">
            <div class="flex items-end gap-2">
                <p class="text-3xl font-black text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                @if ($product->compare_at_price)
                    <p class="pb-1 text-sm font-semibold text-muted line-through">Rp {{ number_format($product->compare_at_price, 0, ',', '.') }}</p>
                @endif
            </div>
            <a href="{{ route('saditacare') }}" class="mt-4 flex h-11 items-center justify-center gap-2 rounded-xl border border-primary bg-white text-sm font-bold text-primary">
                <span class="material-symbols-outlined text-[18px]">forum</span>
                Konsultasi Produk
            </a>
        </div>
    </section>

    <section class="mt-6 px-5" x-data="{ activeTab: 'desc' }">
        <div class="grid grid-cols-3 overflow-hidden rounded-2xl border border-line bg-white p-1 text-xs font-bold">
            <button type="button" @click="activeTab = 'desc'" class="rounded-xl px-3 py-3" :class="activeTab === 'desc' ? 'bg-primary text-white' : 'text-muted'">Deskripsi</button>
            <button type="button" @click="activeTab = 'spec'" class="rounded-xl px-3 py-3" :class="activeTab === 'spec' ? 'bg-primary text-white' : 'text-muted'">Spesifikasi</button>
            <button type="button" @click="activeTab = 'reviews'" class="rounded-xl px-3 py-3" :class="activeTab === 'reviews' ? 'bg-primary text-white' : 'text-muted'">Review</button>
        </div>

        <div class="mt-4 rounded-2xl border border-line bg-white p-4 shadow-sm">
            <div x-show="activeTab === 'desc'">
                <h2 class="text-base font-black">Deskripsi Produk</h2>
                <p class="mt-3 text-sm leading-7 text-muted">{{ $product->description ?: $product->short_description ?: '-' }}</p>
                <div class="mt-4 grid gap-3">
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-primary">
                            <span class="material-symbols-outlined text-[20px]">health_and_safety</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Manfaat utama:</strong> membantu penanganan sesuai indikasi produk pada hewan target.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-surface text-moss">
                            <span class="material-symbols-outlined text-[20px]">medical_services</span>
                        </span>
                        <p class="text-sm leading-6"><strong>Catatan penggunaan:</strong> ikuti dosis pada label dan konsultasikan ke dokter hewan untuk kasus berat.</p>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'spec'" style="display: none;">
                <h2 class="text-base font-black">Spesifikasi Teknis</h2>
                <dl class="mt-4 divide-y divide-line text-sm">
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Komposisi</dt><dd class="text-right font-bold">{{ $product->composition ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Indikasi</dt><dd class="text-right font-bold">{{ $product->indication ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Dosis</dt><dd class="text-right font-bold">{{ $product->dosage ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Withdrawal</dt><dd class="text-right font-bold">{{ $product->withdrawal_time ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 py-3"><dt class="font-semibold text-muted">Nomor Registrasi</dt><dd class="text-right font-bold">{{ $product->registration_number ?: '-' }}</dd></div>
                </dl>
            </div>

            <div x-show="activeTab === 'reviews'" style="display: none;">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black">Review Pengguna</h2>
                        <p class="mt-1 text-xs font-semibold text-muted">Contoh ulasan edukatif untuk halaman non-toko</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black text-primary">5.0</p>
                        <p class="text-xs font-semibold text-muted">Konsultasi positif</p>
                    </div>
                </div>
                <div class="mt-4 space-y-4">
                    <article class="border-t border-line pt-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold">Peternak Mandiri</p>
                            <p class="text-xs font-semibold text-muted">Bogor</p>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-muted">Penjelasan produk jelas dan membantu memahami kapan produk ini tepat digunakan.</p>
                    </article>
                    <article class="border-t border-line pt-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold">Mitra Farm</p>
                            <p class="text-xs font-semibold text-muted">Bandung</p>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-muted">Spesifikasi lengkap, jadi memudahkan konsultasi dengan dokter hewan sebelum aplikasi.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="mt-8 px-5 pb-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-black">Produk Terkait</h3>
                <a href="{{ route('produk.category', $category) }}" class="text-xs font-bold uppercase tracking-wide text-primary">Lihat Semua</a>
            </div>
            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                @foreach ($relatedProducts as $relatedProduct)
                    <a href="{{ route('produk.detail', ['category' => $category, 'product' => $relatedProduct]) }}" class="w-40 shrink-0 overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                        <div class="h-32 w-full overflow-hidden bg-surface">
                            <img class="h-32 w-full object-cover" alt="{{ $relatedProduct->name }}" src="{{ $relatedProduct->image_url }}">
                        </div>
                        <div class="p-3">
                            <h4 class="line-clamp-2 text-sm font-bold">{{ $relatedProduct->name }}</h4>
                            <p class="mt-2 text-sm font-black text-primary">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @push('scripts')
    @endpush
</x-layouts.app>
