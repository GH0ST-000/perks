<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'სლაიდერები';

    protected static ?string $modelLabel = 'სლაიდერი';

    protected static ?string $pluralModelLabel = 'სლაიდერები';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('სლაიდერის ინფორმაცია')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('სათაური')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('background_image')
                            ->label('ფონური სურათი')
                            ->image()
                            ->disk('public')
                            ->directory('sliders/backgrounds')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('სლაიდერის ფონური სურათი (blurred background)')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('tag_text')
                            ->label('ტეგის ტექსტი')
                            ->maxLength(255)
                            ->placeholder('მაგ: FOR COMPANIES')
                            ->helperText('მცირე ტეგი სლაიდერის ზედა ნაწილში')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('headline_before')
                            ->label('სათაური - დასაწყისი')
                            ->maxLength(255)
                            ->placeholder('მაგ: Empower Your Team with')
                            ->helperText('სათაურის ნაწილი გრადიენტის წინ')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('headline_highlight')
                            ->label('სათაური - გამოკვეთილი ნაწილი')
                            ->maxLength(255)
                            ->placeholder('მაგ: Premium')
                            ->helperText('სათაურის გრადიენტული ნაწილი')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('headline_after')
                            ->label('სათაური - დასასრული')
                            ->maxLength(255)
                            ->placeholder('მაგ: Benefits')
                            ->helperText('სათაურის ნაწილი გრადიენტის შემდეგ')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('sub_headline')
                            ->label('ქვე-სათაური')
                            ->rows(3)
                            ->placeholder('მაგ: Attract and retain top talent with comprehensive employee benefits.')
                            ->helperText('სლაიდერის აღწერა/ქვე-სათაური')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('ღილაკები')
                    ->schema([
                        Forms\Components\TextInput::make('button1_text')
                            ->label('ღილაკი 1 - ტექსტი')
                            ->maxLength(255)
                            ->placeholder('მაგ: View Pricing'),
                        Forms\Components\TextInput::make('button1_link')
                            ->label('ღილაკი 1 - ლინკი')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com/pricing'),
                        Forms\Components\TextInput::make('button2_text')
                            ->label('ღილაკი 2 - ტექსტი')
                            ->maxLength(255)
                            ->placeholder('მაგ: Learn More'),
                        Forms\Components\TextInput::make('button2_link')
                            ->label('ღილაკი 2 - ლინკი')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com/learn-more'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('დამატებითი ინფორმაცია')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('აღწერა')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('order')
                            ->label('რიგი')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required()
                            ->helperText('სლაიდერების გამოჩენის რიგი (0 = პირველი)'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('აქტიური')
                            ->default(true)
                            ->helperText('სლაიდერის გამოჩენა/დამალვა'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('background_image')
                    ->label('სურათი')
                    ->disk('public')
                    ->size(80)
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('სათაური')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),
                Tables\Columns\TextColumn::make('tag_text')
                    ->label('ტეგი')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('headline_highlight')
                    ->label('გამოკვეთილი ნაწილი')
                    ->searchable()
                    ->limit(20)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('button1_text')
                    ->label('ღილაკი 1')
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('button2_text')
                    ->label('ღილაკი 2')
                    ->badge()
                    ->color('gray')
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
                Infolists\Components\Section::make('სლაიდერის ინფორმაცია')
                    ->schema([
                        Infolists\Components\ImageEntry::make('background_image')
                            ->label('ფონური სურათი')
                            ->disk('public')
                            ->size(200),
                        Infolists\Components\TextEntry::make('title')
                            ->label('სათაური')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('tag_text')
                            ->label('ტეგის ტექსტი')
                            ->badge()
                            ->color('gray'),
                        Infolists\Components\TextEntry::make('headline_before')
                            ->label('სათაური - დასაწყისი'),
                        Infolists\Components\TextEntry::make('headline_highlight')
                            ->label('სათაური - გამოკვეთილი')
                            ->badge()
                            ->color('warning'),
                        Infolists\Components\TextEntry::make('headline_after')
                            ->label('სათაური - დასასრული'),
                        Infolists\Components\TextEntry::make('sub_headline')
                            ->label('ქვე-სათაური')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('description')
                            ->label('აღწერა')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('ღილაკები')
                    ->schema([
                        Infolists\Components\TextEntry::make('button1_text')
                            ->label('ღილაკი 1 - ტექსტი'),
                        Infolists\Components\TextEntry::make('button1_link')
                            ->label('ღილაკი 1 - ლინკი')
                            ->url(fn ($record) => $record->button1_link)
                            ->openUrlInNewTab()
                            ->placeholder('არ არის მითითებული'),
                        Infolists\Components\TextEntry::make('button2_text')
                            ->label('ღილაკი 2 - ტექსტი'),
                        Infolists\Components\TextEntry::make('button2_link')
                            ->label('ღილაკი 2 - ლინკი')
                            ->url(fn ($record) => $record->button2_link)
                            ->openUrlInNewTab()
                            ->placeholder('არ არის მითითებული'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('სტატუსი')
                    ->schema([
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('აქტიური')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        Infolists\Components\TextEntry::make('order')
                            ->label('რიგი')
                            ->badge()
                            ->color('gray'),
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
            // RelationManagers\ImagesRelationManager::class, // Keep if you still want to use images relation
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'view' => Pages\ViewSlider::route('/{record}'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
