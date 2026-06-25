<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerMarketingSubscriptionResource\Pages;
use App\Models\PartnerMarketingSubscription;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartnerMarketingSubscriptionResource extends Resource
{
    protected static ?string $model = PartnerMarketingSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'მართვა';

    protected static ?string $navigationLabel = 'მარკეტინგის გამოწერები';

    protected static ?string $modelLabel = 'მარკეტინგის გამოწერა';

    protected static ?string $pluralModelLabel = 'მარკეტინგის გამოწერები';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('პარტნიორი')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('package_title')
                    ->label('პაკეტი')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('თანხა')
                    ->money('GEL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('სტატუსი')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        PartnerMarketingSubscription::STATUS_ACTIVE => 'success',
                        PartnerMarketingSubscription::STATUS_PENDING => 'warning',
                        PartnerMarketingSubscription::STATUS_PAST_DUE => 'danger',
                        PartnerMarketingSubscription::STATUS_CANCELLED => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        PartnerMarketingSubscription::STATUS_ACTIVE => 'აქტიური',
                        PartnerMarketingSubscription::STATUS_PENDING => 'მოლოდინში',
                        PartnerMarketingSubscription::STATUS_PAST_DUE => 'ვადაგადაცილებული',
                        PartnerMarketingSubscription::STATUS_CANCELLED => 'გაუქმებული',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('დაწყება')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_billed_at')
                    ->label('ბოლო ჩამოჭრა')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('next_billing_date')
                    ->label('შემდეგი ჩამოჭრა')
                    ->dateTime('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('შექმნა')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('სტატუსი')
                    ->options([
                        PartnerMarketingSubscription::STATUS_ACTIVE => 'აქტიური',
                        PartnerMarketingSubscription::STATUS_PENDING => 'მოლოდინში',
                        PartnerMarketingSubscription::STATUS_PAST_DUE => 'ვადაგადაცილებული',
                        PartnerMarketingSubscription::STATUS_CANCELLED => 'გაუქმებული',
                    ]),
                Tables\Filters\SelectFilter::make('package_id')
                    ->label('პაკეტი')
                    ->options([
                        'social' => 'სოციალური გაძლიერება',
                        'platinum' => 'პლატინის პარტნიორი',
                        'executive' => 'აღმასრულებელი დონე',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('გამოწერა')
                    ->schema([
                        Infolists\Components\TextEntry::make('partner.name')->label('პარტნიორი'),
                        Infolists\Components\TextEntry::make('user.name')->label('ანგარიში'),
                        Infolists\Components\TextEntry::make('package_title')->label('პაკეტი'),
                        Infolists\Components\TextEntry::make('amount')->label('თანხა')->money('GEL'),
                        Infolists\Components\TextEntry::make('status')->label('სტატუსი'),
                        Infolists\Components\TextEntry::make('started_at')->label('დაწყების თარიღი')->dateTime('d.m.Y H:i'),
                        Infolists\Components\TextEntry::make('last_billed_at')->label('ბოლო ჩამოჭრა')->dateTime('d.m.Y H:i'),
                        Infolists\Components\TextEntry::make('next_billing_date')->label('შემდეგი ჩამოჭრა')->dateTime('d.m.Y'),
                        Infolists\Components\TextEntry::make('current_period_start')->label('პერიოდის დასაწყისი')->dateTime('d.m.Y'),
                        Infolists\Components\TextEntry::make('current_period_end')->label('პერიოდის დასასრული')->dateTime('d.m.Y'),
                        Infolists\Components\TextEntry::make('cancelled_at')->label('გაუქმება')->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('გადახდების ისტორია')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('payments')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('type')->label('ტიპი'),
                                Infolists\Components\TextEntry::make('amount')->label('თანხა')->money('GEL'),
                                Infolists\Components\TextEntry::make('status')->label('სტატუსი'),
                                Infolists\Components\TextEntry::make('paid_at')->label('გადახდის დრო')->dateTime('d.m.Y H:i'),
                                Infolists\Components\TextEntry::make('external_order_id')->label('შეკვეთის ID')->copyable(),
                            ])
                            ->columns(5),
                    ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['partner', 'user', 'payments']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerMarketingSubscriptions::route('/'),
            'view' => Pages\ViewPartnerMarketingSubscription::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
