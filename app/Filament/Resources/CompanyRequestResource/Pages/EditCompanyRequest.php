<?php

namespace App\Filament\Resources\CompanyRequestResource\Pages;

use App\Filament\Resources\CompanyRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyRequest extends EditRecord
{
    protected static string $resource = CompanyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
