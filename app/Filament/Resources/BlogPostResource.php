<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'ბლოგი';

    protected static ?string $modelLabel = 'ბლოგ პოსტი';

    protected static ?string $pluralModelLabel = 'ბლოგ პოსტები';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('სათაური')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                    ->columnSpan(2),
                Forms\Components\TextInput::make('slug')
                    ->label('სლაგი')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(BlogPost::class, 'slug', ignoreRecord: true),
                Forms\Components\Select::make('category')
                    ->label('კატეგორია')
                    ->options([
                        'ახალი ამბები' => 'ახალი ამბები',
                        'გაიდები' => 'გაიდები',
                        'ბენეფიტები' => 'ბენეფიტები',
                        'კომპანია' => 'კომპანია',
                        'პარტნიორები' => 'პარტნიორები',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label('სურათი')
                    ->image()
                    ->directory('blog-images')
                    ->columnSpan(2),
                Forms\Components\Textarea::make('excerpt')
                    ->label('მოკლე აღწერა')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->label('შინაარსი')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('author')
                    ->label('ავტორი')
                    ->required()
                    ->maxLength(255)
                    ->default('Perks Team'),
                Forms\Components\Toggle::make('is_published')
                    ->label('გამოქვეყნებული')
                    ->default(false)
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('გამოქვეყნების თარიღი')
                    ->default(now()),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('სურათი')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('სათაური')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category')
                    ->label('კატეგორია')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('author')
                    ->label('ავტორი')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('გამოქვეყნებული')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('გამოქვეყნების თარიღი')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('შექმნის თარიღი')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('კატეგორია')
                    ->options([
                        'ახალი ამბები' => 'ახალი ამბები',
                        'გაიდები' => 'გაიდები',
                        'ბენეფიტები' => 'ბენეფიტები',
                        'კომპანია' => 'კომპანია',
                        'პარტნიორები' => 'პარტნიორები',
                    ]),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('გამოქვეყნებული')
                    ->boolean()
                    ->trueLabel('კი')
                    ->falseLabel('არა')
                    ->placeholder('ყველა'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
