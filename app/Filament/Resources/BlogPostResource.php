<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'ბლოგი';

    protected static ?string $modelLabel = 'სტატია';

    protected static ?string $pluralModelLabel = 'სტატიები';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ძირითადი ინფორმაცია')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('სათაური')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL-ში გამოჩენილი სათაური'),

                        Forms\Components\Textarea::make('excerpt')
                            ->label('მოკლე აღწერა')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('მოკლე აღწერა რომელიც გამოჩნდება სტატიების სიაში'),

                        Forms\Components\Select::make('category')
                            ->label('კატეგორია')
                            ->options([
                                'სიახლეები' => 'სიახლეები',
                                'გზამკვლევები' => 'გზამკვლევები',
                                'ინფორმაცია' => 'ინფორმაცია',
                                'ბენეფიტები' => 'ბენეფიტები',
                            ])
                            ->searchable()
                            ->preload(),

                        Forms\Components\TagsInput::make('tags')
                            ->label('ტეგები')
                            ->helperText('დააჭირეთ Enter-ს თითოეული ტეგის დასამატებლად'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('კონტენტი')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('სტატიის ტექსტი')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('blog-attachments')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('მედია')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('მთავარი სურათი')
                            ->image()
                            ->disk('public')
                            ->directory('blog-images')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                            ])
                            ->maxSize(5120)
                            ->helperText('რეკომენდებული ზომა: 1200x675 (16:9)'),
                    ]),

                Forms\Components\Section::make('დამატებითი ინფორმაცია')
                    ->schema([
                        Forms\Components\TextInput::make('author')
                            ->label('ავტორი')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('read_time')
                            ->label('კითხვის დრო (წუთი)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(120)
                            ->helperText('სტატიის წაკითხვისთვის საჭირო დრო წუთებში'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('პუბლიკაცია')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('გამოქვეყნებული')
                            ->default(false)
                            ->live(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('გამოქვეყნების თარიღი')
                            ->default(now())
                            ->required(fn (Forms\Get $get): bool => $get('is_published'))
                            ->visible(fn (Forms\Get $get): bool => $get('is_published')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta სათაური')
                            ->maxLength(60)
                            ->helperText('SEO-სთვის. თუ ცარიელია, გამოიყენება სათაური'),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta აღწერა')
                            ->rows(2)
                            ->maxLength(160)
                            ->helperText('SEO-სთვის. თუ ცარიელია, გამოიყენება მოკლე აღწერა'),
                    ])
                    ->collapsed()
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('სურათი')
                    ->size(60)
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('title')
                    ->label('სათაური')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category')
                    ->label('კატეგორია')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author')
                    ->label('ავტორი')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('გამოქვეყნებული')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('გამოქვეყნების თარიღი')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('read_time')
                    ->label('კითხვის დრო')
                    ->suffix(' წთ')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('შექმნის თარიღი')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('კატეგორია')
                    ->options([
                        'სიახლეები' => 'სიახლეები',
                        'გზამკვლევები' => 'გზამკვლევები',
                        'ინფორმაცია' => 'ინფორმაცია',
                        'ბენეფიტები' => 'ბენეფიტები',
                    ]),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('გამოქვეყნებული')
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
