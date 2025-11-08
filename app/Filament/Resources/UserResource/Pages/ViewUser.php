<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('add_pcoins')
                    ->label('Add P-Coins')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        \Filament\Forms\Components\Textarea::make('reason')
                            ->label('Reason')
                            ->rows(3)
                            ->placeholder('e.g., Promotion bonus, Purchase, etc.'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->increment('p_coins', $data['amount']);
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('P-Coins Added')
                            ->body("Added {$data['amount']} P-Coins to {$record->name}")
                            ->send();
                    }),
                Actions\Action::make('deduct_pcoins')
                    ->label('Deduct P-Coins')
                    ->icon('heroicon-o-minus-circle')
                    ->color('danger')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(fn ($record) => $record->p_coins),
                        \Filament\Forms\Components\Textarea::make('reason')
                            ->label('Reason')
                            ->rows(3)
                            ->placeholder('e.g., Used for purchase, Deduction, etc.'),
                    ])
                    ->action(function ($record, array $data) {
                        if ($record->p_coins >= $data['amount']) {
                            $record->decrement('p_coins', $data['amount']);
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('P-Coins Deducted')
                                ->body("Deducted {$data['amount']} P-Coins from {$record->name}")
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Insufficient P-Coins')
                                ->body("User only has {$record->p_coins} P-Coins")
                                ->send();
                        }
                    }),
                Actions\Action::make('send_offer')
                    ->label('Send Offer')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->form([
                        \Filament\Forms\Components\Select::make('offer_id')
                            ->label('Premium Offer')
                            ->options(\App\Models\PremiumOffer::where('day_left', '>', 0)->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('message')
                            ->label('Personal Message')
                            ->rows(3)
                            ->placeholder('Optional personalized message'),
                    ])
                    ->action(function ($record, array $data) {
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Offer Sent')
                            ->body("Offer sent to {$record->name}")
                            ->send();
                    }),
            ]),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Livewire\MostVisitedCategories::class,
        ];
    }
}
