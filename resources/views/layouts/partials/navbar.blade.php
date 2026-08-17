<nav class="relative z-50 flex h-[72px] w-full items-center justify-between rounded-t-3xl bg-white px-2 shadow-[0_-8px_30px_rgba(0,0,0,0.06)] border-t border-slate-100">
    
    <div class="flex flex-1 items-center justify-around">
        <a href="{{ route('home') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[26px] {{ request()->routeIs('home') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}" @if(request()->routeIs('home')) style="font-variation-settings:'FILL' 1" @endif>home</span>
            <span class="text-[10px] font-semibold {{ request()->routeIs('home') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Beranda') }}</span>
        </a>
        <a href="{{ route('produk') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[26px] {{ request()->routeIs('produk*') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}" @if(request()->routeIs('produk*')) style="font-variation-settings:'FILL' 1" @endif>inventory_2</span>
            <span class="text-[10px] font-semibold {{ request()->routeIs('produk*') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Produk') }}</span>
        </a>
    </div>
    
    <!-- Tombol Tengah (QR Scanner) dengan efek Cutout -->
    <div class="relative flex w-[72px] justify-center">
        <div class="absolute -top-[36px] flex h-[68px] w-[68px] items-center justify-center rounded-full bg-white shadow-[0_-6px_15px_rgba(0,0,0,0.04)]">
            <button class="flex h-[56px] w-[56px] items-center justify-center rounded-full bg-[#800000] text-white shadow-lg transition-transform hover:scale-105 active:scale-95">
                <span class="material-symbols-outlined text-[28px]">qr_code_scanner</span>
            </button>
        </div>
    </div>

    <div class="flex flex-1 items-center justify-around">
        <a href="{{ route('artikel') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[26px] {{ request()->routeIs('artikel*') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}" @if(request()->routeIs('artikel*')) style="font-variation-settings:'FILL' 1" @endif>article</span>
            <span class="text-[10px] font-semibold {{ request()->routeIs('artikel*') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Artikel') }}</span>
        </a>
        <a href="{{ route('chat') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[26px] {{ request()->routeIs('chat') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}" @if(request()->routeIs('chat')) style="font-variation-settings:'FILL' 1" @endif>chat_bubble</span>
            <span class="text-[10px] font-semibold {{ request()->routeIs('chat') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Chat') }}</span>
        </a>
    </div>
</nav>
