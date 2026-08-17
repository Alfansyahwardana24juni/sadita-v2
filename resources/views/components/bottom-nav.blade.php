<nav aria-label="Menu utama" class="relative z-50 flex h-[64px] w-full items-center justify-between rounded-t-3xl sm:rounded-b-3xl bg-white px-2 shadow-[0_-8px_30px_rgba(0,0,0,0.06)] sm:shadow-[0_8px_30px_rgba(0,0,0,0.08)] border-t border-slate-100 sm:border">
    
    <div class="flex flex-1 items-center justify-around">
        <a href="{{ route('home') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[24px] {{ request()->routeIs('home') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}" @if(request()->routeIs('home')) style="font-variation-settings:'FILL' 1" @endif>home</span>
            <span class="text-[9px] font-semibold {{ request()->routeIs('home') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Beranda') }}</span>
        </a>
        <a href="{{ route('tentang') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[24px] {{ request()->routeIs('tentang') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}" @if(request()->routeIs('tentang')) style="font-variation-settings:'FILL' 1" @endif>apartment</span>
            <span class="text-[9px] font-semibold {{ request()->routeIs('tentang') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Tentang') }}</span>
        </a>
    </div>
    
    <!-- Tombol Tengah (Produk) dengan efek Cutout -->
    <div class="relative flex w-[64px] justify-center shrink-0">
        <div class="absolute -top-[42px] flex h-[60px] w-[60px] items-center justify-center rounded-full bg-white shadow-[0_-6px_15px_rgba(0,0,0,0.04)]">
            <a href="{{ route('produk') }}" class="flex h-[50px] w-[50px] flex-col items-center justify-center rounded-full transition-transform hover:scale-105 active:scale-95 {{ request()->routeIs('produk*') ? 'bg-[#800000] text-white shadow-lg shadow-[#800000]/30' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                <span class="material-symbols-outlined text-[24px]" @if(request()->routeIs('produk*')) style="font-variation-settings:'FILL' 1" @endif>inventory_2</span>
            </a>
        </div>
    </div>

    <div class="flex flex-1 items-center justify-around">
        <a href="{{ route('artikel') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[24px] {{ request()->routeIs('artikel*') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}" @if(request()->routeIs('artikel*')) style="font-variation-settings:'FILL' 1" @endif>article</span>
            <span class="text-[9px] font-semibold {{ request()->routeIs('artikel*') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Artikel') }}</span>
        </a>
        <a href="{{ route('chat') }}" class="group flex flex-col items-center gap-1 p-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined text-[24px] {{ request()->routeIs('chat') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}" @if(request()->routeIs('chat')) style="font-variation-settings:'FILL' 1" @endif>chat_bubble</span>
            <span class="text-[9px] font-semibold {{ request()->routeIs('chat') ? 'text-[#800000]' : 'text-slate-400 group-hover:text-slate-600' }}">{{ __('Chat') }}</span>
        </a>
    </div>
</nav>