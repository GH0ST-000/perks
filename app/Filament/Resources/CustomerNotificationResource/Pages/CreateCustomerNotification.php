<?php

namespace App\Filament\Resources\CustomerNotificationResource\Pages;

use App\Filament\Resources\CustomerNotificationResource;
use App\Services\SmsService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateCustomerNotification extends CreateRecord
{
    protected static string $resource = CustomerNotificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        // For managers, always use their company_id
        if ($user->role === 'manager') {
            if (!$user->company_id) {
                Notification::make()
                    ->danger()
                    ->title('Company Required')
                    ->body('You must be assigned to a company before sending notifications. Please contact an administrator.')
                    ->persistent()
                    ->send();

                $this->halt();
            }
            $data['company_id'] = $user->company_id;
        }

        // For admins, company_id should come from the form
        // But if it's missing, throw an error
        if ($user->role === 'admin' && empty($data['company_id'])) {
            throw new \Exception('Company is required');
        }

        // Ensure manager_id is always the current user
        $data['manager_id'] = $user->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        // Send SMS after creating the notification
        $smsService = app(SmsService::class);
        $record = $this->record;

        try {
            $smsService->sendVerificationCode(
                $record->customer_phone,
                $record->verification_code
            );

            Notification::make()
                ->success()
                ->title('SMS Sent')
                ->body("Verification code sent to {$record->customer_phone}")
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->warning()
                ->title('Notification Created')
                ->body('Record created but SMS failed to send. Check logs.')
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Customer notification created';
    }
}
