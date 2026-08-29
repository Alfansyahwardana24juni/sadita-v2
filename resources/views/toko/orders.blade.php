<x-layouts.toko title="Order Saya - SADITA Toko">
    <section class="border-b border-line bg-white px-5 py-5 flex items-center justify-between">
        <div>
            <h1 class="text-lg font-black text-primary">Pesanan Saya</h1>
            <p class="text-xs text-muted mt-1">Status dan riwayat pesanan otomatis Anda</p>
        </div>
    </section>

    <section class="space-y-4 px-5 py-5 pb-24">
        @forelse($orders as $order)
            <article class="rounded-2xl border border-line bg-white p-4 shadow-sm" data-order-card>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-muted">No. Pesanan</p>
                        <p class="mt-1 text-sm font-black text-primary">{{ $order->order_number }}</p>
                    </div>
                    <span class="rounded-full bg-primary/10 px-3 py-1 text-[11px] font-bold text-primary">{{ $order->status_label }}</span>
                </div>

                <div class="mt-3 space-y-1 text-sm">
                    <p><span class="text-muted">Tanggal:</span> <span class="font-semibold">{{ $order->created_at->translatedFormat('d M Y H:i') }}</span></p>
                    @if($order->warehouse)
                        <p><span class="text-muted">Gudang:</span> <span class="font-semibold">{{ $order->warehouse->name }}</span></p>
                    @endif
                    <p><span class="text-muted">Total:</span> <span class="font-black text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                </div>

                <div class="mt-3 flex items-center justify-between border-t border-line pt-3">
                    <p class="text-xs text-muted">{{ $order->items->count() }} item</p>
                    <button
                        type="button"
                        class="order-detail-toggle inline-flex items-center gap-1 rounded-lg border border-line px-3 py-1.5 text-xs font-bold text-primary"
                        data-order-id="{{ $order->id }}"
                        aria-expanded="false"
                    >
                        <span class="material-symbols-outlined text-[16px]">expand_more</span>
                        Detail
                    </button>
                </div>

                <div id="order-detail-{{ $order->id }}" class="order-detail-panel mt-3 hidden border-t border-line pt-3">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-muted">Item Pesanan</p>
                    <div class="mt-2 space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="min-w-0 flex-1 font-semibold text-ink">{{ $item->product_name }} <span class="text-muted">x{{ $item->quantity }}</span></span>
                                <span class="shrink-0 font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 rounded-xl bg-surface p-3 text-xs leading-5 text-muted">
                        <p><span class="font-bold text-ink">Pemesan:</span> {{ $order->customer_name }}</p>
                        <p class="mt-1"><span class="font-bold text-ink">HP:</span> {{ $order->customer_phone }}</p>
                        @if($order->customer_address)
                            <p class="mt-1"><span class="font-bold text-ink">Alamat:</span> {{ $order->customer_address }}</p>
                        @endif
                        @if($order->notes)
                            <p class="mt-1"><span class="font-bold text-ink">Catatan:</span> {{ $order->notes }}</p>
                        @endif
                    </div>

                    @if($waPhone)
                        <a
                            href="https://wa.me/{{ $waPhone }}?text={{ urlencode($order->toWhatsAppMessage()) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-3 flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#25D366] text-sm font-bold text-white shadow-sm"
                        >
                            <svg class="h-[18px] w-[18px] fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Konfirmasi via WhatsApp
                        </a>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-line bg-white p-6 text-center">
                <span class="material-symbols-outlined text-4xl text-muted">receipt_long</span>
                <h2 class="mt-3 text-lg font-black text-primary">Belum ada pesanan</h2>
                <p class="mt-1 text-sm leading-6 text-muted">Pesanan yang sudah diajukan akan tampil di sini.</p>
                <a href="{{ route('toko.katalog') }}" class="mt-4 inline-flex h-10 items-center rounded-xl bg-primary px-4 text-sm font-bold text-white">
                    Mulai Belanja
                </a>
            </div>
        @endforelse
    </section>

    @push('scripts')
        <script>
            document.querySelectorAll('.order-detail-toggle').forEach((button) => {
                button.addEventListener('click', () => {
                    const orderId = button.dataset.orderId;
                    const panel = document.getElementById(`order-detail-${orderId}`);
                    const icon = button.querySelector('.material-symbols-outlined');
                    const isOpen = !panel.classList.contains('hidden');

                    document.querySelectorAll('.order-detail-panel').forEach((otherPanel) => {
                        if (otherPanel !== panel) {
                            otherPanel.classList.add('hidden');
                        }
                    });
                    document.querySelectorAll('.order-detail-toggle').forEach((otherButton) => {
                        if (otherButton !== button) {
                            otherButton.setAttribute('aria-expanded', 'false');
                            const otherIcon = otherButton.querySelector('.material-symbols-outlined');
                            if (otherIcon) otherIcon.textContent = 'expand_more';
                        }
                    });

                    if (isOpen) {
                        panel.classList.add('hidden');
                        button.setAttribute('aria-expanded', 'false');
                        if (icon) icon.textContent = 'expand_more';
                    } else {
                        panel.classList.remove('hidden');
                        button.setAttribute('aria-expanded', 'true');
                        if (icon) icon.textContent = 'expand_less';
                    }
                });
            });
        </script>
    @endpush
</x-layouts.toko>
