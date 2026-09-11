<x-layouts.toko title="Pesanan Berhasil - SADITA">
    <div class="flex flex-col items-center justify-center px-5 py-10 text-center">
        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-moss/10 text-moss">
            <span class="material-symbols-outlined text-[40px]" style="font-variation-settings:'FILL' 1;">check_circle</span>
        </div>
        <h1 class="mt-5 text-2xl font-black text-primary">Pesanan Diterima!</h1>
        <p class="mt-2 text-sm text-muted">No. pesanan: <span class="font-bold text-ink">{{ $order->order_number }}</span></p>
        <p class="mt-1 text-sm text-muted">Lanjutkan konfirmasi via WhatsApp di bawah.</p>
    </div>

    {{-- Order Summary --}}
    <section class="mx-5 rounded-2xl border border-line bg-white p-4">
        <p class="mb-3 text-xs font-bold uppercase tracking-[0.16em] text-muted">Detail Pesanan</p>
        <div class="space-y-2">
            @foreach($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span class="text-ink">{{ $item->product_name }} x{{ $item->quantity }}</span>
                    <span class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="border-t border-line pt-2 flex justify-between">
                <span class="text-sm font-bold">Total</span>
                <span class="text-sm font-black text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </section>

    <section class="mx-5 mt-4 rounded-2xl border border-line bg-white p-4 space-y-2">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Data Pemesan</p>
        <p class="text-sm"><span class="text-muted">Nama:</span> <span class="font-bold">{{ $order->customer_name }}</span></p>
        <p class="text-sm"><span class="text-muted">HP:</span> <span class="font-bold">{{ $order->customer_phone }}</span></p>
        <p class="text-sm"><span class="text-muted">Pembayaran:</span> <span class="font-bold">{{ $order->payment_method_label }}</span> <span class="text-muted">({{ $order->payment_status_label }})</span></p>
        @if($order->warehouse)
            <p class="text-sm"><span class="text-muted">Gudang:</span> <span class="font-bold">{{ $order->warehouse->name }}</span></p>
        @endif
    </section>

    @php
        $bankName = config('sadita.bank_name');
        $bankAccount = config('sadita.bank_account');
        $bankHolder = config('sadita.bank_holder');
        $qrisImageUrl = config('sadita.qris_image_url');
    @endphp

    @if ($order->payment_status !== 'paid_confirmed')
        <section class="mx-5 mt-4 rounded-2xl border border-line bg-white p-4">
            <p class="mb-3 text-xs font-bold uppercase tracking-[0.16em] text-muted">Instruksi Pembayaran</p>

            @if ($order->payment_method === 'transfer')
                <div class="rounded-xl bg-surface p-3 text-sm text-ink">
                    <p class="font-bold">Transfer ke rekening resmi SADITA:</p>
                    <div class="mt-2 space-y-1 text-sm text-muted">
                        <p><span class="font-semibold text-ink">Bank:</span> {{ $bankName ?: '-' }}</p>
                        <p><span class="font-semibold text-ink">No. Rek:</span> {{ $bankAccount ?: '-' }}</p>
                        <p><span class="font-semibold text-ink">A/N:</span> {{ $bankHolder ?: '-' }}</p>
                    </div>
                    <p class="mt-3 text-xs leading-5 text-muted">Setelah transfer, kirim bukti pembayaran via WhatsApp agar pesanan bisa segera diproses.</p>
                </div>
            @elseif ($order->payment_method === 'qris')
                <div class="rounded-xl bg-surface p-3 text-sm text-muted">
                    <p class="font-bold text-ink">Scan QRIS untuk pembayaran:</p>
                    @if ($qrisImageUrl)
                        <img src="{{ $qrisImageUrl }}" alt="QRIS SADITA" class="mt-3 w-full rounded-xl border border-line bg-white p-2">
                    @else
                        <p class="mt-2 text-xs leading-5">QRIS belum di-set. Silakan hubungi admin SADITA via WhatsApp untuk instruksi pembayaran.</p>
                    @endif
                    <p class="mt-3 text-xs leading-5">Setelah bayar, kirim screenshot bukti pembayaran via WhatsApp.</p>
                </div>
            @else
                <div class="rounded-xl bg-surface p-3 text-sm text-muted">
                    <p class="font-bold text-ink">COD (konfirmasi admin)</p>
                    <p class="mt-2 text-xs leading-5">Tim SADITA akan konfirmasi ketersediaan dan detail pengiriman via WhatsApp.</p>
                </div>
            @endif
        </section>
    @endif

    {{-- WhatsApp CTA --}}
    @if($waMessage && $waPhone)
        <section class="mx-5 mt-5 rounded-2xl border border-line bg-surface p-4">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-moss mb-3">Konfirmasi via WhatsApp</p>
            <div class="rounded-xl bg-white border border-line p-3 text-xs text-muted leading-6 whitespace-pre-line mb-4">{{ $waMessage }}</div>
            <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode($waMessage) }}"
                target="_blank"
                class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#25D366] text-sm font-bold text-white shadow-sm">
                <svg class="h-[18px] w-[18px] fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Kirim ke WhatsApp SADITA
            </a>
        </section>
    @endif

    <div class="mx-5 mt-4 grid grid-cols-2 gap-3">
        <a href="{{ route('toko.katalog') }}" class="flex h-11 items-center justify-center gap-2 rounded-xl border border-line bg-white text-sm font-bold text-primary">
            <span class="material-symbols-outlined text-[17px]">grid_view</span>
            Katalog
        </a>
        <a href="{{ route('toko.orders') }}" class="flex h-11 items-center justify-center gap-2 rounded-xl bg-primary text-sm font-bold text-white">
            <span class="material-symbols-outlined text-[17px]">receipt_long</span>
            Lihat Order
        </a>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('load', () => {
                if (typeof window.saditaNotify === 'function') {
                    window.saditaNotify('Pesanan berhasil dibuat!', 'checkout');
                }
            });
        </script>
    @endpush
</x-layouts.toko>
