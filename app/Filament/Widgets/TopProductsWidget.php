<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Widgets\ChartWidget;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends ChartWidget
{
    protected ?string $heading = 'Produk Terlaris (Grafik)';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $products = Product::query()
            ->withCount(['orderItems as total_terjual' => function ($query) {
                $query->select(DB::raw('IFNULL(SUM(quantity), 0)'));
            }])
            ->orderByDesc('total_terjual')
            ->limit(7)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Terjual',
                    'data' => $products->pluck('total_terjual')->toArray(),
                    'backgroundColor' => '#0ea5e9',
                ],
            ],
            'labels' => $products->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
