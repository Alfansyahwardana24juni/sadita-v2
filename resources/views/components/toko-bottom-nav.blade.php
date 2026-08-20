@php
    $isTokoHome = request()->routeIs('toko.home');
    $isKatalog = request()->routeIs('toko.katalog') || request()->routeIs('toko.produk.*');
    $isCart = request()->routeIs('cart.index');
    $isOrder = request()->routeIs('checkout') || request()->routeIs('checkout.*') || request()->routeIs('toko.orders') || request()->routeIs('toko.track-order');
    
    // Get WA number for CS
    $storeSetting = \App\Models\StoreSetting::active();
    $waPhone = $storeSetting ? ltrim($storeSetting->whatsapp_number, '0') : '';
    if ($waPhone && !str_starts_with($waPhone, '62')) {
        $waPhone = '62' . $waPhone;
    }
@endphp

<nav class="relative z-50 flex h-[64px] w-full items-center justify-between rounded-t-3xl sm:rounded-b-3xl bg-white px-2 shadow-[0_-8px_30px_rgba(0,0,0,0.06)] sm:shadow-[0_8px_30px_rgba(0,0,0,0.08)] border-t border-slate-100 sm:border">
    <div class="flex flex-1 items-center justify-around">
        <a href="{{ route('toko.home') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[24px] {{ $isTokoHome ? 'text-primary' : 'text-slate-400 group-hover:text-slate-600' }}" @if($isTokoHome) style="font-variation-settings:'FILL' 1" @endif>store</span>
            <span class="text-[9px] font-semibold {{ $isTokoHome ? 'text-primary' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Toko') }}</span>
        </a>

        <a href="{{ route('toko.katalog') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[24px] {{ $isKatalog ? 'text-primary' : 'text-slate-400 group-hover:text-slate-600' }}" @if($isKatalog) style="font-variation-settings:'FILL' 1" @endif>grid_view</span>
            <span class="text-[9px] font-semibold {{ $isKatalog ? 'text-primary' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Katalog') }}</span>
        </a>
    </div>

    <!-- Tombol Tengah (Keranjang) dengan efek Cutout -->
    <div class="relative flex w-[64px] justify-center shrink-0">
        <div class="absolute -top-[42px] flex h-[60px] w-[60px] items-center justify-center rounded-full bg-white shadow-[0_-6px_15px_rgba(0,0,0,0.04)]">
            <a href="{{ route('cart.index') }}" class="flex h-[50px] w-[50px] flex-col items-center justify-center rounded-full transition-transform hover:scale-105 active:scale-95 {{ $isCart ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                <span class="material-symbols-outlined text-[24px]" @if($isCart) style="font-variation-settings:'FILL' 1" @endif>shopping_cart</span>
            </a>
            <span data-cart-count class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-amber text-[9px] font-bold text-white shadow-sm" style="display:none;">0</span>
        </div>
    </div>

    <div class="flex flex-1 items-center justify-around">
        <a href="{{ route('toko.orders') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[24px] {{ $isOrder ? 'text-primary' : 'text-slate-400 group-hover:text-slate-600' }}" @if($isOrder) style="font-variation-settings:'FILL' 1" @endif>receipt_long</span>
            <span class="text-[9px] font-semibold {{ $isOrder ? 'text-primary' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Pesanan') }}</span>
        </a>

        <a href="https://wa.me/{{ $waPhone }}?text=Halo%20Admin%20SADITA,%20saya%20butuh%20bantuan%20terkait%20Toko." target="_blank" rel="noopener noreferrer" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[24px] text-slate-400 group-hover:text-slate-600">support_agent</span>
            <span class="text-[9px] font-semibold text-slate-400 group-hover:text-slate-600">{{ __('CS Bantuan') }}</span>
        </a>
    </div>
</nav>
