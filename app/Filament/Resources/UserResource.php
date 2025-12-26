<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'მომხმარებლები';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Select::make('company_id')
                            ->label('Company')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                            ])
                            ->columnSpan(1),
                        Forms\Components\Select::make('role')
                            ->options([
                                'user' => 'User',
                                'admin' => 'Admin',
                            ])
                            ->default('user')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('p_coins')
                            ->label('P-Coins')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->suffix('coins')
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Authentication')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Leave blank to keep current password when editing.'),
                    ]),
                Forms\Components\Section::make('Profile Photo')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_photo')
                            ->image()
                            ->disk('public')
                            ->directory('profile-photos')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->disk('public')
                    ->size(50),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('p_coins')
                    ->label('P-Coins')
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => number_format($state) . ' coins'),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'user' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                    ]),
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('email_verified_at')
                    ->label('Email Verified')
                    ->query(fn ($query) => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('p_coins')
                    ->form([
                        Forms\Components\TextInput::make('p_coins_from')
                            ->label('P-Coins From')
                            ->numeric()
                            ->placeholder('Min'),
                        Forms\Components\TextInput::make('p_coins_to')
                            ->label('P-Coins To')
                            ->numeric()
                            ->placeholder('Max'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['p_coins_from'],
                                fn ($query, $value) => $query->where('p_coins', '>=', $value)
                            )
                            ->when(
                                $data['p_coins_to'],
                                fn ($query, $value) => $query->where('p_coins', '<=', $value)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('add_pcoins')
                        ->label('Add P-Coins')
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('amount')
                                ->label('Amount')
                                ->numeric()
                                ->required()
                                ->minValue(1),
                            Forms\Components\Textarea::make('reason')
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
                    Tables\Actions\Action::make('deduct_pcoins')
                        ->label('Deduct P-Coins')
                        ->icon('heroicon-o-minus-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\TextInput::make('amount')
                                ->label('Amount')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->maxValue(fn ($record) => $record->p_coins),
                            Forms\Components\Textarea::make('reason')
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
                    Tables\Actions\Action::make('send_offer')
                        ->label('Send Offer')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('info')
                        ->form([
                            Forms\Components\Select::make('offer_id')
                                ->label('Premium Offer')
                                ->options(\App\Models\PremiumOffer::pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Textarea::make('message')
                                ->label('Personal Message')
                                ->rows(3)
                                ->placeholder('Optional personalized message'),
                        ])
                        ->action(function ($record, array $data) {
                            // Here you would implement the actual offer sending logic
                            // This could send an email, notification, etc.
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Offer Sent')
                                ->body("Offer sent to {$record->name}")
                                ->send();
                        }),
                ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('User Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('profile_photo')
                            ->circular()
                            ->defaultImageUrl(url('/images/default-avatar.png'))
                            ->disk('public')
                            ->size(150),
                        Infolists\Components\TextEntry::make('name')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('email')
                            ->icon('heroicon-m-envelope')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone')
                            ->icon('heroicon-m-phone')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('company.name')
                            ->label('Company')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('p_coins')
                            ->label('P-Coins')
                            ->badge()
                            ->color('warning')
                            ->formatStateUsing(fn ($state) => number_format($state) . ' coins'),
                        Infolists\Components\TextEntry::make('role')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'admin' => 'danger',
                                'user' => 'success',
                                default => 'gray',
                            }),
                        Infolists\Components\IconEntry::make('email_verified_at')
                            ->label('Email Verified')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        Infolists\Components\TextEntry::make('email_verified_at')
                            ->dateTime()
                            ->label('Email Verified At'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Created At'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime()
                            ->label('Updated At'),
                        Infolists\Components\TextEntry::make('visits_count')
                            ->label('Total Visits')
                            ->getStateUsing(fn ($record) => $record->visits()->count() . ' visits')
                            ->badge()
                            ->color('info'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VisitsRelationManager::class,
            RelationManagers\WalletTransactionsRelationManager::class,
            RelationManagers\PaymentMethodsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
