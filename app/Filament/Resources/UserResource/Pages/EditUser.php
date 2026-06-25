<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Support\PhoneNumber;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function beforeSave(): void
    {
        $state = $this->form->getState();
        $phone = PhoneNumber::normalize($state['phone'] ?? '');

        if (User::where('phone', $phone)->where('id', '!=', $this->record->id)->exists()) {
            throw ValidationException::withMessages([
                'data.phone' => 'ეს ტელეფონის ნომერი უკვე რეგისტრირებულია.',
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! empty($data['phone'])) {
            $data['phone'] = PhoneNumber::normalize($data['phone']);
        }

        if (! empty($data['email_verified'])) {
            $data['email_verified_at'] = now();
        } elseif (array_key_exists('email_verified', $data) && ! $data['email_verified']) {
            $data['email_verified_at'] = null;
        }

        unset($data['email_verified']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
