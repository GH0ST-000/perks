<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftResource\Pages;
use App\Filament\Resources\GiftResource\RelationManagers;
use App\Models\Gift;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GiftResource extends Resource
{
    protected static ?string $model = Gift::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'საჩუქრები';

    protected static ?string $modelLabel = 'Gift';

    protected static ?string $pluralModelLabel = 'Gifts';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Gift Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Gift Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Gift Image')
                            ->image()
                            ->disk('public')
                            ->directory('gifts')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('Upload an image for the gift (max 5MB)')
                            ->columnSpan(1),
                        Forms\Components\Select::make('type')
                            ->label('Gift Type')
                            ->options([
                                'voucher' => 'Voucher',
                                'product' => 'Product',
                                'service' => 'Service',
                            ])
                            ->default('voucher')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Pricing & Stock')
                    ->schema([
                        Forms\Components\TextInput::make('p_coins_cost')
                            ->label('P Coins Cost')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->suffix('P')
                            ->helperText('Cost in P coins to redeem this gift')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('stock')
                            ->label('Stock Quantity')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->helperText('Available quantity')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active gifts will be visible to users')
                            ->default(true)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Metadata')
                            ->helperText('Add additional information like terms, usage instructions, etc.')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->size(60)
                    ->defaultImageUrl(url('/images/gift-placeholder.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'voucher' => 'Voucher',
                        'product' => 'Product',
                        'service' => 'Service',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'voucher',
                        'warning' => 'product',
                        'success' => 'service',
                    ]),
                Tables\Columns\TextColumn::make('p_coins_cost')
                    ->label('Cost')
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn (int $state): string => $state . ' P'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (int $state): string => $state . ' left'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('redemptions_count')
                    ->label('Redeemed')
                    ->counts('redemptions')
                    ->badge()
                    ->color('info')
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('type')
                    ->label('Gift Type')
                    ->options([
                        'voucher' => 'Voucher',
                        'product' => 'Product',
                        'service' => 'Service',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All gifts')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                Tables\Filters\Filter::make('stock')
                    ->form([
                        Forms\Components\Select::make('stock_status')
                            ->label('Stock Status')
                            ->options([
                                'out_of_stock' => 'Out of Stock',
                                'low_stock' => 'Low Stock (≤ 5)',
                                'in_stock' => 'In Stock (> 5)',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['stock_status'] === 'out_of_stock',
                            fn ($query) => $query->where('stock', '<=', 0)
                        )->when(
                            $data['stock_status'] === 'low_stock',
                            fn ($query) => $query->where('stock', '>', 0)->where('stock', '<=', 5)
                        )->when(
                            $data['stock_status'] === 'in_stock',
                            fn ($query) => $query->where('stock', '>', 5)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('add_stock')
                    ->label('Add Stock')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity to Add')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(10),
                    ])
                    ->action(function (Gift $record, array $data): void {
                        $record->increment('stock', $data['quantity']);
                    })
                    ->successNotification(
                        fn () => \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Stock Updated')
                            ->body('Stock quantity has been increased.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Gift Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->disk('public')
                            ->size(200)
                            ->defaultImageUrl(url('/images/gift-placeholder.png')),
                        Infolists\Components\TextEntry::make('name')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('type')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'voucher' => 'Voucher',
                                'product' => 'Product',
                                'service' => 'Service',
                                default => $state,
                            })
                            ->colors([
                                'primary' => 'voucher',
                                'warning' => 'product',
                                'success' => 'service',
                            ]),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('p_coins_cost')
                            ->label('P Coins Cost')
                            ->badge()
                            ->color('warning')
                            ->formatStateUsing(fn (int $state): string => $state . ' P'),
                        Infolists\Components\TextEntry::make('stock')
                            ->label('Stock Quantity')
                            ->badge()
                            ->color(fn (int $state): string => match (true) {
                                $state <= 0 => 'danger',
                                $state <= 5 => 'warning',
                                default => 'success',
                            })
                            ->formatStateUsing(fn (int $state): string => $state . ' available'),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Active Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        Infolists\Components\TextEntry::make('sort_order')
                            ->label('Sort Order'),
                        Infolists\Components\TextEntry::make('redemptions_count')
                            ->label('Total Redemptions')
                            ->state(fn (Gift $record): int => $record->redemptions()->count())
                            ->badge()
                            ->color('info'),
                        Infolists\Components\KeyValueEntry::make('metadata')
                            ->label('Additional Information')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Created At'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime()
                            ->label('Updated At'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RedemptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGifts::route('/'),
            'create' => Pages\CreateGift::route('/create'),
            'view' => Pages\ViewGift::route('/{record}'),
            'edit' => Pages\EditGift::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}

