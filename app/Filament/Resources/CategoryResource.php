<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'კატეგორიები';

    protected static ?string $modelLabel = 'კატეგორია';

    protected static ?string $pluralModelLabel = 'კატეგორიები';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('კატეგორიის ინფორმაცია')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('სახელი')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            })
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(2),
                        Forms\Components\ViewField::make('image')
                            ->label('აიკონის არჩევა')
                            ->view('filament.forms.components.category-icon-picker')
                            ->helperText('დააკლიკეთ სასურველ აიკონს ბიბლიოთეკიდან.')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('custom_icon')
                            ->label('ან ატვირთეთ საკუთარი აიკონი')
                            ->helperText('თუ საჭიროა სხვა სურათი, ატვირთეთ აქ (გადააჭარბებს ბიბლიოთეკის არჩევანს).')
                            ->image()
                            ->disk('public')
                            ->directory('categories/custom')
                            ->visibility('public')
                            ->imageEditor()
                            ->imagePreviewHeight('100')
                            ->maxSize(5120)
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('small_text')
                            ->label('მოკლე ტექსტი')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('star')
                            ->label('ვარსკვლავი')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(5)
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('აღწერა')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('აიკონი')
                    ->disk('public')
                    ->square()
                    ->size(48),
                Tables\Columns\TextColumn::make('name')
                    ->label('სახელი')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('small_text')
                    ->label('Small Text')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('star')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->html()
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
                Tables\Filters\Filter::make('star')
                    ->form([
                        Forms\Components\Select::make('star')
                            ->options([
                                0 => '0 Stars',
                                1 => '1 Star',
                                2 => '2 Stars',
                                3 => '3 Stars',
                                4 => '4 Stars',
                                5 => '5 Stars',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['star'],
                            fn ($query, $star) => $query->where('star', $star)
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
            ->defaultSort('name');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('კატეგორიის ინფორმაცია')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->label('აიკონი')
                            ->disk('public')
                            ->size(120)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('name')
                            ->label('სახელი')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('slug')
                            ->label('Slug')
                            ->copyable()
                            ->color('gray'),
                        Infolists\Components\TextEntry::make('small_text')
                            ->label('მოკლე ტექსტი'),
                        Infolists\Components\TextEntry::make('star')
                            ->badge()
                            ->color(fn (int $state): string => match (true) {
                                $state >= 4 => 'success',
                                $state >= 3 => 'warning',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('description')
                            ->html()
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
