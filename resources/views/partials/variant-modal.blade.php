{{-- Modal / Bottom-Sheet Pilihan Varian & Quantity --}}
<div id="variant-selection-modal" class="fixed inset-0 z-[120] hidden items-end sm:items-center justify-center" aria-hidden="true">
    {{-- Backdrop --}}
    <div id="variant-modal-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity opacity-0"></div>

    {{-- Sheet / Dialog Content --}}
    <div id="variant-modal-content" class="relative w-full max-w-lg rounded-t-3xl sm:rounded-3xl bg-white p-5 shadow-2xl transition-all transform translate-y-full sm:translate-y-4 opacity-0 z-10 max-h-[90vh] flex flex-col">
        {{-- Header: Product Thumbnail & Title --}}
        <div class="flex items-start gap-3 border-b border-line pb-4 shrink-0">
            <img id="variant-modal-img" src="" alt="" class="h-16 w-16 rounded-xl object-cover border border-line bg-surface shrink-0" onerror="this.onerror=null;this.src='https://placehold.co/200x200/F1F5F9/94A3B8?text=Produk';">
            <div class="min-w-0 flex-1">
                <span id="variant-modal-category" class="inline-block rounded bg-primary/10 px-2 py-0.5 text-[9px] font-bold uppercase text-primary mb-1">Kategori</span>
                <h3 id="variant-modal-title" class="text-sm font-black text-ink line-clamp-2">Nama Produk</h3>
                <div class="mt-1 flex items-baseline gap-2">
                    <p id="variant-modal-price" class="text-base font-black text-primary">Rp 0</p>
                    <p id="variant-modal-compare" class="text-xs text-muted line-through hidden">Rp 0</p>
                </div>
            </div>
            <button type="button" id="variant-modal-close" class="flex h-8 w-8 items-center justify-center rounded-lg text-muted hover:bg-surface hover:text-ink transition-colors" aria-label="Tutup">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        {{-- Scrollable Body: Pilihan Varian & Quantity --}}
        <div class="flex-1 overflow-y-auto overscroll-contain py-4 space-y-4">
            {{-- Pilihan Varian --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-[0.14em] text-muted mb-2">Pilih Kemasan / Varian</label>
                <div id="variant-modal-units" class="grid grid-cols-1 gap-2">
                    {{-- Dinamis diisi via JavaScript --}}
                </div>
            </div>

            {{-- Quantity Selector --}}
            <div class="flex items-center justify-between rounded-2xl border border-line p-3 bg-surface/50">
                <div>
                    <label class="block text-xs font-bold text-ink">Jumlah Pembelian</label>
                    <span id="variant-modal-stock-info" class="text-[11px] font-medium text-muted">Stok: -</span>
                </div>
                <div class="flex h-10 items-center rounded-xl border border-line bg-white shadow-sm">
                    <button type="button" id="variant-modal-qty-minus" class="flex h-10 w-10 items-center justify-center text-primary hover:bg-surface active:bg-line rounded-l-xl transition-all" aria-label="Kurangi">
                        <span class="material-symbols-outlined text-[18px]">remove</span>
                    </button>
                    <span id="variant-modal-qty-val" class="w-10 text-center text-sm font-black text-ink">1</span>
                    <button type="button" id="variant-modal-qty-plus" class="flex h-10 w-10 items-center justify-center text-primary hover:bg-surface active:bg-line rounded-r-xl transition-all" aria-label="Tambah">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Footer: Total & Tombol Tambah --}}
        <div class="border-t border-line pt-3 shrink-0 flex items-center gap-3">
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wider text-muted">Subtotal</p>
                <p id="variant-modal-total" class="text-base font-black text-primary truncate">Rp 0</p>
            </div>
            <button type="button" id="variant-modal-submit" class="flex-1 flex h-12 items-center justify-center gap-2 rounded-xl bg-primary px-4 text-sm font-black text-white shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[19px]">add_shopping_cart</span>
                <span>+ Keranjang</span>
            </button>
        </div>
    </div>
</div>

<script>
    (() => {
        const modal = document.getElementById('variant-selection-modal');
        if (!modal) return;

        const backdrop = document.getElementById('variant-modal-backdrop');
        const content = document.getElementById('variant-modal-content');
        const closeBtn = document.getElementById('variant-modal-close');
        const imgEl = document.getElementById('variant-modal-img');
        const catEl = document.getElementById('variant-modal-category');
        const titleEl = document.getElementById('variant-modal-title');
        const priceEl = document.getElementById('variant-modal-price');
        const compareEl = document.getElementById('variant-modal-compare');
        const unitsContainer = document.getElementById('variant-modal-units');
        const stockInfo = document.getElementById('variant-modal-stock-info');
        const qtyVal = document.getElementById('variant-modal-qty-val');
        const qtyMinus = document.getElementById('variant-modal-qty-minus');
        const qtyPlus = document.getElementById('variant-modal-qty-plus');
        const totalEl = document.getElementById('variant-modal-total');
        const submitBtn = document.getElementById('variant-modal-submit');

        let currentProduct = null;
        let selectedUnit = null;
        let qty = 1;

        const fmt = (n) => 'Rp ' + Number(n || 0).toLocaleString('id-ID');

        function maxQty() {
            if (!selectedUnit) return 1;
            const stock = selectedUnit.stock > 0 ? selectedUnit.stock : 20;
            return Math.min(20, Math.max(1, stock));
        }

        function updateModalState() {
            if (!selectedUnit) return;

            priceEl.textContent = fmt(selectedUnit.price);
            if (selectedUnit.compare_at_price && selectedUnit.compare_at_price > selectedUnit.price) {
                compareEl.textContent = fmt(selectedUnit.compare_at_price);
                compareEl.classList.remove('hidden');
            } else {
                compareEl.classList.add('hidden');
            }

            const isOutOfStock = selectedUnit.stock <= 0 && selectedUnit.stock !== undefined;
            if (isOutOfStock) {
                stockInfo.textContent = 'Stok habis';
                stockInfo.className = 'text-[11px] font-bold text-red-500';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                submitBtn.querySelector('span:last-child').textContent = 'Stok Habis';
            } else {
                stockInfo.textContent = selectedUnit.stock ? `Tersedia ${selectedUnit.stock} pcs` : 'Tersedia';
                stockInfo.className = 'text-[11px] font-semibold text-moss';
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.querySelector('span:last-child').textContent = '+ Keranjang';
            }

            if (qty > maxQty()) qty = maxQty();
            if (qty < 1) qty = 1;
            qtyVal.textContent = qty;
            totalEl.textContent = fmt(selectedUnit.price * qty);

            // Highlight selected unit card
            document.querySelectorAll('.variant-unit-card').forEach(card => {
                const isSelected = card.dataset.unitId == selectedUnit.unit_id || card.dataset.unitSlug == selectedUnit.id;
                card.classList.toggle('border-primary', isSelected);
                card.classList.toggle('bg-primary/5', isSelected);
                card.classList.toggle('border-line', !isSelected);
                card.classList.toggle('bg-white', !isSelected);
            });
        }

        window.saditaOpenVariantModal = function(productPayload) {
            currentProduct = typeof productPayload === 'string' ? JSON.parse(productPayload) : productPayload;
            if (!currentProduct || !currentProduct.units || currentProduct.units.length === 0) return;

            imgEl.src = currentProduct.image || 'https://placehold.co/200x200/F1F5F9/94A3B8?text=Produk';
            catEl.textContent = currentProduct.category || 'SADITA';
            titleEl.textContent = currentProduct.name;

            // Render unit choices
            unitsContainer.innerHTML = '';
            currentProduct.units.forEach((unit, idx) => {
                const isOut = unit.stock !== undefined && unit.stock <= 0;
                const card = document.createElement('div');
                card.className = `variant-unit-card flex items-center justify-between p-3 rounded-xl border-2 cursor-pointer transition-all ${isOut ? 'opacity-50' : 'hover:border-primary/50'}`;
                card.dataset.unitId = unit.unit_id;
                card.dataset.unitSlug = unit.id;

                card.innerHTML = `
                    <div class="flex items-center gap-3">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-line variant-radio-indicator">
                            <span class="h-2.5 w-2.5 rounded-full bg-primary hidden variant-radio-dot"></span>
                        </span>
                        <div>
                            <p class="text-xs font-black text-ink">${unit.name}</p>
                            <p class="text-[10px] text-muted">${isOut ? 'Stok habis' : (unit.stock ? 'Stok: ' + unit.stock + ' pcs' : 'Tersedia')}</p>
                        </div>
                    </div>
                    <span class="text-xs font-black text-primary">${fmt(unit.price)}</span>
                `;

                card.addEventListener('click', () => {
                    selectedUnit = unit;
                    qty = 1;
                    updateModalState();
                });

                unitsContainer.appendChild(card);
            });

            // Default to first in-stock unit or first unit
            selectedUnit = currentProduct.units.find(u => u.stock > 0) || currentProduct.units[0];
            qty = 1;
            updateModalState();

            // Show modal with animation
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                content.classList.remove('translate-y-full', 'sm:translate-y-4', 'opacity-0');
                content.classList.add('translate-y-0', 'opacity-100');
            });
            document.body.style.overflow = 'hidden';
        };

        function closeModal() {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            content.classList.remove('translate-y-0', 'opacity-100');
            content.classList.add('translate-y-full', 'sm:translate-y-4', 'opacity-0');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 200);
        }

        closeBtn.addEventListener('click', closeModal);
        backdrop.addEventListener('click', closeModal);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        qtyMinus.addEventListener('click', () => {
            if (qty > 1) {
                qty--;
                updateModalState();
            }
        });

        qtyPlus.addEventListener('click', () => {
            if (qty < maxQty()) {
                qty++;
                updateModalState();
            }
        });

        submitBtn.addEventListener('click', async () => {
            if (!selectedUnit || (selectedUnit.stock !== undefined && selectedUnit.stock <= 0)) return;

            const btnOriginal = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span> Menyimpan...';

            try {
                // Call global saditaAddToCart
                await saditaAddToCart({
                    id: selectedUnit.id,
                    product_unit_id: selectedUnit.unit_id,
                    name: selectedUnit.full_name || (currentProduct.name + ' — ' + selectedUnit.name),
                    price: selectedUnit.price,
                    image: currentProduct.image
                }, qty);

                closeModal();
            } catch (err) {
                // error handled in saditaAddToCart
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = btnOriginal;
            }
        });

        // Global delegation for buttons with [data-open-variant-modal]
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-open-variant-modal]');
            if (btn) {
                e.preventDefault();
                const payload = btn.getAttribute('data-open-variant-modal');
                if (payload) {
                    window.saditaOpenVariantModal(payload);
                }
            }
        });
    })();
</script>
