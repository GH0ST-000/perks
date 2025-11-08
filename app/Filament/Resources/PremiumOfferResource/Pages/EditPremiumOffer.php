<?php

namespace App\Filament\Resources\PremiumOfferResource\Pages;

use App\Filament\Resources\PremiumOfferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPremiumOffer extends EditRecord
{
    protected static string $resource = PremiumOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
