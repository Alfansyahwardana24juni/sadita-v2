<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Penjualan', 'Rp ' . number_format(\App\Models\Order::where('status', 'delivered')->sum('total'), 0, ',', '.'))
                ->description('Total omzet dari pesanan selesai')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Request Chat AI', \App\Models\ConsultationLog::count())
                ->description('Sesi konsultasi SaditaCare')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary'),
            Stat::make('Total Kunjungan Website', \App\Models\PageVisit::count())
                ->description('Total hit/kunjungan halaman')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),
        ];
    }
}
