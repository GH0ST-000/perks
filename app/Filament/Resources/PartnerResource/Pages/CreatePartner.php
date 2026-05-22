<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use App\Filament\Resources\UserResource;
use App\Services\PartnerAccountService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePartner extends CreateRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_user')
                ->label('მომხმარებლის დამატება')
                ->icon('heroicon-o-user-plus')
                ->color('gray')
                ->url(UserResource::getUrl('create')),
        ];
    }

    protected function afterCreate(): void
    {
        $user = app(PartnerAccountService::class)->syncLoginUser($this->record);

        if ($user) {
            Notification::make()
                ->success()
                ->title('პარტნიორ კომპანია დაემატა')
                ->body('შესვლის ანგარიში შეიქმნა — OTP შესვლა ტელეფონით ხელმისაწვდომია.')
                ->send();
        } else {
            Notification::make()
                ->warning()
                ->title('პარტნიორი შეიქმნა, მაგრამ შესვლა ვერ მოეწყო')
                ->body('ეს ტელეფონის ნომერი უკვე გამოიყენება სხვა მომხმარებლის მიერ.')
                ->send();
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return null;
    }
}
