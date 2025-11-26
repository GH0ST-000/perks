<?php

namespace App\Filament\Resources\CustomerNotificationResource\Pages;

use App\Filament\Resources\CustomerNotificationResource;
use App\Filament\Resources\CustomerNotificationResource\Widgets\CustomerStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerNotifications extends ListRecords
{
    protected static string $resource = CustomerNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Send Verification Code')
                ->icon('heroicon-o-paper-airplane'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerStatsWidget::class,
        ];
    }
}
