<x-layouts.toko title="Lacak Pesanan - SADITA Toko">
    <section class="border-b border-line bg-white px-5 py-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('toko.orders') }}" class="flex h-9 w-9 items-center justify-center rounded-xl text-primary hover:bg-surface transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-lg font-black text-primary">Pencarian Pesanan</h1>
                <p class="text-xs text-muted">Cari pesanan jika riwayat tidak muncul otomatis</p>
            </div>
        </div>
    </section>

    <section class="px-5 py-6">
        @if($error)
            <div class="mb-5 rounded-xl bg-red-50 p-4 text-sm text-red-800 border border-red-100 flex items-start gap-3 shadow-sm">
                <span class="material-symbols-outlined text-base text-red-600 shrink-0 mt-0.5">error</span>
                <p>{{ $error }}</p>
            </div>
        @endif

        <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
            <form action="{{ route('toko.track-order') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-muted">Nomor Pesanan</label>
                    <input name="order_number" value="{{ request('order_number') }}" required
                        class="h-11 w-full rounded-xl border border-line bg-surface/50 px-3 text-sm placeholder-ink focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                        placeholder="Contoh: SDT260..." />
                </div>
                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wide text-muted">No. HP / WhatsApp Pemesan</label>
                    <input name="customer_phone" value="{{ request('customer_phone') }}" required type="tel"
                        class="h-11 w-full rounded-xl border border-line bg-surface/50 px-3 text-sm placeholder-ink focus:bg-white focus:border-primary focus:outline-none focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                        placeholder="08xxxxxxxxxx" />
                </div>
                <button type="submit" class="mt-2 flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-bold text-white shadow-lg hover:shadow-xl active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    Lacak Pesanan
                </button>
            </form>
        </div>
    </section>

    @if($order)
        <section class="px-5 pb-24">
            <h2 class="text-xs font-bold uppercase tracking-wide text-muted mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[16px]">receipt_long</span>
                Hasil Pelacakan
            </h2>
            
            <article class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-4 border-b border-line pb-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-muted">No. Pesanan</p>
                        <p class="text-base font-black text-primary">{{ $order->order_number }}</p>
                        <p class="text-xs text-muted mt-1">{{ $order->created_at->translatedFormat('d M Y H:i') }}</p>
                    </div>
                    <span class="rounded-full bg-primary/10 px-3 py-1 text-[11px] font-bold text-primary border border-primary/20">{{ $order->status_label }}</span>
                </div>

                <div class="space-y-3">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-muted border-b border-dashed border-line pb-2">Informasi Pemesan</p>
                    <div class="grid grid-cols-2 gap-3 text-sm mt-2">
                        <div>
                            <p class="text-xs text-muted mb-0.5">Nama</p>
                            <p class="font-bold text-ink">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-muted mb-0.5">Pembayaran</p>
                            <p class="font-bold text-ink">{{ $order->payment_method_label }}</p>
                            <p class="text-[10px] text-muted">{{ $order->payment_status_label }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-muted mb-0.5">Alamat Pengiriman</p>
                        <p class="font-medium text-ink text-sm">{{ $order->customer_address }}</p>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-line">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-muted mb-3">Item Pesanan</p>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="min-w-0 flex-1 font-semibold text-ink line-clamp-2">
                                    {{ $item->product_name }} 
                                    <span class="text-muted ml-1 font-medium">x{{ $item->quantity }}</span>
                                </span>
                                <span class="shrink-0 font-bold text-ink">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-3 border-t border-line border-dashed flex justify-between items-center">
                        <span class="text-sm font-bold text-muted">Total Bayar</span>
                        <span class="text-lg font-black text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if ($order->status === 'pending')
                    <div class="mt-5 pt-4 border-t border-line">
                        <div class="flex gap-3">
                            <a href="{{ route('toko.orders.edit', [$order->order_number, $order->success_token]) }}" class="flex-1 flex h-11 items-center justify-center gap-2 rounded-xl border border-primary text-sm font-bold text-primary bg-primary/5">
                                <span class="material-symbols-outlined text-[17px]">edit</span>
                                Edit Pesanan
                            </a>
                            
                            @if(!$order->cancel_requested)
                                <button type="button" onclick="document.getElementById('cancel-modal').classList.remove('hidden')" class="flex-1 flex h-11 items-center justify-center gap-2 rounded-xl border border-red-500 text-sm font-bold text-red-500 bg-red-50">
                                    <span class="material-symbols-outlined text-[17px]">cancel</span>
                                    Batalkan
                                </button>
                            @else
                                <div class="flex-1 flex h-11 items-center justify-center gap-2 rounded-xl bg-orange-100 text-sm font-bold text-orange-600">
                                    <span class="material-symbols-outlined text-[17px]">pending_actions</span>
                                    Menunggu Batal
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Cancel Modal --}}
                    @if(!$order->cancel_requested)
                        <div id="cancel-modal" class="fixed inset-0 z-50 hidden bg-black/50 px-5 backdrop-blur-sm transition-opacity flex items-center justify-center">
                            <div class="w-full max-w-sm rounded-2xl bg-white p-5 shadow-2xl">
                                <h3 class="text-lg font-black text-ink mb-2">Batalkan Pesanan?</h3>
                                <p class="text-sm text-muted mb-4">Pesanan yang dibatalkan memerlukan persetujuan Admin. Berikan alasan pembatalan Anda:</p>
                                
                                <form action="{{ route('toko.orders.cancel', [$order->order_number, $order->success_token]) }}" method="POST">
                                    @csrf
                                    <textarea name="cancel_reason" required rows="3" placeholder="Contoh: Ingin ganti alamat / salah pesan barang..." class="w-full rounded-xl border border-line bg-surface/50 p-3 text-sm focus:border-primary outline-none mb-4"></textarea>
                                    
                                    <div class="flex gap-3">
                                        <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl border border-line text-sm font-bold text-ink">Kembali</button>
                                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-500 text-sm font-bold text-white shadow-lg shadow-red-500/30">Ya, Batalkan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif

                @if($waPhone)
                    <div class="mt-5 pt-4 border-t border-line">
                        <p class="text-[11px] text-muted text-center mb-3">Punya kendala dengan pesanan ini?</p>
                        <a href="https://wa.me/{{ $waPhone }}?text={{ urlencode('Halo Admin SADITA, saya ingin menanyakan status pesanan saya dengan nomor: ' . $order->order_number) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#25D366] text-sm font-bold text-white shadow-sm hover:opacity-90 active:scale-95 transition-all"
                        >
                            <svg class="h-[18px] w-[18px] fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Hubungi via WhatsApp
                        </a>
                    </div>
                @endif
            </article>
        </section>
    @endif
</x-layouts.toko>
