<?php
$selectedCount = collect($items)->filter(fn($i) => !isset($i["selected"]) || $i["selected"])->count();
$allSelected = count($items) > 0 && $selectedCount === count($items);
?>
<x-layouts.toko title="Keranjang - SADITA">
    <section class="sticky top-0 z-50 border-b border-line bg-white/95 backdrop-blur px-5 py-4 flex items-center gap-3">
        <a href="{{ route('toko.katalog') }}" class="flex h-9 w-9 items-center justify-center rounded-xl text-primary hover:bg-primary/5 transition-colors">
            <span class="material-symbols-outlined text-[22px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-lg font-black text-ink leading-tight">Keranjang Belanja</h1>
            @if($warehouseName)
                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mt-0.5">Gudang: {{ $warehouseName }}</p>
            @endif
        </div>
    </section>

    @if($items->isEmpty())
        <div class="flex flex-col items-center justify-center px-5 py-24 text-center">
            <div class="flex h-32 w-32 items-center justify-center rounded-full bg-surface mb-6 shadow-inner ring-1 ring-black/5">
                <span class="material-symbols-outlined text-[64px] text-muted/50">shopping_cart_off</span>
            </div>
            <h2 class="text-xl font-black text-ink">Keranjang masih kosong</h2>
            <p class="mt-2 text-sm leading-6 text-muted max-w-[280px]">Belum ada produk di keranjang Anda. Cari obat atau vitamin untuk ternak Anda sekarang.</p>
            <a href="{{ route('toko.katalog') }}" class="mt-8 inline-flex h-12 items-center gap-2 rounded-xl bg-primary px-6 text-sm font-bold text-white shadow-lg shadow-primary/30 hover:bg-primary-hover active:scale-95 transition-all">
                Mulai Belanja
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    @else
        <!-- Checkbox Pilih Semua -->
        <section class="px-5 pt-4 pb-1 sticky top-[72px] z-40 bg-white">
            <label class="flex items-center gap-3 cursor-pointer p-2 -ml-2 rounded-xl hover:bg-surface/50 transition-colors">
                <input type="checkbox" id="select-all" onchange="toggleSelectAll(this.checked)" class="cart-checkbox w-[22px] h-[22px] rounded-lg border-2 border-line text-primary focus:ring-0 focus:ring-offset-0 cursor-pointer transition-all" {{ $allSelected ? 'checked' : '' }}>
                <span class="text-sm font-bold text-ink">Pilih Semua</span>
            </label>
        </section>

        <section class="px-5 py-3 space-y-3 pb-32">
            @foreach($items as $item)
                <div class="flex gap-3 rounded-2xl border border-line bg-white p-3 shadow-sm hover:border-primary/30 transition-colors" id="cart-item-{{ $item['product_id'] }}">
                    <div class="pt-1 flex-shrink-0">
                        <label class="cursor-pointer">
                            <input type="checkbox" onchange="toggleSelect({{ $item['product_id'] }}, this.checked)" class="cart-item-checkbox w-[22px] h-[22px] rounded-lg border-2 border-line text-primary focus:ring-0 focus:ring-offset-0 cursor-pointer transition-all" {{ (!isset($item['selected']) || $item['selected']) ? 'checked' : '' }}>
                        </label>
                    </div>
                    
                    @if($item['image'])
                        @php
                            $imageSrc = str_starts_with($item['image'], 'http') ? $item['image'] : Storage::url($item['image']);
                        @endphp
                        <img src="{{ $imageSrc }}" alt="{{ $item['name'] }}" class="h-[72px] w-[72px] rounded-xl object-cover shrink-0 bg-surface border border-line/50" onerror="this.onerror=null;this.src='https://placehold.co/400x400/F1F5F9/94A3B8?text=No+Image';" />
                    @else
                        <div class="flex h-[72px] w-[72px] shrink-0 items-center justify-center rounded-xl bg-surface border border-line/50">
                            <span class="material-symbols-outlined text-muted text-[32px]">inventory_2</span>
                        </div>
                    @endif
                    
                    <div class="min-w-0 flex-1 flex flex-col justify-between py-0.5">
                        <h3 class="text-sm font-bold text-ink leading-snug line-clamp-2">{{ $item['name'] }}</h3>
                        <p class="mt-1 text-[15px] font-black text-primary">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        
                        <div class="mt-2.5 flex items-center justify-between">
                            <button onclick="removeItem({{ $item['product_id'] }})" class="text-[13px] text-muted hover:text-red-500 font-semibold active:scale-95 transition-all flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">delete</span>Hapus</button>
                            
                            <div class="flex items-center gap-1.5 rounded-xl border border-line bg-white shadow-sm p-0.5">
                                <button onclick="updateQty({{ $item['product_id'] }}, {{ $item['quantity'] - 1 }})" class="flex h-7 w-7 items-center justify-center text-ink hover:bg-surface hover:text-primary rounded-lg active:scale-90 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">remove</span>
                                </button>
                                <span class="text-[13px] font-bold w-6 text-center text-ink" id="qty-{{ $item['product_id'] }}">{{ $item['quantity'] }}</span>
                                <button onclick="updateQty({{ $item['product_id'] }}, {{ $item['quantity'] + 1 }})" class="flex h-7 w-7 items-center justify-center text-ink hover:bg-surface hover:text-primary rounded-lg active:scale-90 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="fixed bottom-[72px] left-1/2 z-40 w-full max-w-[488px] -translate-x-1/2 border-t border-line bg-white px-5 py-3 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col">
                    <span class="text-[11px] font-bold text-muted uppercase tracking-wider mb-0.5">Total Harga</span>
                    <span class="text-[17px] font-black text-primary" id="total-price">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                
                @php
                    $isBtnDisabled = $selectedCount === 0;
                    $btnClass = $isBtnDisabled ? 'bg-surface text-muted pointer-events-none' : 'bg-primary text-white shadow-lg shadow-primary/30 hover:bg-primary-hover hover:-translate-y-0.5 active:scale-95';
                @endphp
                
                <a href="{{ route('checkout') }}" id="checkout-btn" class="flex h-12 flex-1 items-center justify-center gap-2 rounded-xl text-[15px] font-bold transition-all {{ $btnClass }}">
                    Beli (<span id="checkout-count">{{ $selectedCount }}</span>)
                </a>
            </div>
        </section>
    @endif

    <script>
        async function saditaFetchWithRetry(url, options, retries = 1) {
            try { return await fetch(url, options); }
            catch (error) {
                if (retries <= 0) throw error;
                await new Promise(resolve => setTimeout(resolve, 500));
                return saditaFetchWithRetry(url, options, retries - 1);
            }
        }

        async function toggleSelect(productId, selected) {
            try {
                const res = await saditaFetchWithRetry('/cart/toggle-select', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ product_id: productId, selected: selected })
                });
                const data = await res.json();
                updateBottomBar(data);
                checkSelectAllState();
            } catch (err) { window.saditaNotify('Gagal mengubah pilihan', 'error'); }
        }

        async function toggleSelectAll(selected) {
            try {
                const res = await saditaFetchWithRetry('/cart/toggle-select', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ product_id: null, selected: selected })
                });
                const data = await res.json();
                document.querySelectorAll('.cart-item-checkbox').forEach(cb => cb.checked = selected);
                updateBottomBar(data);
            } catch (err) { window.saditaNotify('Gagal mengubah pilihan', 'error'); }
        }

        function checkSelectAllState() {
            const all = document.querySelectorAll('.cart-item-checkbox');
            const checked = document.querySelectorAll('.cart-item-checkbox:checked');
            const selectAllBtn = document.getElementById('select-all');
            if(selectAllBtn) selectAllBtn.checked = all.length === checked.length && all.length > 0;
        }

        function updateBottomBar(data) {
            const count = data.selectedCount || 0;
            const subtotal = data.subtotal || 0;
            document.getElementById('total-price').textContent = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
            
            const btn = document.getElementById('checkout-btn');
            const countSpan = document.getElementById('checkout-count');
            if (btn && countSpan) {
                countSpan.textContent = count;
                if (count > 0) {
                    btn.className = 'flex h-12 flex-1 items-center justify-center gap-2 rounded-xl text-[15px] font-bold transition-all bg-primary text-white shadow-lg shadow-primary/30 hover:bg-primary-hover hover:-translate-y-0.5 active:scale-95';
                } else {
                    btn.className = 'flex h-12 flex-1 items-center justify-center gap-2 rounded-xl text-[15px] font-bold transition-all bg-surface text-muted pointer-events-none';
                }
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
                if (!res.ok) throw new Error(data.message || 'Gagal memperbarui jumlah.');

                if (qty <= 0) {
                    document.getElementById(`cart-item-${productId}`)?.remove();
                    checkSelectAllState();
                } else {
                    const item = data.items?.find(i => i.product_id == productId);
                    if (item) {
                        document.getElementById(`qty-${productId}`).textContent = item.quantity;
                    }
                }
                // When qty updates, update total price and count by toggling a fake select (no-op but gets latest totals)
                toggleSelect(productId, document.querySelector(`#cart-item-${productId} .cart-item-checkbox`).checked);
            } catch (err) { window.saditaNotify(err.message, 'error'); }
        }

        async function removeItem(productId) {
            if (!confirm('Yakin ingin menghapus produk ini dari keranjang?')) return;
            try {
                const res = await saditaFetchWithRetry(`/cart/remove/${productId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'Gagal menghapus.');

                document.getElementById(`cart-item-${productId}`)?.remove();
                window.saditaNotify('Produk dihapus', 'success');
                // Re-calculate totals using toggleSelect API for null
                const res2 = await saditaFetchWithRetry('/cart/toggle-select', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ product_id: productId, selected: false }) // ensure it is false/removed
                });
                const data2 = await res2.json();
                updateBottomBar(data2);
                checkSelectAllState();
                
                if (document.querySelectorAll('.cart-item-checkbox').length === 0) location.reload();
            } catch (err) { window.saditaNotify(err.message, 'error'); }
        }
    </script>
</x-layouts.toko>

