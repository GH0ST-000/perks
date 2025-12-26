<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodsRelationManager extends RelationManager
{
    protected static string $relationship = 'paymentMethods';

    protected static ?string $title = 'გადახდის მეთოდები';

    protected static ?string $modelLabel = 'გადახდის მეთოდი';

    protected static ?string $pluralModelLabel = 'გადახდის მეთოდები';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->label('ტიპი')
                    ->required()
                    ->default('card')
                    ->maxLength(255),
                Forms\Components\TextInput::make('brand')
                    ->label('ბრენდი')
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_four')
                    ->label('ბოლო 4 ციფრი')
                    ->required()
                    ->maxLength(4),
                Forms\Components\TextInput::make('expiry_month')
                    ->label('ვადა (თვე)')
                    ->maxLength(2),
                Forms\Components\TextInput::make('expiry_year')
                    ->label('ვადა (წელი)')
                    ->maxLength(4),
                Forms\Components\Toggle::make('is_default')
                    ->label('ნაგულისხმევი')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('brand')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('ტიპი')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label('ბრენდი')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'VISA' => 'success',
                        'MASTERCARD' => 'warning',
                        'AMEX' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('last_four')
                    ->label('ბარათის ნომერი')
                    ->formatStateUsing(fn ($state) => '.... .... .... ' . $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('ვადა')
                    ->formatStateUsing(function ($record) {
                        if ($record->expiry_month && $record->expiry_year) {
                            return str_pad($record->expiry_month, 2, '0', STR_PAD_LEFT) . '/' . substr($record->expiry_year, -2);
                        }
                        return '-';
                    }),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('ნაგულისხმევი')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('შექმნის თარიღი')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

