@php
    $storeSetting = \App\Models\StoreSetting::active();
    $paymentMethods = $storeSetting->payment_methods ?? [];
@endphp
<x-layouts.toko title="Checkout - SADITA">
    {{-- Header --}}
    <section class="sticky top-0 z-30 border-b border-line bg-white">
        <div class="flex items-center gap-3 px-5 py-4">
            <a href="{{ route('cart.index') }}" class="flex h-10 w-10 items-center justify-center rounded-full hover:bg-surface transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div class="flex-1">
                <h1 class="text-base font-black text-ink">Checkout</h1>
                <p class="text-[11px] text-muted">Selesaikan pembelian Anda</p>
            </div>
        </div>
    </section>

    {{-- Main Container --}}
    <div class="bg-surface min-h-screen pb-28">
        <div class="px-4 py-5 md:py-6">
            @if(session('error'))
                <div class="mb-5 rounded-xl bg-red-50 p-4 text-sm text-red-800 border border-red-100 flex items-start gap-3 animate-fade-in shadow-sm">
                    <span class="material-symbols-outlined text-base text-red-600 shrink-0 mt-0.5">error</span>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                {{-- 1. Alamat Pengiriman --}}
                <div class="bg-white rounded-2xl border border-line p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-line border-dashed">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 shrink-0">
                            <span class="material-symbols-outlined text-base text-primary">location_on</span>
                        </div>
                        <div>
                            <h2 class="text-base font-black text-ink">Alamat Pengiriman</h2>
                            <p class="text-[11px] font-semibold text-muted">Dikirim dari <span class="text-primary">{{ $warehouse->name }}{{ $warehouse->postal_code ? ' (' . $warehouse->postal_code . ')' : '' }}</span></p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3.5">
                        <!-- Inputs -->
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-muted">Nama Penerima *</label>
                            <input name="customer_name" value="{{ old('customer_name') }}" required
                                class="h-11 w-full rounded-xl border border-line bg-surface/50 px-3 text-sm placeholder-ink focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-medium @error('customer_name') border-red-500 @enderror"
                                placeholder="Nama lengkap" />
                            @error('customer_name') <p class="mt-1 text-[10px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-muted">No. HP / WhatsApp *</label>
                            <input name="customer_phone" value="{{ old('customer_phone') }}" required type="tel"
                                class="h-11 w-full rounded-xl border border-line bg-surface/50 px-3 text-sm placeholder-ink focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-medium @error('customer_phone') border-red-500 @enderror"
                                placeholder="08xxxxxxxxxx" />
                            @error('customer_phone') <p class="mt-1 text-[10px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-surface/30 p-4 rounded-2xl border border-line">
                            <div>
                                <label class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-muted">Provinsi *</label>
                                <div class="relative">
                                    <select name="customer_province" id="province-select" required
                                        class="h-11 w-full appearance-none rounded-xl border border-line bg-white px-3 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-semibold @error('customer_province') border-red-500 @enderror">
                                        <option value="">Pilih Provinsi...</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-muted pointer-events-none">expand_more</span>
                                </div>
                                @error('customer_province') <p class="mt-1 text-[10px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                            </div>

                            <div id="city-container">
                                <label class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-muted">Kota/Kabupaten *</label>
                                <div class="relative">
                                    <select name="customer_regency_id" id="city-select" required disabled
                                        class="h-11 w-full appearance-none rounded-xl border border-line bg-white px-3 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-semibold disabled:opacity-60 disabled:bg-surface disabled:cursor-not-allowed">
                                        <option value="">Pilih Kota/Kabupaten...</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-muted pointer-events-none">expand_more</span>
                                </div>
                                <input type="hidden" name="customer_city" id="customer-city-input" value="{{ old('customer_city') }}">
                            </div>

                            <div id="district-container">
                                <label class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-muted">Kecamatan *</label>
                                <div class="relative">
                                    <select name="district_id" id="district-select" required disabled
                                        class="h-11 w-full appearance-none rounded-xl border border-line bg-white px-3 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-semibold disabled:opacity-60 disabled:bg-surface disabled:cursor-not-allowed">
                                        <option value="">Pilih Kecamatan...</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-muted pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div id="village-container">
                                <label class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-muted">Kelurahan/Desa *</label>
                                <div class="relative">
                                    <select name="village_id" id="village-select" required disabled
                                        class="h-11 w-full appearance-none rounded-xl border border-line bg-white px-3 text-sm focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-semibold disabled:opacity-60 disabled:bg-surface disabled:cursor-not-allowed">
                                        <option value="">Pilih Kelurahan/Desa...</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-muted pointer-events-none">expand_more</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-muted">Alamat Lengkap *</label>
                            <textarea name="customer_address" rows="2" required
                                class="w-full rounded-xl border border-line bg-surface/50 px-3 py-2 text-sm placeholder-ink focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none @error('customer_address') border-red-500 @enderror"
                                placeholder="Nama jalan, gedung, RT/RW, nomor rumah">{{ old('customer_address') }}</textarea>
                            @error('customer_address') <p class="mt-1 text-[10px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-muted">Kode Pos *</label>
                            <input name="postal_code" id="postal-code-input" value="{{ old('postal_code') }}" required inputmode="numeric" maxlength="10"
                                class="h-11 w-full rounded-xl border border-line bg-surface/50 px-3 text-sm placeholder-ink focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-medium @error('postal_code') border-red-500 @enderror"
                                placeholder="Contoh: 12345" />
                            @error('postal_code') <p class="mt-1 text-[10px] text-red-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-muted">Catatan (Opsional)</label>
                            <input name="notes" value="{{ old('notes') }}"
                                class="h-11 w-full rounded-xl border border-line bg-surface/50 px-3 text-sm placeholder-ink focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                                placeholder="Cth: Titip di pos satpam" />
                        </div>
                    </div>

                    {{-- Fitur Ingat Alamat --}}
                    <div class="mt-5 p-4 rounded-xl border-2 border-dashed border-primary/20 bg-primary/5 flex items-start gap-3">
                        <input type="checkbox" id="remember-me" checked
                            class="mt-1 w-5 h-5 rounded-md border-2 border-primary text-primary focus:ring-0 focus:ring-offset-0 cursor-pointer transition-colors">
                        <label for="remember-me" class="cursor-pointer select-none">
                            <div class="text-sm font-black text-primary">YA, Simpan alamat & info pengiriman saya.</div>
                            <div class="text-xs font-medium text-ink mt-0.5">Data ini akan otomatis terisi saat Anda belanja lagi.</div>
                        </label>
                    </div>
                </div>

                {{-- 2. Ringkasan Belanja --}}
                <div class="bg-white rounded-2xl border border-line p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-line border-dashed">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 shrink-0">
                            <span class="material-symbols-outlined text-base text-primary">shopping_bag</span>
                        </div>
                        <div class="flex-1 flex justify-between items-center">
                            <h2 class="text-base font-black text-ink">Pesanan Anda</h2>
                            <span class="text-[10px] font-bold bg-surface px-2 py-1 rounded-full text-muted border border-line" id="checkout-total-weight-summary">...</span>
                        </div>
                    </div>

                    <div class="space-y-3" id="checkout-items-list">
                        @foreach($items as $item)
                            <div class="group rounded-xl border border-line bg-white p-3 flex items-start gap-3" data-checkout-item="{{ $item['product_id'] }}">
                                @if(isset($item['image']) && $item['image'])
                                    <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="h-16 w-16 rounded-lg object-cover border border-line shrink-0" />
                                @else
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg bg-surface border border-line">
                                        <span class="material-symbols-outlined text-muted text-xl">inventory_2</span>
                                    </div>
                                @endif
                                
                                <div class="flex-1 min-w-0 flex flex-col h-full justify-between gap-1.5">
                                    <div class="flex justify-between items-start gap-2">
                                        <p class="text-sm font-bold text-ink line-clamp-2 leading-tight">{{ $item['name'] }}</p>
                                    </div>
                                    <div class="flex items-end justify-between gap-2 mt-auto">
                                        <div>
                                            <p class="text-[11px] font-semibold text-muted">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                            <span class="block text-sm font-black text-primary mt-0.5" data-item-subtotal>
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex h-8 items-center rounded-lg border border-line bg-surface/50">
                                            <button type="button" class="flex h-8 w-8 items-center justify-center text-primary active:bg-primary/10 rounded-l-lg transition-colors" data-cart-action="decrease" data-product-id="{{ $item['product_id'] }}">
                                                <span class="material-symbols-outlined text-[17px]">remove</span>
                                            </button>
                                            <span class="w-7 text-center text-xs font-bold" data-item-qty>{{ $item['quantity'] }}</span>
                                            <button type="button" class="flex h-8 w-8 items-center justify-center text-primary active:bg-primary/10 rounded-r-lg transition-colors" data-cart-action="increase" data-product-id="{{ $item['product_id'] }}">
                                                <span class="material-symbols-outlined text-[17px]">add</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 3. Metode Pengiriman --}}
                <div class="bg-white rounded-2xl border border-line p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.04)]" id="shipping-section-wrapper">
                    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-line border-dashed">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 shrink-0">
                            <span class="material-symbols-outlined text-base text-primary">local_shipping</span>
                        </div>
                        <h2 class="text-base font-black text-ink">Metode Pengiriman</h2>
                    </div>

                    <div id="shipping-options" class="space-y-3">
                        <div class="py-4 text-center border-2 border-dashed border-line rounded-xl bg-surface/30">
                            <span class="material-symbols-outlined text-muted text-3xl mb-2">map</span>
                            <p class="text-sm font-medium text-muted">Silakan lengkapi alamat pengiriman di atas untuk melihat opsi ongkos kirim.</p>
                        </div>
                    </div>

                    <input type="hidden" name="shipping_method" id="shipping-method-input">
                    <input type="hidden" name="shipping_service" id="shipping-service-input">
                    <input type="hidden" name="shipping_cost" id="shipping-cost-input" value="0">
                </div>

                {{-- 5. Metode Pembayaran --}}
                <div class="bg-white rounded-2xl border border-line p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.04)]">
                    <h2 class="text-xs font-black text-ink mb-3 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[16px]">account_balance_wallet</span>
                        Pembayaran
                    </h2>
                    <div class="space-y-2.5">
                        @forelse($paymentMethods as $index => $pm)
                            @if($pm['type'] === 'bank')
                                <label class="group flex flex-col gap-2 rounded-xl border-2 border-line bg-white p-3 cursor-pointer hover:border-primary/40 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:shadow-[0_4px_12px_-4px_rgba(var(--color-primary),0.1)]">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="payment_method" value="transfer_{{ $index }}" @checked(old('payment_method', $index === 0 ? 'transfer_0' : '') === 'transfer_'.$index) required class="text-primary focus:ring-primary/20 accent-primary w-4 h-4">
                                        <div class="flex-1">
                                            <p class="font-bold text-sm text-ink flex items-center gap-1.5">💳 Transfer Bank {{ $pm['bank_name'] ?? '' }}</p>
                                            <p class="text-[11px] text-muted font-medium mt-0.5">Verifikasi manual ke rekening resmi</p>
                                        </div>
                                    </div>
                                    <div class="hidden group-has-[:checked]:block mt-1 pl-7">
                                        <div class="p-2 rounded-lg bg-white border border-line text-[11px] leading-5 text-muted">
                                            Transfer ke rekening resmi kami:<br>
                                            <strong>Bank: {{ $pm['bank_name'] ?? '-' }}</strong><br>
                                            <strong>No. Rek: {{ $pm['bank_account'] ?? '-' }}</strong><br>
                                            <strong>A/N: {{ $pm['bank_holder'] ?? '-' }}</strong>
                                        </div>
                                    </div>
                                </label>
                            @elseif($pm['type'] === 'qris')
                                <label class="group flex flex-col gap-2 rounded-xl border-2 border-line bg-white p-3 cursor-pointer hover:border-primary/40 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:shadow-[0_4px_12px_-4px_rgba(var(--color-primary),0.1)]">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="payment_method" value="qris_{{ $index }}" @checked(old('payment_method', $index === 0 ? 'qris_0' : '') === 'qris_'.$index) required class="text-primary focus:ring-primary/20 accent-primary w-4 h-4">
                                        <div class="flex-1">
                                            <p class="font-bold text-sm text-ink flex items-center gap-1.5">📱 QRIS</p>
                                            <p class="text-[11px] text-muted font-medium mt-0.5">Gopay, OVO, Dana, ShopeePay, dll</p>
                                        </div>
                                    </div>
                                    <div class="hidden group-has-[:checked]:block mt-1 pl-7">
                                        <div class="p-2 rounded-lg bg-white border border-line text-[11px] leading-5 text-muted">
                                            Setelah pesanan dibuat, Anda akan mendapatkan QR Code untuk di-scan dengan aplikasi dompet digital atau mobile banking Anda.
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @empty
                            <div class="text-sm text-amber-600 bg-amber-50 p-3 rounded-lg border border-amber-200">
                                Metode pembayaran digital sedang tidak tersedia. Silakan pilih Bayar di Tempat (COD).
                            </div>
                        @endforelse

                        <label class="group flex flex-col gap-2 rounded-xl border-2 border-line bg-white p-3 cursor-pointer hover:border-primary/40 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:shadow-[0_4px_12px_-4px_rgba(var(--color-primary),0.1)]">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod" @checked(old('payment_method') === 'cod') required class="text-primary focus:ring-primary/20 accent-primary w-4 h-4">
                                <div class="flex-1">
                                    <p class="font-bold text-sm text-ink flex items-center gap-1.5">💵 Bayar di Tempat (COD)</p>
                                    <p class="text-[11px] text-muted font-medium mt-0.5">Bayar tunai ke kurir saat tiba</p>
                                </div>
                            </div>
                            <div class="hidden group-has-[:checked]:block mt-1 pl-7">
                                <div class="p-2 rounded-lg bg-white border border-line text-[11px] leading-5 text-muted">
                                    Pesanan akan dikirimkan dan Anda dapat membayar langsung kepada kurir kami saat barang tiba di lokasi Anda.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- 6. Total Pembayaran --}}
                <div class="bg-white rounded-2xl border border-line p-5 shadow-[0_4px_20px_-8px_rgba(0,0,0,0.1)] relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-28 h-28 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>

                    <h3 class="font-black text-ink text-xs uppercase tracking-wider mb-4 relative z-10">Rincian Akhir</h3>
                    <div class="space-y-2.5 text-sm font-medium text-muted mb-4 pb-4 border-b border-line border-dashed relative z-10">
                        <div class="flex justify-between items-center">
                            <span>Subtotal Produk</span>
                            <span class="font-bold text-ink" id="detail-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="flex items-center gap-1">
                                Ongkos Kirim 
                                <span class="text-[9px] bg-surface border border-line px-1 py-0.5 rounded" id="checkout-courier-summary">-</span>
                            </span>
                            <span class="font-bold text-ink" id="detail-shipping">Rp 0</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between relative z-10">
                        <span class="font-black text-ink text-sm">TOTAL BAYAR</span>
                        <span id="total-payment-summary" class="text-xl font-black text-primary tracking-tight">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                {{-- 7. Tombol (Hanya terlihat jika tidak ada sticky bar atau di layar besar) --}}
                <div class="hidden">
                    <button form="checkout-form" type="submit" id="checkout-submit-button"></button>
                    <span id="checkout-submit-icon"></span>
                    <span id="checkout-submit-text"></span>
                </div>

            </form>
        </div>
    </div>

    {{-- Sticky Bottom Summary Bar (Selalu Muncul) --}}
    <div id="sticky-bottom-bar" class="fixed bottom-[72px] left-1/2 -translate-x-1/2 w-full z-40 border-t border-line bg-white/95 backdrop-blur-md px-4 py-3 shadow-[0_-8px_24px_-8px_rgba(0,0,0,0.1)] fixed-container-responsive">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[10px] uppercase tracking-wider text-muted font-bold">Total Pembayaran</p>
                <p id="sticky-total-payment" class="text-lg font-black text-primary truncate tracking-tight">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
            </div>
            <button form="checkout-form" type="submit" id="sticky-checkout-submit-button"
                class="flex-grow h-11 px-5 rounded-xl bg-primary text-sm font-black text-white shadow-[0_8px_16px_-4px_rgba(var(--color-primary),0.3)] active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2 transition-transform">
                <span id="sticky-checkout-submit-icon" class="material-symbols-outlined text-[18px]">lock</span>
                <span id="sticky-checkout-submit-text">Bayar</span>
            </button>
        </div>
    </div>
    
    @php

        $checkoutItemsPayload = $items->values()->map(function ($item) {
            return [
                'name' => $item['name'],
                'price' => (int) $item['price'],
                'quantity' => (int) $item['quantity'],
            ];
        })->all();

        $originVillageCode = config('api_co_id.origin_village_codes.' . $warehouse->slug)
            ?: config('api_co_id.origin_village_code');
    @endphp

    <script>
        (() => {
            // ── Element refs ──────────────────────────────────────────────────
            const form                      = document.getElementById('checkout-form');
            const submitButton              = document.getElementById('checkout-submit-button');
            const submitText                = document.getElementById('checkout-submit-text');
            const submitIcon                = document.getElementById('checkout-submit-icon');

            const stickySubmitButton        = document.getElementById('sticky-checkout-submit-button');
            const stickySubmitText          = document.getElementById('sticky-checkout-submit-text');
            const stickySubmitIcon          = document.getElementById('sticky-checkout-submit-icon');
            const stickyTotalPayment        = document.getElementById('sticky-total-payment');

            const provinceSelect            = document.getElementById('province-select');
            const cityContainer             = document.getElementById('city-container');
            const citySelect                = document.getElementById('city-select');
            const districtContainer         = document.getElementById('district-container');
            const districtSelect            = document.getElementById('district-select');
            const villageContainer          = document.getElementById('village-container');
            const villageSelect             = document.getElementById('village-select');
            const customerCityInput         = document.getElementById('customer-city-input');
            const postalCodeInput           = document.getElementById('postal-code-input');
            const shippingSectionWrapper    = document.getElementById('shipping-section-wrapper');
            const shippingOptions           = document.getElementById('shipping-options');
            const shippingMethodInput       = document.getElementById('shipping-method-input');
            const shippingServiceInput      = document.getElementById('shipping-service-input');
            const shippingCostInput         = document.getElementById('shipping-cost-input');

            // Ringkasan Belanja Summary fields
            const subtotalSummaryLabel      = document.getElementById('checkout-subtotal-summary-label');
            const totalWeightSummary        = document.getElementById('checkout-total-weight-summary');
            const courierSummary            = document.getElementById('checkout-courier-summary');
            const etdSummary                = document.getElementById('checkout-etd-summary');
            const shippingSummary            = document.getElementById('checkout-shipping-summary');

            // Total Pembayaran breakdown
            const detailSubtotal            = document.getElementById('detail-subtotal');
            const detailShipping            = document.getElementById('detail-shipping');
            const detailDiscount            = document.getElementById('detail-discount');
            const totalPaymentSummary       = document.getElementById('total-payment-summary');

            const checkoutItemsList         = document.getElementById('checkout-items-list');

            let subtotal = {{ $subtotal }};
            const originVillageCode = @json($originVillageCode);
            const originCity = @json($warehouse->city);
            
            let checkoutItems = @json($checkoutItemsPayload);
            let cartWeightGram = {{ $totalWeight ?? 500 }};

            let selectedShippingCost = 0;
            let shippingMethodsLoaded = false;

            if (!form || !submitButton) return;

            // ── Boot ──────────────────────────────────────────────────────────
            loadProvinces().then(() => {
                restoreCustomerDataFromLocalStorage();
            });
            bindCheckoutCartControls();
            updateWeightDisplay();

            // ── Province change → load cities ─────────────────────────────────
            provinceSelect.addEventListener('change', () => {
                const provinceId = provinceSelect.value;
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten...</option>';
                customerCityInput.value = '';
                citySelect.disabled = true;
                resetDistricts();
                resetVillages();
                hideShippingSection();

                if (provinceId) loadCities(provinceId);
            });

            // ── City change → load districts ─────────────────────────────────
            citySelect.addEventListener('change', () => {
                const selected = citySelect.selectedOptions[0];
                const regencyId = citySelect.value;
                const cityName = selected?.textContent?.trim() || '';
                customerCityInput.value = cityName;
                resetDistricts();
                resetVillages();
                hideShippingSection();

                if (regencyId) {
                    loadDistricts(regencyId);
                }
            });

            // ── District change → load villages ──────────────────────────────
            districtSelect.addEventListener('change', () => {
                resetVillages();
                hideShippingSection();

                if (districtSelect.value) {
                    loadVillages(districtSelect.value);
                }
            });

            // ── Village change → load shipping (RULE 1: Wajib kelurahan dipilih)
            villageSelect.addEventListener('change', () => {
                const selected = citySelect.selectedOptions[0];
                if (citySelect.value && selected && villageSelect.value) {
                    loadShippingMethods(
                        citySelect.value,
                        selected.dataset.rajaongkirCityId || '',
                        selected.textContent.trim(),
                        cartWeightGram
                    );
                } else {
                    hideShippingSection();
                }
            });

            postalCodeInput.addEventListener('input', () => {
                postalCodeInput.value = postalCodeInput.value.replace(/\D+/g, '').slice(0, 10);
            });

            // ── Form submit guard ─────────────────────────────────────────────
            form.addEventListener('submit', async (e) => {
                if (!shippingMethodsLoaded) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Pilih Pengiriman',
                            text: 'Silakan pilih wilayah lengkap dan metode pengiriman terlebih dahulu.',
                            icon: 'warning'
                        });
                    } else {
                        alert('Silakan pilih wilayah lengkap dan metode pengiriman terlebih dahulu');
                    }
                    return;
                }
                
                if (typeof Swal !== 'undefined') {
                    e.preventDefault();
                    const result = await Swal.fire({
                        title: 'Buat Pesanan?',
                        text: "Pastikan alamat dan rincian pesanan sudah benar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Buat Pesanan',
                        cancelButtonText: 'Cek Lagi',
                        customClass: {
                            popup: 'rounded-3xl shadow-xl border border-line/20',
                            title: 'text-lg font-black text-ink',
                            confirmButton: 'rounded-xl font-bold bg-primary',
                            cancelButton: 'rounded-xl font-bold'
                        }
                    });
                    
                    if (!result.isConfirmed) return;
                }
                
                // Disable both buttons
                submitButton.disabled = true;
                if (stickySubmitButton) stickySubmitButton.disabled = true;

                // Loading text & spinner on main submit button
                if (submitText) submitText.textContent = 'Memproses...';
                if (submitIcon) {
                    submitIcon.textContent = 'progress_activity';
                    submitIcon.classList.add('animate-spin');
                }

                // Loading text & spinner on mobile sticky submit button
                if (stickySubmitText) stickySubmitText.textContent = 'Memproses...';
                if (stickySubmitIcon) {
                    stickySubmitIcon.textContent = 'progress_activity';
                    stickySubmitIcon.classList.add('animate-spin');
                }
                
                saveCustomerDataToLocalStorage();
                if (typeof Swal !== 'undefined') form.submit();
            });

            // --- Auto-fill LocalStorage Logic ---
            function saveCustomerDataToLocalStorage() {
                try {
                    const rememberMe = document.getElementById('remember-me');
                    if (rememberMe && !rememberMe.checked) {
                        localStorage.removeItem('sadita_saved_customer');
                        return;
                    }
                    
                    const customerData = {
                        customer_name: form.querySelector('[name="customer_name"]')?.value || '',
                        customer_phone: form.querySelector('[name="customer_phone"]')?.value || '',
                        customer_province: provinceSelect.value || '',
                        customer_regency_id: citySelect.value || '',
                        district_id: districtSelect.value || '',
                        village_id: villageSelect.value || '',
                        customer_address: form.querySelector('[name="customer_address"]')?.value || '',
                        postal_code: postalCodeInput.value || '',
                    };
                    localStorage.setItem('sadita_saved_customer', JSON.stringify(customerData));
                } catch (e) {
                    console.error('Gagal menyimpan data ke LocalStorage:', e);
                }
            }

            async function restoreCustomerDataFromLocalStorage() {
                try {
                    const saved = localStorage.getItem('sadita_saved_customer');
                    if (!saved) return;
                    
                    const data = JSON.parse(saved);
                    
                    if(data.customer_name) form.querySelector('[name="customer_name"]').value = data.customer_name;
                    if(data.customer_phone) form.querySelector('[name="customer_phone"]').value = data.customer_phone;
                    if(data.customer_address) form.querySelector('[name="customer_address"]').value = data.customer_address;
                    if(data.postal_code) postalCodeInput.value = data.postal_code;

                    if(data.customer_province) {
                        provinceSelect.value = data.customer_province;
                        await loadCities(data.customer_province);
                        
                        if(data.customer_regency_id) {
                            citySelect.disabled = false;
                            citySelect.value = data.customer_regency_id;
                            customerCityInput.value = citySelect.options[citySelect.selectedIndex]?.text || '';
                            await loadDistricts(data.customer_regency_id);
                            
                            if(data.district_id) {
                                districtSelect.disabled = false;
                                districtSelect.value = data.district_id;
                                await loadVillages(data.district_id);
                                
                                if(data.village_id) {
                                    villageSelect.disabled = false;
                                    villageSelect.value = data.village_id;
                                    hideShippingSection();
                                    const o = citySelect.options[citySelect.selectedIndex];
                                    if (o && o.dataset.rajaongkirCityId) {
                                        const rjId = o.dataset.rajaongkirCityId;
                                        await loadShippingMethods(data.customer_regency_id, rjId, o.textContent, cartWeightGram);
                                    }
                                }
                            }
                        }
                    }
                } catch (e) {
                    console.error('Gagal memulihkan data dari LocalStorage:', e);
                }
            }

            // ── Load provinces ────────────────────────────────────────────────
            async function loadProvinces() {
                try {
                    const res  = await fetch('/api/shipping/provinces');
                    const data = await res.json();
                    if (data.success && data.data?.length) {
                        provinceSelect.innerHTML = '<option value="">Pilih Provinsi...</option>';
                        data.data.forEach(p => {
                            const o = new Option(formatRegionName(p.province), p.province_id);
                            provinceSelect.append(o);
                        });
                    }
                } catch (err) {
                    console.error('Gagal memuat provinsi:', err);
                }
            }

            async function loadDistricts(regencyId) {
                try {
                    districtSelect.innerHTML = '<option value="">Memuat Kecamatan...</option>';
                    const res  = await fetch(`/api/shipping/districts?regency_id=${regencyId}`);
                    const data = await res.json();
                    if (data.success && data.data?.length) {
                        districtSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';
                        data.data.forEach(d => {
                            districtSelect.append(new Option(formatRegionName(d.district_name), d.district_id));
                        });
                        districtSelect.disabled = false;
                    } else {
                        districtSelect.innerHTML = '<option value="">Kecamatan tidak ditemukan</option>';
                    }
                } catch (err) {
                    console.error('Gagal memuat kecamatan:', err);
                    districtSelect.innerHTML = '<option value="">Gagal memuat</option>';
                }
            }

            async function loadVillages(districtId) {
                try {
                    villageSelect.innerHTML = '<option value="">Memuat Kelurahan/Desa...</option>';
                    const res  = await fetch(`/api/shipping/villages?district_id=${districtId}`);
                    const data = await res.json();
                    if (data.success && data.data?.length) {
                        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa...</option>';
                        data.data.forEach(v => {
                            villageSelect.append(new Option(formatRegionName(v.village_name), v.village_id));
                        });
                        villageSelect.disabled = false;
                    } else {
                        villageSelect.innerHTML = '<option value="">Kelurahan tidak ditemukan</option>';
                    }
                } catch (err) {
                    console.error('Gagal memuat kelurahan/desa:', err);
                    villageSelect.innerHTML = '<option value="">Gagal memuat</option>';
                }
            }

            // ── Load cities ───────────────────────────────────────────────────
            async function loadCities(provinceId) {
                try {
                    citySelect.innerHTML = '<option value="">Memuat Kota/Kabupaten...</option>';
                    const res  = await fetch(`/api/shipping/cities?province_id=${provinceId}`);
                    const data = await res.json();
                    if (data.success && data.data?.length) {
                        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten...</option>';
                        data.data.forEach(c => {
                            const label = formatRegionName(c.city_name);
                            const o = new Option(label, c.regency_id);
                            if (c.rajaongkir_city_id) {
                                o.dataset.rajaongkirCityId = c.rajaongkir_city_id;
                            }
                            citySelect.append(o);
                        });
                        citySelect.disabled = false;
                    } else {
                        citySelect.innerHTML = '<option value="">Kota tidak ditemukan</option>';
                    }
                } catch (err) {
                    console.error('Gagal memuat kota:', err);
                    citySelect.innerHTML = '<option value="">Gagal memuat</option>';
                }
            }

            // ── Load shipping methods with Skeleton Loading state ─────────────
            async function loadShippingMethods(regencyId, cityId, cityName, weightGram) {
                renderShippingSkeleton();

                const csrf = document.querySelector('meta[name="csrf-token"]')?.content
                           || document.querySelector('input[name="_token"]')?.value
                           || '';

                try {
                    const res  = await fetch('/api/shipping/methods', {
                        method : 'POST',
                        headers: {
                            'Content-Type' : 'application/json',
                            'X-CSRF-TOKEN' : csrf,
                        },
                        body: JSON.stringify({
                            regency_id: regencyId,
                            city_id  : cityId ? parseInt(cityId) : null,
                            city_name: cityName,
                            postal_code: postalCodeInput.value.trim(),
                            origin_village_code: originVillageCode,
                            destination_village_code: villageSelect.value,
                            origin_city: originCity,
                            weight   : weightGram,
                            total    : subtotal,
                            items    : checkoutItems,
                        }),
                    });

                    const data = await res.json();

                    if (data.success && data.methods?.length) {
                        renderShippingOptions(data.methods, data.is_local, cityName);
                    } else {
                        showShippingError(data.message || 'Tidak ada metode pengiriman tersedia untuk kota ini.');
                    }
                } catch (err) {
                    console.error('Gagal memuat metode pengiriman:', err);
                    showShippingError('Gagal memuat metode pengiriman. Coba lagi.');
                }
            }

            // ── Render Shipping Skeleton (No empty spinner) ───────────────────
            function renderShippingSkeleton() {
                shippingSectionWrapper.style.display = 'block';
                shippingOptions.style.display        = 'block';
                shippingMethodsLoaded                = false;

                shippingOptions.innerHTML = `
                    <div class="text-xs text-muted mb-3 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-500 animate-pulse">
                            Menghitung Ongkir...
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 animate-pulse">
                            <div class="h-4 w-4 rounded-full bg-slate-200"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                                <div class="h-3 bg-slate-200 rounded w-1/4"></div>
                            </div>
                            <div class="h-5 bg-slate-200 rounded w-16"></div>
                        </div>
                        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 animate-pulse">
                            <div class="h-4 w-4 rounded-full bg-slate-200"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 bg-slate-200 rounded w-1/2"></div>
                                <div class="h-3 bg-slate-200 rounded w-1/3"></div>
                            </div>
                            <div class="h-5 bg-slate-200 rounded w-12"></div>
                        </div>
                    </div>
                `;
            }

            // ── Render shipping options with "Lihat layanan lain" toggle ───────
            function renderShippingOptions(methods, isLocal, cityName) {
                const hasExpedition = methods.some(method => ['api_co_id', 'rajaongkir'].includes(method.type));
                const badge = isLocal
                    ? `<span class="inline-flex items-center gap-1 rounded-full bg-moss/10 px-2.5 py-1 text-xs font-semibold text-moss">
                           <span class="material-symbols-outlined text-[13px]">home_pin</span> Dalam Kota
                       </span>`
                    : `<span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                           <span class="material-symbols-outlined text-[13px]">public</span> ${hasExpedition ? 'Ekspedisi Nasional' : 'Luar Kota'}
                       </span>`;

                const header = `<div class="mb-3 flex items-center gap-2 flex-wrap">
                    ${badge}
                    <span class="text-xs text-muted">→ <strong class="text-ink">${cityName}</strong></span>
                </div>`;

                const firstOptionHtml = renderSingleOptionHtml(methods[0], 0, true);
                let extraOptionsHtml = '';
                let toggleBtnHtml = '';

                if (methods.length > 1) {
                    extraOptionsHtml = `
                        <div id="extra-shipping-options" style="display: none;" class="space-y-2 mt-2 animate-fade-in">
                            ${methods.slice(1).map((method, idx) => renderSingleOptionHtml(method, idx + 1, false)).join('')}
                        </div>
                    `;
                    toggleBtnHtml = `
                        <button type="button" id="toggle-shipping-btn" class="mt-3 w-full py-2.5 border border-line rounded-lg text-xs font-bold text-ink hover:bg-surface transition-all flex items-center justify-center gap-1">
                            <span>Lihat Layanan Lain (${methods.length - 1})</span>
                            <span class="material-symbols-outlined text-sm">keyboard_arrow_down</span>
                        </button>
                    `;
                }

                shippingOptions.innerHTML = header + '<div class="space-y-2">' + firstOptionHtml + '</div>' + extraOptionsHtml + toggleBtnHtml;

                // Bind change event to all radios
                shippingOptions.querySelectorAll('input[name="shipping_option"]').forEach((radio) => {
                    radio.addEventListener('change', (e) => {
                        const index = parseInt(e.target.value);
                        selectShippingMethod(methods[index]);
                        
                        // Style selected option border
                        shippingOptions.querySelectorAll('label').forEach((label, i) => {
                            if (i === index) {
                                label.classList.add('border-primary', 'bg-primary/5');
                                label.classList.remove('border-line', 'bg-white');
                            } else {
                                label.classList.remove('border-primary', 'bg-primary/5');
                                label.classList.add('border-line', 'bg-white');
                            }
                        });
                    });
                });

                // Bind toggle button click
                const toggleBtn = document.getElementById('toggle-shipping-btn');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', () => {
                        const extraDiv = document.getElementById('extra-shipping-options');
                        if (extraDiv) {
                            extraDiv.style.display = 'block';
                            toggleBtn.style.display = 'none';
                        }
                    });
                }

                // Default: select the best/cheapest method (index 0)
                selectShippingMethod(methods[0]);
                shippingMethodsLoaded = true;
            }

            function renderSingleOptionHtml(method, index, isSelected) {
                const isFree     = method.is_free || method.cost === 0;
                const icon       = method.type === 'pickup'
                    ? 'storefront'
                    : (method.type === 'kurir_sadita' ? 'electric_moped' : 'local_shipping');
                const label      = method.service_name
                    ? `${method.name} <span class="text-muted font-normal">— ${method.service_name}</span>`
                    : method.name;

                return `
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border-2 transition-all p-4
                    ${isSelected ? 'border-primary bg-primary/5' : 'border-line bg-white hover:border-primary/30'}">
                    <input type="radio" name="shipping_option" value="${index}"
                        ${isSelected ? 'checked' : ''} required class="mt-0.5 shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="material-symbols-outlined text-[18px] text-primary shrink-0">${icon}</span>
                            <span class="font-semibold text-sm text-ink">${label}</span>
                            ${isFree ? '<span class="rounded-full bg-moss px-2 py-0.5 text-[10px] font-bold text-white shrink-0">GRATIS</span>' : ''}
                        </div>
                        <p class="mt-1 text-xs text-muted">Estimasi: <strong>${method.estimated_days}</strong></p>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="block text-base font-black ${isFree ? 'text-moss' : 'text-primary'}">
                            ${isFree ? 'GRATIS' : 'Rp ' + method.cost.toLocaleString('id-ID')}
                        </span>
                    </div>
                </label>`;
            }

            function selectShippingMethod(method) {
                shippingMethodInput.value  = method.name;
                shippingServiceInput.value = method.service_code || '';
                shippingCostInput.value    = method.cost;
                selectedShippingCost       = method.cost;

                // Update Ringkasan Belanja details
                if (courierSummary) courierSummary.textContent = method.name;
                if (etdSummary) etdSummary.textContent = method.estimated_days;
                if (shippingSummary) shippingSummary.textContent = method.cost === 0 ? 'GRATIS' : 'Rp ' + method.cost.toLocaleString('id-ID');

                updateTotal();
            }

            function showShippingError(msg) {
                shippingOptions.innerHTML = `
                    <div class="py-6 text-center">
                        <p class="text-sm text-red-600 mb-3">${msg}</p>
                        <button type="button" id="retry-shipping-btn" class="inline-flex items-center gap-1 px-4 py-2 rounded-lg bg-surface border border-line text-xs font-bold text-ink hover:bg-line transition-all">
                            <span class="material-symbols-outlined text-sm">refresh</span> Coba Lagi
                        </button>
                    </div>
                `;

                const retryBtn = document.getElementById('retry-shipping-btn');
                if (retryBtn) {
                    retryBtn.addEventListener('click', () => {
                        const selected = citySelect.selectedOptions[0];
                        if (citySelect.value && selected && villageSelect.value) {
                            loadShippingMethods(
                                citySelect.value,
                                selected.dataset.rajaongkirCityId || '',
                                selected.textContent.trim(),
                                cartWeightGram
                            );
                        }
                    });
                }

                shippingOptions.style.display = 'block';
                shippingMethodsLoaded         = false;
            }

            function hideShippingSection() {
                shippingSectionWrapper.style.display = 'block';
                shippingOptions.style.display = 'block';
                shippingOptions.innerHTML = `
                    <div class="py-4 text-center border-2 border-dashed border-line rounded-xl bg-surface/30">
                        <span class="material-symbols-outlined text-muted text-3xl mb-2">map</span>
                        <p class="text-sm font-medium text-muted">Silakan lengkapi alamat pengiriman di atas untuk melihat opsi ongkos kirim.</p>
                    </div>
                `;
                selectedShippingCost  = 0;
                shippingMethodsLoaded = false;
                shippingMethodInput.value = '';
                shippingServiceInput.value = '';
                shippingCostInput.value = 0;

                // Reset Ringkasan Belanja details
                if (courierSummary) courierSummary.textContent = '-';
                if (etdSummary) etdSummary.textContent = '-';
                if (shippingSummary) shippingSummary.textContent = '-';

                updateTotal();
            }

            function resetDistricts() {
                districtSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';
                districtSelect.disabled = true;
            }

            function resetVillages() {
                villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa...</option>';
                villageSelect.disabled = true;
            }

            function updateWeightDisplay() {
                if (totalWeightSummary) {
                    if (cartWeightGram >= 1000) {
                        totalWeightSummary.textContent = (cartWeightGram / 1000).toFixed(1) + ' kg';
                    } else {
                        totalWeightSummary.textContent = cartWeightGram + ' gram';
                    }
                }
            }

            // Real-time changes logic
            function updateTotal() {
                const total = Math.max(0, subtotal + selectedShippingCost);
                
                // Update Section 6 breakdown
                if (detailSubtotal) detailSubtotal.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                if (detailShipping) detailShipping.textContent = selectedShippingCost === 0 ? 'Rp 0' : 'Rp ' + selectedShippingCost.toLocaleString('id-ID');
                
                // Update Total Pembayaran
                if (totalPaymentSummary) totalPaymentSummary.textContent = 'Rp ' + total.toLocaleString('id-ID');
                
                // Update Mobile Sticky Bar
                if (stickyTotalPayment) {
                    stickyTotalPayment.textContent = 'Rp ' + total.toLocaleString('id-ID');
                }
            }

            function bindCheckoutCartControls() {
                checkoutItemsList?.addEventListener('click', async (event) => {
                    const button = event.target.closest('[data-cart-action]');
                    if (!button) return;

                    const productId = button.dataset.productId;
                    const action = button.dataset.cartAction;
                    const itemEl = button.closest('[data-checkout-item]');
                    const currentQty = Number(itemEl?.querySelector('[data-item-qty]')?.textContent || 0);
                    const nextQty = action === 'increase'
                        ? currentQty + 1
                        : (action === 'decrease' ? currentQty - 1 : 0);

                    await updateCheckoutCartItem(productId, nextQty);
                });
            }

            async function updateCheckoutCartItem(productId, quantity) {
                try {
                    const res = await fetch(`/cart/update/${productId}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ quantity }),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data.message || 'Gagal memperbarui ringkasan pesanan.');

                    syncCheckoutSummary(data);
                } catch (error) {
                    if (typeof window.saditaNotify === 'function') {
                        window.saditaNotify(error.message || 'Gagal memperbarui ringkasan pesanan.', 'error');
                    }
                }
            }

            function syncCheckoutSummary(data) {
                const items = data.items || [];
                checkoutItems = items.map(item => ({
                    name: item.name,
                    price: Number(item.price || 0),
                    quantity: Number(item.quantity || 1),
                }));

                checkoutItemsList?.querySelectorAll('[data-checkout-item]').forEach(itemEl => {
                    const productId = Number(itemEl.dataset.checkoutItem);
                    const item = items.find(candidate => Number(candidate.product_id) === productId);

                    if (!item) {
                        itemEl.remove();
                        return;
                    }

                    itemEl.querySelector('[data-item-qty]').textContent = item.quantity;
                    itemEl.querySelector('[data-item-subtotal]').textContent = 'Rp ' + Number(item.subtotal || 0).toLocaleString('id-ID');
                });

                subtotal = Number(data.subtotal || 0);
                cartWeightGram = Number(data.totalWeight || 500);
                
                // Update subtotal in summary
                if (subtotalSummaryLabel) subtotalSummaryLabel.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                
                updateWeightDisplay();
                hideShippingSection();

                // Refetch shipping methods if village is already selected
                const selected = citySelect.selectedOptions[0];
                if (citySelect.value && selected && villageSelect.value) {
                    const cityId = selected.dataset.rajaongkirCityId || '';
                    const cityName = selected.textContent.trim();
                    customerCityInput.value = cityName;
                    loadShippingMethods(citySelect.value, cityId, cityName, cartWeightGram);
                }

                if (items.length === 0) {
                    window.location.href = @json(route('cart.index'));
                }

                if (typeof window.saditaUpdateCartCount === 'function') {
                    window.saditaUpdateCartCount();
                }
            }

            function formatRegionName(value) {
                return String(value || '')
                    .toLowerCase()
                    .replace(/\b\w/g, char => char.toUpperCase());
            }
        })();
    </script>
</x-layouts.toko>
