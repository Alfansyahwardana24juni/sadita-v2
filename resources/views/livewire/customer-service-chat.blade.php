<div>
    <!-- Header -->
    <section class="bg-white px-5 pt-8 pb-6 text-center">
        <h1 class="text-3xl font-black leading-tight tracking-tight text-primary">
            {{ __('Selamat Datang') }}<br>
            <span class="text-ink">{{ __('di Sadita Customer Service') }}</span>
        </h1>
        <p class="mt-3 text-sm leading-6 text-muted">
            {{ __('Konsultasikan kebutuhan Anda kepada tim kami.') }}
        </p>
    </section>

    <!-- Categories Segmented Control -->
    <section class="bg-white px-5 pb-4 sticky top-[72px] z-40">
        <div class="flex gap-2 overflow-x-auto no-scrollbar py-2">
            @foreach($categories as $category)
                <button 
                    wire:click="setCategory({{ $category->id }})"
                    class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-bold transition-all duration-300 {{ $activeCategoryId === $category->id ? 'bg-primary text-white shadow-md' : 'bg-surface text-slate-500 hover:bg-slate-200' }}"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </section>

    <!-- Agents List -->
    <section class="px-5 py-6 space-y-4 pb-32">
        @forelse($this->agents as $agent)
            @php
                $statusColor = match($agent->status) {
                    'online' => 'bg-moss',
                    'busy' => 'bg-amber',
                    'offline' => 'bg-slate-400',
                    default => 'bg-slate-400',
                };
                $statusText = match($agent->status) {
                    'online' => 'Online',
                    'busy' => 'Sedang Melayani',
                    'offline' => 'Offline',
                    default => 'Offline',
                };
                
                $waMessage = urlencode("Halo Kak " . $agent->name . ", saya ingin bertanya mengenai produk SADITA.");
                $waLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $agent->whatsapp_number) . "?text=" . $waMessage;
            @endphp
            
            <a href="{{ $waLink }}" target="_blank" class="group block rounded-2xl bg-white p-4 shadow-sm border border-line hover:shadow-md hover:border-primary/30 active:scale-[0.98] transition-all relative overflow-hidden cursor-pointer">
                <div class="flex items-start gap-4 relative z-10">
                    <div class="relative shrink-0">
                        @if($agent->photo)
                            <img src="{{ Storage::url($agent->photo) }}" alt="{{ $agent->name }}" class="h-16 w-16 rounded-full object-cover shadow-sm bg-surface">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-xl shadow-sm">
                                {{ substr($agent->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="absolute bottom-0 right-0 h-4 w-4 rounded-full border-2 border-white {{ $statusColor }}"></span>
                    </div>
                    
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-black text-ink truncate group-hover:text-primary transition-colors">{{ $agent->name }}</h3>
                        <p class="text-xs font-bold text-primary mt-0.5">{{ $agent->title }}</p>
                        
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center gap-1.5 text-xs text-muted">
                                <span class="material-symbols-outlined text-[14px]">work</span>
                                <span>{{ $agent->experience }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-muted">
                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                <span>{{ $agent->city }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-muted">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                <span>{{ $agent->working_hours }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-line flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full {{ $statusColor }}"></span>
                        <span class="text-[11px] font-bold {{ $agent->status === 'online' ? 'text-moss' : ($agent->status === 'busy' ? 'text-amber' : 'text-slate-500') }}">{{ $statusText }}</span>
                    </div>
                    
                    <button class="inline-flex h-9 items-center gap-1.5 rounded-xl px-4 text-xs font-bold transition-colors {{ $agent->status === 'offline' ? 'bg-surface text-slate-600 hover:bg-slate-200' : 'bg-primary/10 text-primary hover:bg-primary hover:text-white' }}">
                        @if($agent->status === 'offline')
                            Lihat Admin Lain
                        @else
                            <span class="material-symbols-outlined text-[16px]">chat</span>
                            Chat Sekarang
                        @endif
                    </button>
                </div>
            </a>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-surface/50 p-8 text-center">
                <span class="material-symbols-outlined text-[48px] text-slate-300 mb-3">support_agent</span>
                <p class="text-sm font-bold text-slate-500">Tidak ada petugas yang tersedia di kategori ini.</p>
                <p class="mt-1 text-xs text-slate-400">Silakan pilih kategori lain.</p>
            </div>
        @endforelse
    </section>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</div>
