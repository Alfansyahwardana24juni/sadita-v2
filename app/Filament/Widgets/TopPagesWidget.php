<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Widgets\ChartWidget;
use App\Models\PageVisit;
use Illuminate\Support\Facades\DB;

class TopPagesWidget extends ChartWidget
{
    protected ?string $heading = 'Halaman Paling Sering Dikunjungi (Pie)';
    protected int | string | array $columnSpan = '1';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $pages = PageVisit::query()
            ->select('url', DB::raw('count(*) as total_kunjungan'))
            ->groupBy('url')
            ->orderByDesc('total_kunjungan')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Kunjungan',
                    'data' => $pages->pluck('total_kunjungan')->toArray(),
                    'backgroundColor' => [
                        '#8b5cf6',
                        '#ec4899',
                        '#0ea5e9',
                        '#10b981',
                        '#f59e0b',
                    ],
                ],
            ],
            'labels' => $pages->pluck('url')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
