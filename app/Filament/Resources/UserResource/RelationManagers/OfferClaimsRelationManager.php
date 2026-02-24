<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OfferClaimsRelationManager extends RelationManager
{
    protected static string $relationship = 'offerClaims';

    protected static ?string $title = 'მიღებული შეთავაზებები';

    protected static ?string $modelLabel = 'Claimed Offer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('premium_offer_id')
                    ->label('Offer')
                    ->relationship('premiumOffer', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('card_type')
                    ->label('Card Type')
                    ->options([
                        'standard' => 'Standard',
                        'premium' => 'Premium',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('discount_received')
                    ->label('Discount Received')
                    ->numeric()
                    ->required()
                    ->suffix('%'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'used' => 'Used',
                        'expired' => 'Expired',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('premiumOffer.name')
            ->columns([
                Tables\Columns\ImageColumn::make('premiumOffer.image')
                    ->label('Image')
                    ->disk('public')
                    ->size(60)
                    ->circular(),
                Tables\Columns\TextColumn::make('premiumOffer.name')
                    ->label('Offer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->premiumOffer->partner->name ?? null),
                Tables\Columns\TextColumn::make('card_type')
                    ->label('Card Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'standard' => 'gray',
                        'premium' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_received')
                    ->label('Discount')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => '-' . number_format($state, 0) . '%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('premiumOffer.p_coins_reward')
                    ->label('P-Coins')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => '+' . number_format($state) . ' P-coins')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'info',
                        'used' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('claimed_at')
                    ->label('Claimed At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('used_at')
                    ->label('Used At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Not used yet'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('card_type')
                    ->options([
                        'standard' => 'Standard',
                        'premium' => 'Premium',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'used' => 'Used',
                        'expired' => 'Expired',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('mark_as_used')
                        ->label('Mark as Used')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'used',
                                'used_at' => now(),
                            ]);
                            
                            // Award P-coins if configured
                            if ($record->premiumOffer->p_coins_reward > 0) {
                                $record->user->increment('p_coins', $record->premiumOffer->p_coins_reward);
                                
                                \Filament\Notifications\Notification::make()
                                    ->success()
                                    ->title('Offer Marked as Used')
                                    ->body("Awarded {$record->premiumOffer->p_coins_reward} P-Coins to {$record->user->name}")
                                    ->send();
                            } else {
                                \Filament\Notifications\Notification::make()
                                    ->success()
                                    ->title('Offer Marked as Used')
                                    ->send();
                            }
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('claimed_at', 'desc');
    }
}
