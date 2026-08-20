<x-layouts.toko title="Edit Pesanan - SADITA">
    <div class="px-5 py-6">
        <h1 class="text-xl font-black text-primary">Edit Pesanan</h1>
        <p class="mt-1 text-sm text-muted">No: {{ $order->order_number }}</p>

        @if(session('error'))
            <div class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-600 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('toko.orders.update', [$order->order_number, $order->success_token]) }}" method="POST" class="mt-6 space-y-5">
            @csrf
            
            <div class="bg-white rounded-2xl border border-line p-5">
                <h2 class="text-xs font-black text-ink mb-3 uppercase tracking-wider">Detail Pengiriman</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-ink mb-1.5">Nama Penerima</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required class="w-full rounded-xl border border-line bg-surface/50 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-ink mb-1.5">No. WhatsApp</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}" required class="w-full rounded-xl border border-line bg-surface/50 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-ink mb-1.5">Alamat Lengkap</label>
                        <textarea name="customer_address" required rows="3" class="w-full rounded-xl border border-line bg-surface/50 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">{{ old('customer_address', $order->customer_address) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-ink mb-1.5">Catatan (Opsional)</label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border border-line bg-surface/50 px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-line p-5">
                <h2 class="text-xs font-black text-ink mb-3 uppercase tracking-wider">Metode Pembayaran</h2>
                <div class="space-y-2.5">
                    @foreach($paymentMethods as $index => $pm)
                        @if($pm['type'] === 'bank')
                            <label class="group flex flex-col gap-2 rounded-xl border-2 border-line bg-white p-3 cursor-pointer hover:border-primary/40 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="transfer_{{ $index }}" @checked(old('payment_method', $order->payment_method) == 'transfer_'.$index || (str_starts_with($order->payment_method, 'transfer') && $loop->first)) required class="text-primary w-4 h-4">
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-ink">Transfer Bank {{ $pm['bank_name'] ?? '' }}</p>
                                    </div>
                                </div>
                            </label>
                        @elseif($pm['type'] === 'qris')
                            <label class="group flex flex-col gap-2 rounded-xl border-2 border-line bg-white p-3 cursor-pointer hover:border-primary/40 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="qris_{{ $index }}" @checked(old('payment_method', $order->payment_method) == 'qris_'.$index || (str_starts_with($order->payment_method, 'qris') && $loop->first)) required class="text-primary w-4 h-4">
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-ink">QRIS</p>
                                    </div>
                                </div>
                            </label>
                        @endif
                    @endforeach
                    <label class="group flex flex-col gap-2 rounded-xl border-2 border-line bg-white p-3 cursor-pointer hover:border-primary/40 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="cod" @checked(old('payment_method', $order->payment_method) === 'cod') required class="text-primary w-4 h-4">
                            <div class="flex-1">
                                <p class="font-bold text-sm text-ink">COD (Bayar di Tempat)</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ url()->previous() }}" class="flex-1 text-center py-3 rounded-xl border border-line text-sm font-bold text-ink bg-white">Batal</a>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-primary text-sm font-bold text-white shadow-lg shadow-primary/20">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-layouts.toko>
