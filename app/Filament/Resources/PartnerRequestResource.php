<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerRequestResource\Pages;
use App\Filament\Resources\PartnerRequestResource\RelationManagers;
use App\Models\PartnerRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartnerRequestResource extends Resource
{
    protected static ?string $model = PartnerRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'პარტნიორების მოთხოვნები';

    protected static ?string $modelLabel = 'პარტნიორის მოთხოვნა';

    protected static ?string $pluralModelLabel = 'პარტნიორების მოთხოვნები';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('business_name')
                    ->label('ბიზნესის სახელი')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\Select::make('category')
                    ->label('კატეგორია')
                    ->options([
                        'restaurant' => 'რესტორანი',
                        'hotel' => 'სასტუმრო',
                        'fitness' => 'ფიტნესი',
                        'wellness' => 'ველნესი',
                        'entertainment' => 'გასართობი',
                        'shopping' => 'შოპინგი',
                        'cafe' => 'კაფე',
                        'spa' => 'სპა',
                        'other' => 'სხვა',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('contact_person')
                    ->label('საკონტაქტო პირი')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('ტელეფონი')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->label('ვებ-გვერდი')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('სტატუსი')
                    ->options([
                        'pending' => 'მოლოდინში',
                        'contacted' => 'დაკავშირებული',
                        'approved' => 'დამტკიცებული',
                        'rejected' => 'უარყოფილი',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('შენიშვნები')
                    ->columnSpanFull()
                    ->rows(4),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->label('ბიზნესი')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('კატეგორია')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'restaurant' => 'რესტორანი',
                        'hotel' => 'სასტუმრო',
                        'fitness' => 'ფიტნესი',
                        'wellness' => 'ველნესი',
                        'entertainment' => 'გასართობი',
                        'shopping' => 'შოპინგი',
                        'cafe' => 'კაფე',
                        'spa' => 'სპა',
                        'other' => 'სხვა',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('contact_person')
                    ->label('საკონტაქტო პირი')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('ტელეფონი')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('website')
                    ->label('ვებ-გვერდი')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('სტატუსი')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'contacted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'მოლოდინში',
                        'contacted' => 'დაკავშირებული',
                        'approved' => 'დამტკიცებული',
                        'rejected' => 'უარყოფილი',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('თარიღი')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('სტატუსი')
                    ->options([
                        'pending' => 'მოლოდინში',
                        'contacted' => 'დაკავშირებული',
                        'approved' => 'დამტკიცებული',
                        'rejected' => 'უარყოფილი',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->label('კატეგორია')
                    ->options([
                        'restaurant' => 'რესტორანი',
                        'hotel' => 'სასტუმრო',
                        'fitness' => 'ფიტნესი',
                        'wellness' => 'ველნესი',
                        'entertainment' => 'გასართობი',
                        'shopping' => 'შოპინგი',
                        'cafe' => 'კაფე',
                        'spa' => 'სპა',
                        'other' => 'სხვა',
                    ]),
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
            'index' => Pages\ListPartnerRequests::route('/'),
            'create' => Pages\CreatePartnerRequest::route('/create'),
            'edit' => Pages\EditPartnerRequest::route('/{record}/edit'),
        ];
    }
}
