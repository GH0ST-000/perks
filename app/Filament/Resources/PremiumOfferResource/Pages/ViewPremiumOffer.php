<?php

namespace App\Filament\Resources\PremiumOfferResource\Pages;

use App\Filament\Resources\PremiumOfferResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPremiumOffer extends ViewRecord
{
    protected static string $resource = PremiumOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
