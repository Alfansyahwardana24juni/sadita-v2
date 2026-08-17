<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Widgets\ChartWidget;
use App\Models\Warehouse;

class TopBranchesWidget extends ChartWidget
{
    protected ?string $heading = 'Cabang Terbanyak Transaksi (Donut)';
    protected int | string | array $columnSpan = '1';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $branches = Warehouse::query()
            ->withCount('orders as total_transaksi')
            ->orderByDesc('total_transaksi')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Transaksi',
                    'data' => $branches->pluck('total_transaksi')->toArray(),
                    'backgroundColor' => [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                    ],
                ],
            ],
            'labels' => $branches->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
