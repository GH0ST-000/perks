<?php

namespace App\Filament\Resources\PartnerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'categories';


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('pivot.discount_percentage')
                    ->label('Discount')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . '%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.points_per_visit')
                    ->label('Points/Visit')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state . ' pts')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.created_at')
                    ->label('Assigned At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('discount_percentage')
                            ->label('Discount Percentage')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.01)
                            ->required()
                            ->suffix('%'),
                        Forms\Components\TextInput::make('points_per_visit')
                            ->label('Points Per Visit')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->suffix('points'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('pivot.discount_percentage')
                            ->label('Discount Percentage')
                            ->numeric()
                            ->required()
                            ->suffix('%'),
                        Forms\Components\TextInput::make('pivot.points_per_visit')
                            ->label('Points Per Visit')
                            ->numeric()
                            ->required()
                            ->suffix('points'),
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
