<?php

namespace App\Filament\Resources\CustomerNotificationResource\Widgets;

use App\Models\CustomerNotification;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class CustomerStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        $query = CustomerNotification::query();

        // Filter by manager's company if not admin
        if ($user->role === 'manager') {
            $query->where('company_id', $user->company_id)
                  ->where('manager_id', $user->id);
        }

        $totalNotifications = (clone $query)->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $verifiedCount = (clone $query)->where('status', 'verified')->count();
        $usedCount = (clone $query)->where('status', 'used')->count();
        $totalDiscount = (clone $query)->where('status', 'used')->sum('discount');
        $todayCount = (clone $query)->whereDate('created_at', today())->count();

        return [
            Stat::make('Total Notifications', $totalNotifications)
                ->description('All time')
                ->descriptionIcon('heroicon-m-bell')
                ->color('primary'),

            Stat::make('Pending', $pendingCount)
                ->description('Awaiting verification')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Verified', $verifiedCount)
                ->description('Ready to use')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Used', $usedCount)
                ->description('Discounts claimed')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('danger'),

            Stat::make('Total Discount Given', number_format($totalDiscount, 2) . '%')
                ->description('All time')
                ->descriptionIcon('heroicon-m-gift')
                ->color('info'),

            Stat::make('Today', $todayCount)
                ->description('Sent today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
        ];
    }
}
