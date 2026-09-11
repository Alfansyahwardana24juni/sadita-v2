<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur rounded-t-[32px]">

    <div class="flex items-center justify-between px-4 py-2">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center">
            <span class="">
                <img
                    src="{{ asset('images/logo sadita.png') }}"
                    alt="SADITA"
                    class="h-8 w-auto object-contain"
                >
            </span>
        </a>

        {{-- Right Menu --}}
        <div class="flex items-center gap-3">

            <x-language-switcher />

           {{-- Toko / Gudang --}}
<a
    href="{{ route('toko.home') }}"
    class="relative flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-white shadow-sm transition hover:opacity-90"
    aria-label="Buka Toko SADITA"
>
    <span class="material-symbols-outlined text-[20px]">
        storefront
    </span>
    <span data-cart-count class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-amber text-[8px] font-bold text-white" style="display:none;">0</span>
</a>

            {{-- Hamburger --}}
            <button
                id="menuToggle"
                onclick="toggleDrawer(true)"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-primary hover:bg-surface"
            >
                <span class="material-symbols-outlined text-[22px]">
                    menu
                </span>
            </button>

        </div>

    </div>
</header>

{{-- Mobile Menu Drawer --}}
<div id="drawerOverlay" class="fixed inset-0 z-[100] hidden bg-black/45 opacity-0 transition-opacity duration-300" onclick="toggleDrawer(false)">
        <div id="drawerPanel" class="absolute right-0 top-0 flex h-full w-[80vw] max-w-[300px] translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-in-out" onclick="event.stopPropagation()">
            <!-- Header Drawer -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo sadita.png') }}" alt="SADITA" class="h-6 w-auto">
                    <span class="text-sm font-black text-primary">Menu SADITA</span>
                </div>
                <button onclick="toggleDrawer(false)" class="flex h-8 w-8 items-center justify-center rounded-lg text-primary hover:bg-slate-100 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Menu Items -->
            <nav class="flex-1 overflow-y-auto px-5 py-5 space-y-2">
                @php
                    $menus = [
                        ['label' => __('Beranda'), 'icon' => 'home', 'route' => 'home'],
                        ['label' => __('Tentang'), 'icon' => 'apartment', 'route' => 'tentang'],
                        ['label' => __('Produk'), 'icon' => 'inventory_2', 'route' => 'produk'],
                        ['label' => __('Artikel'), 'icon' => 'article', 'route' => 'artikel'],
                        ['label' => __('AI Konsultasi'), 'icon' => 'forum', 'route' => 'saditacare'],
                        ['label' => __('Gudang'), 'icon' => 'warehouse', 'route' => 'toko.home'],
                        ['label' => __('Chat'), 'icon' => 'chat_bubble', 'route' => 'chat'],
                    ];
                    $current = Route::currentRouteName();
                @endphp

                @foreach($menus as $menu)
                    @php
                        $isActive = request()->routeIs($menu['route'] . '*');
                        if ($menu['route'] == 'home') {
                            $isActive = $current == 'home';
                        }
                    @endphp
                    <a href="{{ route($menu['route']) }}" class="group flex h-[44px] items-center gap-3 rounded-lg px-3 transition-all duration-200 {{ $isActive ? 'bg-[#800000]/5 text-[#800000]' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                        <span class="material-symbols-outlined text-[20px] {{ $isActive ? 'text-[#800000]' : 'text-slate-400 group-hover:text-primary' }}" @if($isActive) style="font-variation-settings:'FILL' 1" @endif>{{ $menu['icon'] }}</span>
                        <span class="text-[13px] {{ $isActive ? 'font-semibold' : 'font-medium' }}">{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    <script>
        function toggleDrawer(show) {
            const drawerOverlay = document.getElementById('drawerOverlay');
            const drawerPanel = document.getElementById('drawerPanel');
            if (show) {
                drawerOverlay.classList.remove('hidden');
                // Allow display:block to apply before animating opacity/transform
                requestAnimationFrame(() => {
                    drawerOverlay.classList.remove('opacity-0');
                    drawerPanel.classList.remove('translate-x-full');
                });
                document.body.style.overflow = 'hidden';
            } else {
                drawerOverlay.classList.add('opacity-0');
                drawerPanel.classList.add('translate-x-full');
                setTimeout(() => {
                    drawerOverlay.classList.add('hidden');
                }, 300);
                document.body.style.overflow = '';
            }
        }
    </script>