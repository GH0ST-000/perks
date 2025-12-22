<?php

namespace App\Filament\Resources\VacancyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';

    protected static ?string $title = 'განაცხადები';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('სახელი, გვარი')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->label('ელ-ფოსტა')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope')
                    ->color('info'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('ტელეფონი')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('cover_letter')
                    ->label('დამატებითი ინფორმაცია')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->cover_letter)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cv_path')
                    ->label('CV')
                    ->formatStateUsing(fn ($state) => 'CV ფაილი')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(fn ($record) => $record->cv_path ? Storage::disk('public')->url($record->cv_path) : null)
                    ->openUrlInNewTab()
                    ->copyable()
                    ->tooltip('დააკლიკეთ CV-ს სანახავად'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('გაგზავნის თარიღი')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('დან'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('მდე'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn ($query, $date) => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn ($query, $date) => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                // No create action - applications are submitted from frontend
            ])
            ->actions([
                Tables\Actions\Action::make('view_cv')
                    ->label('CV-ს ნახვა')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(fn ($record) => $record->cv_path ? Storage::disk('public')->url($record->cv_path) : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->cv_path)),
                Tables\Actions\Action::make('download_cv')
                    ->label('CV-ს ჩამოტვირთვა')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn ($record) => $record->cv_path ? Storage::disk('public')->url($record->cv_path) : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->cv_path)),
                Tables\Actions\ViewAction::make()
                    ->label('დეტალები')
                    ->modalHeading(fn ($record) => 'განაცხადი - ' . $record->name)
                    ->modalContent(fn ($record) => view('filament.resources.vacancy-resource.relation-managers.application-details', [
                        'application' => $record,
                    ])),
                Tables\Actions\DeleteAction::make()
                    ->label('წაშლა'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('განაცხადები არ არის')
            ->emptyStateDescription('ამ ვაკანსიაზე ჯერ არავინ გაგზავნია განაცხადი.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}

