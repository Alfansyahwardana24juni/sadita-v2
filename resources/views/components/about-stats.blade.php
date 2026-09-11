@php
    $stats = [
        ['image' => 'images/stats/10-tahun-pengalaman.png', 'label' => '10 Tahun Pengalaman'],
        ['image' => 'images/stats/200-produk.png', 'label' => '200+ Berbagai Produk'],
        ['image' => 'images/stats/mitra-klien.png', 'label' => 'Klien & Mitra di Seluruh Indonesia'],
        ['image' => 'images/stats/inovasi-produk.png', 'label' => 'Inovasi Produk Berkelanjutan'],
    ];
@endphp

<div {{ $attributes->merge(['class' => 'grid grid-cols-2 gap-3']) }}>
    @foreach($stats as $stat)
        <div class="flex flex-col items-center gap-3 rounded-2xl border border-line bg-surface p-4 text-center">
            <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white shadow-sm">
                <img src="{{ asset($stat['image']) }}" alt="{{ $stat['label'] }}" class="h-14 w-14 object-contain"
                    loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/120x120/F1F5F9/94A3B8?text=SADITA';">
            </div>
            <p class="text-sm font-bold leading-5 text-ink">{{ $stat['label'] }}</p>
        </div>
    @endforeach
</div>
