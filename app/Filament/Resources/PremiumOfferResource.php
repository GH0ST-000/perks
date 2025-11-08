<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PremiumOfferResource\Pages;
use App\Filament\Resources\PremiumOfferResource\RelationManagers;
use App\Models\PremiumOffer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PremiumOfferResource extends Resource
{
    protected static ?string $model = PremiumOffer::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Premium Offers';

    protected static ?string $modelLabel = 'Premium Offer';

    protected static ?string $pluralModelLabel = 'Premium Offers';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Offer Information')
                    ->schema([
                        Forms\Components\Select::make('partner_id')
                            ->label('Partner')
                            ->relationship('partner', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('header_text')
                            ->label('Header Text')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('premium-offers')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('day_left')
                            ->label('Days Left')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->suffix('days')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('discount')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->required()
                            ->suffix('%')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Premium Upgrade')
                    ->schema([
                        Forms\Components\Toggle::make('is_premium')
                            ->label('Premium Offer')
                            ->helperText('Enable this to mark the offer as premium')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('package_purchased')
                            ->label('Package Purchased')
                            ->helperText('Mark if the partner has purchased the marketing package')
                            ->columnSpan(1)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $set('purchased_at', now());
                                    $set('purchased_by', auth()->id());
                                    $set('is_premium', true);
                                }
                            }),
                        Forms\Components\DateTimePicker::make('purchased_at')
                            ->label('Purchased At')
                            ->disabled(fn ($get) => !$get('package_purchased'))
                            ->columnSpan(1),
                        Forms\Components\Select::make('purchased_by')
                            ->label('Purchased By')
                            ->relationship('purchasedBy', 'name')
                            ->disabled(fn ($get) => !$get('package_purchased'))
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->size(50),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Partner')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\IconColumn::make('is_premium')
                    ->label('Premium')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->sortable(),
                Tables\Columns\IconColumn::make('package_purchased')
                    ->label('Package')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('header_text')
                    ->label('Header Text')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('day_left')
                    ->label('Days Left')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 3 => 'danger',
                        $state <= 7 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn (int $state): string => $state . ' days'),
                Tables\Columns\TextColumn::make('discount')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2) . '%'),
                Tables\Columns\TextColumn::make('purchased_at')
                    ->label('Purchased At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('purchasedBy.name')
                    ->label('Purchased By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
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
                Tables\Filters\SelectFilter::make('partner_id')
                    ->label('Partner')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_premium')
                    ->label('Premium Offer')
                    ->placeholder('All offers')
                    ->trueLabel('Premium only')
                    ->falseLabel('Standard only'),
                Tables\Filters\TernaryFilter::make('package_purchased')
                    ->label('Package Purchased')
                    ->placeholder('All')
                    ->trueLabel('Purchased')
                    ->falseLabel('Not purchased'),
                Tables\Filters\Filter::make('day_left')
                    ->form([
                        Forms\Components\Select::make('day_left_filter')
                            ->label('Days Left')
                            ->options([
                                'urgent' => 'Urgent (0-3 days)',
                                'soon' => 'Soon (4-7 days)',
                                'available' => 'Available (8+ days)',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['day_left_filter'] === 'urgent',
                            fn ($query) => $query->where('day_left', '<=', 3)
                        )->when(
                            $data['day_left_filter'] === 'soon',
                            fn ($query) => $query->whereBetween('day_left', [4, 7])
                        )->when(
                            $data['day_left_filter'] === 'available',
                            fn ($query) => $query->where('day_left', '>=', 8)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
                Infolists\Components\Section::make('Offer Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->disk('public')
                            ->size(200),
                        Infolists\Components\TextEntry::make('partner.name')
                            ->label('Partner')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('name')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('header_text')
                            ->label('Header Text'),
                        Infolists\Components\IconEntry::make('is_premium')
                            ->label('Premium Offer')
                            ->boolean()
                            ->trueIcon('heroicon-o-star')
                            ->falseIcon('heroicon-o-star')
                            ->trueColor('warning')
                            ->falseColor('gray'),
                        Infolists\Components\IconEntry::make('package_purchased')
                            ->label('Package Purchased')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('gray'),
                        Infolists\Components\TextEntry::make('day_left')
                            ->label('Days Left')
                            ->badge()
                            ->color(fn (int $state): string => match (true) {
                                $state <= 3 => 'danger',
                                $state <= 7 => 'warning',
                                default => 'success',
                            })
                            ->formatStateUsing(fn (int $state): string => $state . ' days'),
                        Infolists\Components\TextEntry::make('discount')
                            ->badge()
                            ->color('success')
                            ->formatStateUsing(fn (float $state): string => number_format($state, 2) . '%'),
                        Infolists\Components\TextEntry::make('purchased_at')
                            ->label('Purchased At')
                            ->dateTime()
                            ->placeholder('Not purchased'),
                        Infolists\Components\TextEntry::make('purchasedBy.name')
                            ->label('Purchased By')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('description')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPremiumOffers::route('/'),
            'create' => Pages\CreatePremiumOffer::route('/create'),
            'view' => Pages\ViewPremiumOffer::route('/{record}'),
            'edit' => Pages\EditPremiumOffer::route('/{record}/edit'),
        ];
    }
}
