<header class="sticky top-0 z-50 flex h-16 items-center justify-between border-b border-line bg-white/95 px-5 backdrop-blur">
    <a href="{{ route('home') }}" class="leading-tight">
        <span class="block text-lg font-black tracking-tight text-primary">SADITA</span>
        <span class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-muted">Animal Health</span>
    </a>
    <div class="flex items-center gap-2">
        {{-- Cart icon with badge --}}
        <a href="{{ route('cart.index') }}" class="relative flex h-10 w-10 items-center justify-center rounded-xl text-primary" aria-label="Keranjang belanja">
            <span class="material-symbols-outlined">shopping_cart</span>
            <span data-cart-count class="absolute -right-0.5 -top-0.5 hidden h-4 min-w-4 items-center justify-center rounded-full bg-primary text-[9px] font-bold text-white px-1">0</span>
        </a>
        <x-language-switcher />
        <a href="{{ route('toko.home') }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-white" aria-label="Buka toko SADITA">
            <span class="material-symbols-outlined">storefront</span>
        </a>
    </div>
</header>

{{-- Cart toast notification --}}
<div id="cart-toast" class="fixed top-20 left-1/2 -translate-x-1/2 z-[100] hidden rounded-xl bg-ink px-4 py-2 text-xs font-bold text-white opacity-0 transition-opacity duration-300 shadow-lg">
    Ditambahkan ke keranjang
</div>
