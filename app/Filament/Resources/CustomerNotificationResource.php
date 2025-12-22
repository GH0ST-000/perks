<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerNotificationResource\Pages;
use App\Filament\Resources\CustomerNotificationResource\RelationManagers;
use App\Models\CustomerNotification;
use App\Services\CustomerService;
use App\Services\SmsService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CustomerNotificationResource extends Resource
{
    protected static ?string $model = CustomerNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'კლიენტები';

    protected static ?string $modelLabel = 'Customer Notification';

    protected static ?string $pluralModelLabel = 'Customer Notifications';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $isManager = $user->role === 'manager';
        $isAdmin = $user->role === 'admin';

        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_phone')
                    ->label('Customer Phone Number')
                    ->tel()
                    ->required()
                    ->maxLength(15)
                    ->placeholder('Enter phone number')
                    ->helperText('Customer will receive SMS verification code'),

                Forms\Components\TextInput::make('discount')
                    ->label('Discount Percentage')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(10)
                    ->helperText('Discount percentage to offer customer'),

                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required($isAdmin)
                    ->visible($isAdmin)
                    ->searchable()
                    ->preload()
                    ->label('Company'),

                Forms\Components\TextInput::make('company_display')
                    ->label('Company')
                    ->default($isManager && $user->company ? $user->company->name : null)
                    ->disabled()
                    ->visible($isManager)
                    ->dehydrated(false),

                Forms\Components\Hidden::make('manager_id')
                    ->default($user->id),

                Forms\Components\Hidden::make('verification_code')
                    ->default(fn () => app(SmsService::class)->generateVerificationCode()),

                Forms\Components\Hidden::make('status')
                    ->default('pending'),

                Forms\Components\Hidden::make('expires_at')
                    ->default(now()->addMinutes(10)),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->sortable()
                    ->searchable()
                    ->visible($isAdmin),

                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Manager')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('discount')
                    ->label('Discount')
                    ->suffix('%')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Used')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'verified' => 'success',
                        'used' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('verification_code')
                    ->label('Code')
                    ->copyable()
                    ->visible($isAdmin)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->description(fn ($record) => $record->created_at->format('M d, Y H:i')),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->isExpired() ? 'danger' : 'gray')
                    ->description(fn ($record) => $record->isExpired() ? 'Expired' : 'Active'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'used' => 'Used',
                    ]),

                Tables\Filters\SelectFilter::make('company_id')
                    ->relationship('company', 'name')
                    ->label('Company')
                    ->visible($isAdmin),

                Tables\Filters\Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '<', now()))
                    ->label('Expired Only'),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending' && !$record->isExpired())
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('code')
                            ->label('Verification Code')
                            ->required()
                            ->maxLength(6),
                    ])
                    ->action(function (CustomerNotification $record, array $data): void {
                        if ($record->verification_code === $data['code']) {
                            $record->update([
                                'status' => 'verified',
                                'verified_at' => now(),
                            ]);
                            Notification::make()
                                ->success()
                                ->title('Verified')
                                ->body('Customer code verified successfully!')
                                ->send();
                        } else {
                            Notification::make()
                                ->danger()
                                ->title('Invalid Code')
                                ->body('The verification code is incorrect.')
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('mark_used')
                    ->label('Mark Used')
                    ->icon('heroicon-o-check-badge')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'verified')
                    ->requiresConfirmation()
                    ->action(function (CustomerNotification $record): void {
                        $record->update([
                            'status' => 'used',
                            'usage_count' => $record->usage_count + 1,
                        ]);
                        Notification::make()
                            ->success()
                            ->title('Marked as Used')
                            ->body('Discount marked as used successfully!')
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerNotifications::route('/'),
            'create' => Pages\CreateCustomerNotification::route('/create'),
            'edit' => Pages\EditCustomerNotification::route('/{record}/edit'),
        ];
    }

    /**
     * Control who can see this resource in navigation
     */
    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Filter records based on user role
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Managers can only see their company's notifications
        if ($user->role === 'manager') {
            return $query->where('company_id', $user->company_id)
                        ->where('manager_id', $user->id);
        }

        // Admins see everything
        return $query;
    }
}
