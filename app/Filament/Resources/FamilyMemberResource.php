<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyMemberResource\Pages;
use App\Models\FamilyMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FamilyMemberResource extends Resource
{
    protected static ?string $model = FamilyMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'ოჯახის წევრები';

    protected static ?string $modelLabel = 'Family Member';

    protected static ?string $pluralModelLabel = 'Family Members';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Family Member Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('personal_number')
                            ->label('Personal Number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->length(11)
                            ->numeric()
                            ->placeholder('11 digits')
                            ->columnSpan(1),
                        Forms\Components\Select::make('relationship')
                            ->label('Relationship')
                            ->options([
                                'spouse' => 'Spouse (მეუღლე)',
                                'child' => 'Child (შვილი)',
                                'parent' => 'Parent (მშობელი)',
                                'sibling' => 'Sibling (ძმა/და)',
                                'other' => 'Other (სხვა)',
                            ])
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('personal_number')
                    ->label('Personal Number')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Personal number copied')
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('relationship')
                    ->label('Relationship')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'spouse' => 'success',
                        'child' => 'info',
                        'parent' => 'warning',
                        'sibling' => 'primary',
                        'other' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'spouse' => 'Spouse',
                        'child' => 'Child',
                        'parent' => 'Parent',
                        'sibling' => 'Sibling',
                        'other' => 'Other',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('relationship')
                    ->options([
                        'spouse' => 'Spouse',
                        'child' => 'Child',
                        'parent' => 'Parent',
                        'sibling' => 'Sibling',
                        'other' => 'Other',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All members')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFamilyMembers::route('/'),
            'create' => Pages\CreateFamilyMember::route('/create'),
            'edit' => Pages\EditFamilyMember::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}

