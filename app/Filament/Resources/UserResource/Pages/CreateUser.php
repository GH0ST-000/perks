<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\PartnerResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use App\Support\PhoneNumber;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_partner')
                ->label('პარტნიორ კომპანიის დამატება')
                ->icon('heroicon-o-building-storefront')
                ->color('gray')
                ->url(PartnerResource::getUrl('create')),
        ];
    }

    protected function beforeCreate(): void
    {
        $state = $this->form->getState();
        $phone = PhoneNumber::normalize($state['phone'] ?? '');

        if (User::where('phone', $phone)->exists()) {
            throw ValidationException::withMessages([
                'data.phone' => 'ეს ტელეფონის ნომერი უკვე რეგისტრირებულია.',
            ]);
        }

        if (empty($state['company_id'])) {
            throw ValidationException::withMessages([
                'data.company_id' => 'აირჩიეთ კომპანია.',
            ]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! empty($data['phone'])) {
            $data['phone'] = PhoneNumber::normalize($data['phone']);
        }

        if (empty($data['password'])) {
            $data['password'] = bcrypt(Str::random(32));
        }

        if (! empty($data['email_verified'])) {
            $data['email_verified_at'] = now();
        }

        unset($data['email_verified']);

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'კორპორატიული მომხმარებელი დაემატა — შეძლებს შესვლას ტელეფონის OTP-ით';
    }
}
