<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\PartnerResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getSubheading(): ?string
    {
        return 'მომხმარებლები — კომპანიის თანამშრომლები OTP შესვლით. პარტნიორ კომპანიები ცალკე მენიუშია.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_partner')
                ->label('პარტნიორ კომპანიის დამატება')
                ->icon('heroicon-o-building-storefront')
                ->color('gray')
                ->url(PartnerResource::getUrl('create')),
            Actions\CreateAction::make()
                ->label('მომხმარებლის დამატება')
                ->icon('heroicon-o-user-plus'),
        ];
    }
}
