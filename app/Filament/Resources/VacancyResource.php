<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VacancyResource\Pages;
use App\Filament\Resources\VacancyResource\RelationManagers;
use App\Models\Vacancy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class VacancyResource extends Resource
{
    protected static ?string $model = Vacancy::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'ვაკანსიები';

    protected static ?string $modelLabel = 'ვაკანსია';

    protected static ?string $pluralModelLabel = 'ვაკანსიები';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ძირითადი ინფორმაცია')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label('კომპანია')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('სახელი')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('ელფოსტა')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label('ტელეფონი')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('website')
                                    ->label('ვებ-საიტი')
                                    ->url()
                                    ->maxLength(255),
                            ])
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('title')
                            ->label('სათაური')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->label('სლაგი')
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Vacancy::class, 'slug', ignoreRecord: true)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('აღწერა')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('დეტალები')
                    ->schema([
                        Forms\Components\TextInput::make('city')
                            ->label('ქალაქი')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->label('ლოკაცია')
                            ->maxLength(255),
                        Forms\Components\Select::make('employment_type')
                            ->label('დასაქმების ტიპი')
                            ->options([
                                'full-time' => 'სრული განაკვეთი',
                                'part-time' => 'ნახევარი განაკვეთი',
                                'contract' => 'კონტრაქტი',
                                'freelance' => 'ფრილანსი',
                                'internship' => 'სტაჟირება',
                            ])
                            ->searchable(),
                        Forms\Components\Select::make('experience_level')
                            ->label('გამოცდილების დონე')
                            ->options([
                                'intern' => 'სტაჟიორი',
                                'junior' => 'ჯუნიორი',
                                'mid' => 'საშუალო',
                                'senior' => 'სენიორი',
                                'lead' => 'ლიდერი',
                            ])
                            ->searchable(),
                        Forms\Components\TextInput::make('department')
                            ->label('დეპარტამენტი')
                            ->maxLength(255)
                            ->placeholder('მაგ: Engineering, Sales, Design')
                            ->helperText('დეპარტამენტის სახელი'),
                        Forms\Components\TextInput::make('salary_min')
                            ->label('მინიმალური ხელფასი')
                            ->numeric()
                            ->prefix('₾')
                            ->minValue(0),
                        Forms\Components\TextInput::make('salary_max')
                            ->label('მაქსიმალური ხელფასი')
                            ->numeric()
                            ->prefix('₾')
                            ->minValue(0),
                        Forms\Components\Select::make('salary_currency')
                            ->label('ვალუტა')
                            ->options([
                                'GEL' => '₾ GEL',
                                'USD' => '$ USD',
                                'EUR' => '€ EUR',
                            ])
                            ->default('GEL'),
                        Forms\Components\DatePicker::make('expires_at')
                            ->label('ვადის გასვლის თარიღი')
                            ->native(false)
                            ->minDate(now())
                            ->helperText('ვაკანსია ავტომატურად გახდება არააქტიური ამ თარიღის შემდეგ'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('დამატებითი ინფორმაცია')
                    ->schema([
                        Forms\Components\Textarea::make('requirements')
                            ->label('მოთხოვნები')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('responsibilities')
                            ->label('პასუხისმგებლობა')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('benefits')
                            ->label('პრივილეგიები')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                Forms\Components\Section::make('განაცხადის ინფორმაცია')
                    ->schema([
                        Forms\Components\TextInput::make('application_email')
                            ->label('განაცხადის ელფოსტა')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('application_url')
                            ->label('განაცხადის URL')
                            ->url()
                            ->maxLength(255)
                            ->helperText('ან ელფოსტა ან URL უნდა იყოს მითითებული'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Forms\Components\Section::make('სტატუსი')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('აქტიური')
                            ->default(true)
                            ->required(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('პრემიუმი')
                            ->default(false)
                            ->helperText('პრემიუმ ვაკანსიები ჩანს სიის თავში'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('კომპანია')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('სათაური')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50),
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('განაცხადები')
                    ->counts('applications')
                    ->badge()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state): string => $state > 0 ? $state . ' კანდიდატი' : 'არ არის')
                    ->icon(fn ($state): string => $state > 0 ? 'heroicon-o-users' : 'heroicon-o-user-minus')
                    ->sortable()
                    ->url(fn ($record) => $record->applications()->count() > 0 
                        ? static::getUrl('view', ['record' => $record]) 
                        : null)
                    ->tooltip(fn ($record) => $record->applications()->count() > 0 
                        ? 'დააკლიკეთ განაცხადების სანახავად' 
                        : 'განაცხადები არ არის')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('ქალაქი')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('employment_type')
                    ->label('ტიპი')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'full-time' => 'სრული',
                        'part-time' => 'ნახევარი',
                        'contract' => 'კონტრაქტი',
                        'freelance' => 'ფრილანსი',
                        'internship' => 'სტაჟირება',
                        default => $state,
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('experience_level')
                    ->label('დონე')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('department')
                    ->label('დეპარტამენტი')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('salary_range')
                    ->label('ხელფასი')
                    ->badge()
                    ->color('success')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('აქტიური')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('პრემიუმი')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('days_left')
                    ->label('დარჩენილი დღეები')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'gray',
                        $state <= 0 => 'gray',
                        $state <= 3 => 'danger',
                        $state <= 7 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn ($state): string => $state === null ? 'უსასრულო' : ($state > 0 ? $state . ' დღე' : 'ვადა გაუვიდა'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('ვადის გასვლა')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('company_id')
                    ->label('კომპანია')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('city')
                    ->label('ქალაქი')
                    ->options(fn () => Vacancy::whereNotNull('city')->distinct()->pluck('city', 'city')->toArray())
                    ->searchable(),
                Tables\Filters\SelectFilter::make('employment_type')
                    ->label('დასაქმების ტიპი')
                    ->options([
                        'full-time' => 'სრული განაკვეთი',
                        'part-time' => 'ნახევარი განაკვეთი',
                        'contract' => 'კონტრაქტი',
                        'freelance' => 'ფრილანსი',
                        'internship' => 'სტაჟირება',
                    ]),
                Tables\Filters\SelectFilter::make('experience_level')
                    ->label('გამოცდილების დონე')
                    ->options([
                        'intern' => 'სტაჟიორი',
                        'junior' => 'ჯუნიორი',
                        'mid' => 'საშუალო',
                        'senior' => 'სენიორი',
                        'lead' => 'ლიდერი',
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->label('დეპარტამენტი')
                    ->options(fn () => Vacancy::whereNotNull('department')->distinct()->pluck('department', 'department')->toArray())
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('აქტიური')
                    ->placeholder('ყველა')
                    ->trueLabel('აქტიური')
                    ->falseLabel('არააქტიური'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('პრემიუმი')
                    ->placeholder('ყველა')
                    ->trueLabel('პრემიუმი')
                    ->falseLabel('სტანდარტული'),
                Tables\Filters\Filter::make('has_applications')
                    ->label('განაცხადები')
                    ->form([
                        Forms\Components\Select::make('applications_filter')
                            ->label('სტატუსი')
                            ->options([
                                'with' => 'განაცხადებით',
                                'without' => 'განაცხადების გარეშე',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['applications_filter'] === 'with',
                                fn (Builder $query) => $query->has('applications')
                            )
                            ->when(
                                $data['applications_filter'] === 'without',
                                fn (Builder $query) => $query->doesntHave('applications')
                            );
                    }),
                Tables\Filters\Filter::make('expires_at')
                    ->form([
                        Forms\Components\Select::make('expiration_filter')
                            ->label('ვადის სტატუსი')
                            ->options([
                                'expired' => 'ვადა გაუვიდა',
                                'urgent' => 'სასწრაფო (1-3 დღე)',
                                'soon' => 'მალე (4-7 დღე)',
                                'available' => 'ხელმისაწვდომი (8+ დღე)',
                                'no_expiry' => 'ვადა არ აქვს',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['expiration_filter'] === 'expired',
                                fn (Builder $query) => $query->whereDate('expires_at', '<', now())
                            )
                            ->when(
                                $data['expiration_filter'] === 'urgent',
                                fn (Builder $query) => $query->whereDate('expires_at', '>=', now())
                                    ->whereDate('expires_at', '<=', now()->addDays(3))
                            )
                            ->when(
                                $data['expiration_filter'] === 'soon',
                                fn (Builder $query) => $query->whereDate('expires_at', '>=', now()->addDays(4))
                                    ->whereDate('expires_at', '<=', now()->addDays(7))
                            )
                            ->when(
                                $data['expiration_filter'] === 'available',
                                fn (Builder $query) => $query->whereDate('expires_at', '>', now()->addDays(7))
                            )
                            ->when(
                                $data['expiration_filter'] === 'no_expiry',
                                fn (Builder $query) => $query->whereNull('expires_at')
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
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('ძირითადი ინფორმაცია')
                    ->schema([
                        Infolists\Components\TextEntry::make('company.name')
                            ->label('კომპანია')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('title')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('slug')
                            ->label('სლაგი'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('აღწერა')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('დეტალები')
                    ->schema([
                        Infolists\Components\TextEntry::make('city')
                            ->label('ქალაქი'),
                        Infolists\Components\TextEntry::make('location')
                            ->label('ლოკაცია'),
                        Infolists\Components\TextEntry::make('employment_type')
                            ->label('დასაქმების ტიპი')
                            ->badge(),
                        Infolists\Components\TextEntry::make('experience_level')
                            ->label('გამოცდილების დონე')
                            ->badge(),
                        Infolists\Components\TextEntry::make('department')
                            ->label('დეპარტამენტი'),
                        Infolists\Components\TextEntry::make('salary_range')
                            ->label('ხელფასი')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('expires_at')
                            ->label('ვადის გასვლა')
                            ->date('d/m/Y')
                            ->placeholder('ვადა არ აქვს'),
                        Infolists\Components\TextEntry::make('days_left')
                            ->label('დარჩენილი დღეები')
                            ->badge()
                            ->color(fn ($state): string => match (true) {
                                $state === null => 'gray',
                                $state <= 0 => 'gray',
                                $state <= 3 => 'danger',
                                $state <= 7 => 'warning',
                                default => 'success',
                            })
                            ->formatStateUsing(fn ($state): string => $state === null ? 'უსასრულო' : ($state > 0 ? $state . ' დღე' : 'ვადა გაუვიდა')),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('დამატებითი ინფორმაცია')
                    ->schema([
                        Infolists\Components\TextEntry::make('requirements')
                            ->label('მოთხოვნები')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('responsibilities')
                            ->label('პასუხისმგებლობა')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('benefits')
                            ->label('პრივილეგიები')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
                Infolists\Components\Section::make('განაცხადის ინფორმაცია')
                    ->schema([
                        Infolists\Components\TextEntry::make('application_email')
                            ->label('განაცხადის ელფოსტა')
                            ->placeholder('არ არის მითითებული'),
                        Infolists\Components\TextEntry::make('application_url')
                            ->label('განაცხადის URL')
                            ->placeholder('არ არის მითითებული'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Infolists\Components\Section::make('სტატუსი')
                    ->schema([
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('აქტიური')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        Infolists\Components\IconEntry::make('is_featured')
                            ->label('პრემიუმი')
                            ->boolean()
                            ->trueIcon('heroicon-o-star')
                            ->falseIcon('heroicon-o-star')
                            ->trueColor('warning')
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
            RelationManagers\ApplicationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVacancies::route('/'),
            'create' => Pages\CreateVacancy::route('/create'),
            'view' => Pages\ViewVacancy::route('/{record}'),
            'edit' => Pages\EditVacancy::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}

