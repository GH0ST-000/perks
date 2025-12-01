<?php

namespace App\Filament\Resources\CompanyRequestResource\Pages;

use App\Filament\Resources\CompanyRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyRequests extends ListRecords
{
    protected static string $resource = CompanyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
