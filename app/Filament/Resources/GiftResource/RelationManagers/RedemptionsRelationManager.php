<?php

namespace App\Filament\Resources\GiftResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RedemptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'redemptions';

    protected static ?string $title = 'Gift Redemptions';

    protected static ?string $recordTitleAttribute = 'redemption_code';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('p_coins_spent')
                    ->label('P Coins Spent')
                    ->numeric()
                    ->required()
                    ->suffix('P'),
                Forms\Components\TextInput::make('redemption_code')
                    ->label('Redemption Code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'used' => 'Used',
                        'expired' => 'Expired',
                    ])
                    ->default('completed')
                    ->required(),
                Forms\Components\DateTimePicker::make('redeemed_at')
                    ->label('Redeemed At')
                    ->default(now()),
                Forms\Components\DateTimePicker::make('used_at')
                    ->label('Used At'),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Expires At'),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('redemption_code')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('redemption_code')
                    ->label('Code')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Code copied!')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('p_coins_spent')
                    ->label('Cost')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn (int $state): string => $state . ' P')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'primary' => 'used',
                        'danger' => 'expired',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('redeemed_at')
                    ->label('Redeemed')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_at')
                    ->label('Used')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->expires_at && $record->expires_at->isPast() ? 'danger' : null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'used' => 'Used',
                        'expired' => 'Expired',
                    ]),
                Tables\Filters\Filter::make('redeemed_at')
                    ->form([
                        Forms\Components\DatePicker::make('redeemed_from')
                            ->label('Redeemed From'),
                        Forms\Components\DatePicker::make('redeemed_until')
                            ->label('Redeemed Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['redeemed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('redeemed_at', '>=', $date),
                            )
                            ->when(
                                $data['redeemed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('redeemed_at', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_used')
                    ->label('Mark as Used')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'completed')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'used',
                            'used_at' => now(),
                        ]);
                    })
                    ->successNotification(
                        fn () => \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Marked as Used')
                            ->body('The redemption has been marked as used.')
                    ),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_as_used')
                        ->label('Mark as Used')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'status' => 'used',
                                    'used_at' => now(),
                                ]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('mark_as_expired')
                        ->label('Mark as Expired')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'expired']))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('redeemed_at', 'desc');
    }
}

