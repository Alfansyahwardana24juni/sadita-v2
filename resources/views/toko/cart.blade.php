<x-layouts.toko title="Keranjang - SADITA">
    <section class="border-b border-line bg-white px-5 py-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('toko.katalog') }}" class="flex h-9 w-9 items-center justify-center rounded-xl text-primary">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-lg font-black text-primary">Keranjang Belanja</h1>
                @if($warehouseName)
                    <p class="text-xs text-muted">Gudang: {{ $warehouseName }}</p>
                @endif
            </div>
        </div>
    </section>

    @if($items->isEmpty())
        <div class="flex flex-col items-center justify-center px-5 py-20 text-center">
            <div class="flex h-32 w-32 items-center justify-center rounded-full bg-surface mb-6 shadow-inner">
                <span class="material-symbols-outlined text-[72px] text-muted/50">shopping_cart_off</span>
            </div>
            <h2 class="text-xl font-black text-primary">Keranjang masih kosong</h2>
            <p class="mt-2 text-sm leading-6 text-muted max-w-[280px]">Belum ada produk di keranjang Anda. Cari obat atau vitamin untuk ternak Anda sekarang.</p>
            <a href="{{ route('toko.katalog') }}" class="mt-8 inline-flex h-12 items-center gap-2 rounded-xl bg-primary px-6 text-sm font-bold text-white shadow-[0_4px_15px_rgba(128,0,0,0.2)] hover:bg-primary/90 active:scale-95 transition-all">
                Mulai Belanja
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    @else
        <section class="px-5 py-4 space-y-3">
            @foreach($items as $item)
                <div class="flex gap-3 rounded-2xl border border-line bg-white p-3" id="cart-item-{{ $item['product_id'] }}">
                    @if($item['image'])
                        @php
                            $imageSrc = str_starts_with($item['image'], 'http')
                                ? $item['image']
                                : Storage::url($item['image']);
                        @endphp
                        <img src="{{ $imageSrc }}" alt="{{ $item['name'] }}" class="h-16 w-16 rounded-xl object-cover shrink-0 bg-surface" onerror="this.onerror=null;this.src='https://placehold.co/400x400/F1F5F9/94A3B8?text=No+Image';" />
                    @else
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-surface">
                            <span class="material-symbols-outlined text-muted">inventory_2</span>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm font-bold text-ink line-clamp-2">{{ $item['name'] }}</h3>
                        <p class="mt-1 text-sm font-black text-primary">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        <div class="mt-2 flex items-center gap-3">
                            <div class="flex items-center gap-2 rounded-xl border border-line bg-surface px-2">
                                <button onclick="updateQty({{ $item['product_id'] }}, {{ $item['quantity'] - 1 }})" class="flex h-7 w-7 items-center justify-center text-primary hover:bg-primary/10 rounded-lg active:scale-90 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">remove</span>
                                </button>
                                <span class="text-sm font-bold w-6 text-center" id="qty-{{ $item['product_id'] }}">{{ $item['quantity'] }}</span>
                                <button onclick="updateQty({{ $item['product_id'] }}, {{ $item['quantity'] + 1 }})" class="flex h-7 w-7 items-center justify-center text-primary hover:bg-primary/10 rounded-lg active:scale-90 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                </button>
                            </div>
                            <button onclick="removeItem({{ $item['product_id'] }})" class="text-xs text-red-500 font-semibold hover:text-red-700 active:scale-95 transition-all">Hapus</button>
                        </div>
                    </div>
                    <p class="text-sm font-black text-ink shrink-0" id="subtotal-{{ $item['product_id'] }}">
                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </section>

        <section class="fixed bottom-[72px] left-1/2 z-40 -translate-x-1/2 border-t border-line bg-white p-4 shadow-lg fixed-container-responsive">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm text-muted">Total</span>
                <span class="text-lg font-black text-primary" id="total-price">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout') }}" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-bold text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                Proses Pesanan
            </a>
        </section>
    @endif

    <script>
        async function saditaFetchWithRetry(url, options, retries = 1) {
            try {
                return await fetch(url, options);
            } catch (error) {
                if (retries <= 0) throw error;
                await new Promise(resolve => setTimeout(resolve, 500));
                return saditaFetchWithRetry(url, options, retries - 1);
            }
        }

        async function updateQty(productId, qty) {
            try {
                const res = await saditaFetchWithRetry(`/cart/update/${productId}`, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ quantity: qty })
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'Gagal memperbarui jumlah produk.');

                if (qty <= 0) {
                    document.getElementById(`cart-item-${productId}`)?.remove();
                } else {
                    const item = data.items?.find(i => i.product_id == productId);
                    if (item) {
                        document.getElementById(`qty-${productId}`).textContent = item.quantity;
                        document.getElementById(`subtotal-${productId}`).textContent = 'Rp ' + Number(item.subtotal).toLocaleString('id-ID');
                    }
                }

                document.getElementById('total-price').textContent = 'Rp ' + Number(data.subtotal || 0).toLocaleString('id-ID');
            } catch (err) {
                if (typeof window.saditaNotify === 'function') {
                    window.saditaNotify(err.message || 'Koneksi bermasalah. Coba lagi.', 'error');
                }
            }
        }

        async function removeItem(productId) {
            if (typeof Swal !== 'undefined') {
                const result = await Swal.fire({
                    title: 'Hapus Produk?',
                    text: "Apakah Anda yakin ingin menghapus produk ini dari keranjang?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-3xl shadow-xl border border-line/20',
                        title: 'text-lg font-black text-ink',
                        confirmButton: 'rounded-xl font-bold',
                        cancelButton: 'rounded-xl font-bold'
                    }
                });
                
                if (!result.isConfirmed) return;
            } else {
                if (!confirm('Yakin ingin menghapus produk ini?')) return;
            }

            try {
                const res = await saditaFetchWithRetry(`/cart/remove/${productId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'Gagal menghapus produk dari keranjang.');

                document.getElementById(`cart-item-${productId}`)?.remove();
                if (typeof window.saditaNotify === 'function') {
                    window.saditaNotify('Produk dihapus dari keranjang', 'success');
                }
                
                setTimeout(() => location.reload(), 800);
            } catch (err) {
                if (typeof window.saditaNotify === 'function') {
                    window.saditaNotify(err.message || 'Koneksi bermasalah. Coba lagi.', 'error');
                }
            }
        }
    </script>
</x-layouts.toko>
