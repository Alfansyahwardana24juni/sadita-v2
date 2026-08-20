<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return new \Illuminate\Support\HtmlString(
            '<div class="flex items-center gap-2">
                <span>Penjualan 7 Hari Terakhir</span>
                <span title="Grafik ini menampilkan total nilai pesanan (dalam Rupiah) yang dibuat setiap harinya selama 7 hari ke belakang (tidak termasuk pesanan yang dibatalkan)." class="material-symbols-outlined text-[16px] text-gray-400 cursor-help" style="font-size: 16px;">info</span>
            </div>'
        );
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            
            $data[] = Order::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->sum('total');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => $data,
                    'backgroundColor' => '#800000',
                    'borderColor' => '#800000',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
