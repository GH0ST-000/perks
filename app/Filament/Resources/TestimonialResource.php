<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'ტესტიმონიალები';

    protected static ?string $modelLabel = 'ტესტიმონიალი';

    protected static ?string $pluralModelLabel = 'ტესტიმონიალები';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ტესტიმონიალის ინფორმაცია')
                    ->schema([
                        Forms\Components\Textarea::make('quote')
                            ->label('ციტატა')
                            ->required()
                            ->rows(5)
                            ->maxLength(1000)
                            ->placeholder('ტესტიმონიალის ტექსტი...')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('author_name')
                            ->label('ავტორის სახელი')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('მაგ: გიორგი ბერიძე'),
                        Forms\Components\TextInput::make('author_position')
                            ->label('პოზიცია')
                            ->maxLength(255)
                            ->placeholder('მაგ: HR დირექტორი')
                            ->helperText('ავტორის პოზიცია/როლი'),
                        Forms\Components\TextInput::make('company_name')
                            ->label('კომპანიის სახელი')
                            ->maxLength(255)
                            ->placeholder('მაგ: TechCorp საქართველო')
                            ->helperText('კომპანიის სახელი (არასავალდებულო)'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('დამატებითი ინფორმაცია')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('რიგი')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->helperText('ტესტიმონიალების გამოჩენის რიგი (0 = პირველი)'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('აქტიური')
                            ->default(true)
                            ->helperText('ტესტიმონიალის გამოჩენა/დამალვა'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quote')
                    ->label('ციტატა')
                    ->searchable()
                    ->limit(50)
                    ->wrap()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('author_name')
                    ->label('ავტორი')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('author_position')
                    ->label('პოზიცია')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label('კომპანია')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('რიგი')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('აქტიური')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('შექმნის თარიღი')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('განახლების თარიღი')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('აქტიური')
                    ->placeholder('ყველა')
                    ->trueLabel('აქტიური')
                    ->falseLabel('არააქტიური'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('აქტივაცია')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('დეაქტივაცია')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('ტესტიმონიალის ინფორმაცია')
                    ->schema([
                        Infolists\Components\TextEntry::make('quote')
                            ->label('ციტატა')
                            ->size('lg')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('author_name')
                            ->label('ავტორის სახელი')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('author_position')
                            ->label('პოზიცია')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('company_name')
                            ->label('კომპანიის სახელი')
                            ->badge()
                            ->color('gray'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('დამატებითი ინფორმაცია')
                    ->schema([
                        Infolists\Components\TextEntry::make('order')
                            ->label('რიგი')
                            ->badge()
                            ->color('gray'),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('აქტიური')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('gray'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('შექმნის თარიღი')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('განახლების თარიღი')
                            ->dateTime('d/m/Y H:i'),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'view' => Pages\ViewTestimonial::route('/{record}'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}

