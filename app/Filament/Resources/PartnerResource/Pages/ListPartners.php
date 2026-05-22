<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartners extends ListRecords
{
    protected static string $resource = PartnerResource::class;

    public function getSubheading(): ?string
    {
        return 'პარტნიორი ბიზნესები შეთავაზებებისა და ფასდაკლებებისთვის. საიტზე შესვლადი თანამშრომლები ცალკე მენიუშია.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_user')
                ->label('მომხმარებლის დამატება')
                ->icon('heroicon-o-user-plus')
                ->color('gray')
                ->url(UserResource::getUrl('create')),
            Actions\CreateAction::make()
                ->label('პარტნიორ კომპანიის დამატება')
                ->icon('heroicon-o-building-storefront'),
        ];
    }
}
