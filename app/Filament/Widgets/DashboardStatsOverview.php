<?php

namespace App\Filament\Widgets;

use App\Models\ConsultationLog;
use App\Models\Order;
use App\Models\PageVisit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // === 1. Total Pendapatan ===
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $revenueThisMonth = Order::where('status', 'delivered')
            ->where('created_at', '>=', $thisMonth)
            ->sum('total');
            
        $revenueLastMonth = Order::where('status', 'delivered')
            ->where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $thisMonth)
            ->sum('total');

        $revenueIncrease = $revenueLastMonth > 0 
            ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 
            : 100;
        
        $revenueDesc = $revenueIncrease >= 0 ? number_format($revenueIncrease, 1) . '% naik dari bulan lalu' : number_format(abs($revenueIncrease), 1) . '% turun dari bulan lalu';
        $revenueIcon = $revenueIncrease >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $revenueColor = $revenueIncrease >= 0 ? 'success' : 'danger';

        // === 2. Pengunjung Website (Unik per Session/Device) ===
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Count unique sessions (users/devices)
        $visitorsToday = PageVisit::where('created_at', '>=', $today)->distinct('session_id')->count('session_id');
        $visitorsWeek = PageVisit::where('created_at', '>=', $startOfWeek)->distinct('session_id')->count('session_id');
        $visitorsMonth = PageVisit::where('created_at', '>=', $startOfMonth)->distinct('session_id')->count('session_id');

        $visitorDescription = "Minggu ini: $visitorsWeek | Bulan ini: $visitorsMonth (Unique/User)";

        // === 3. Pesanan Menunggu (Pending) ===
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // === 4. Total Chat AI ===
        $chatAiToday = ConsultationLog::where('created_at', '>=', $today)->count();
        $chatAiMonth = ConsultationLog::where('created_at', '>=', $startOfMonth)->count();

        return [
            Stat::make('Total Omzet (Bulan Ini)', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
                ->description($revenueDesc)
                ->descriptionIcon($revenueIcon)
                ->color($revenueColor)
                ->extraAttributes([
                    'title' => 'Menampilkan total omzet dari pesanan yang sudah selesai (Delivered) pada bulan ini.',
                ]),

            Stat::make('Pengunjung Unik (Hari Ini)', $visitorsToday . ' User')
                ->description($visitorDescription)
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->extraAttributes([
                    'title' => 'Dihitung berdasarkan session/device unik per user, BUKAN per refresh halaman. Angka ini menunjukkan jumlah orang asli yang mengunjungi website.',
                ]),

            Stat::make('Pesanan Perlu Diproses', $pendingOrders)
                ->description('Pesanan status Pending')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($pendingOrders > 0 ? 'warning' : 'success')
                ->extraAttributes([
                    'title' => 'Jumlah pesanan baru yang masuk dan belum diproses oleh Admin.',
                ]),
                
            Stat::make('Konsultasi AI (Hari Ini)', $chatAiToday . ' Sesi')
                ->description('Total bulan ini: ' . $chatAiMonth . ' sesi')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info')
                ->extraAttributes([
                    'title' => 'Jumlah sesi konsultasi SaditaCare AI yang dilakukan pelanggan hari ini.',
                ]),
        ];
    }
}
