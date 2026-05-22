<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use App\Services\PartnerAccountService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(fn () => app(PartnerAccountService::class)->removeLoginUser($this->record)),
        ];
    }

    protected function afterSave(): void
    {
        $user = app(PartnerAccountService::class)->syncLoginUser($this->record);

        if ($user) {
            Notification::make()
                ->success()
                ->title('შესვლის ანგარიში განახლდა')
                ->body('პარტნიორმა შეძლებს OTP შესვლას დამატებული ტელეფონით.')
                ->send();
        }
    }
}
