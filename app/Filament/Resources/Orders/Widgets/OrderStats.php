<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use DB;

class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $orders_count = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        $avg_grand_total = Order::avg('grand_total');
        return [
            Stat::make('New Orders', $orders_count['new'] ?? '-'),
            Stat::make('Processing Orders', $orders_count['processing'] ?? '-'),
            Stat::make('Orders Shipped', $orders_count['shipped'] ?? '-'),
            Stat::make('Average Order Amount', number_format($avg_grand_total, 2) ?? '-'),
        ];
    }
}
