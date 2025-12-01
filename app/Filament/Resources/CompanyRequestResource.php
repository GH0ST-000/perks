<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyRequestResource\Pages;
use App\Filament\Resources\CompanyRequestResource\RelationManagers;
use App\Models\CompanyRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyRequestResource extends Resource
{
    protected static ?string $model = CompanyRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'კომპანიების მოთხოვნები';

    protected static ?string $modelLabel = 'კომპანიის მოთხოვნა';

    protected static ?string $pluralModelLabel = 'კომპანიების მოთხოვნები';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->label('კომპანიის სახელი')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                Forms\Components\TextInput::make('contact_person')
                    ->label('საკონტაქტო პირი')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('ელ-ფოსტა')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('ტელეფონი')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('employees')
                    ->label('თანამშრომლების რაოდენობა')
                    ->numeric(),
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
                Tables\Columns\TextColumn::make('company_name')
                    ->label('კომპანია')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->label('საკონტაქტო პირი')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('ელ-ფოსტა')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('ტელეფონი')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('employees')
                    ->label('თანამშრომლები')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListCompanyRequests::route('/'),
            'create' => Pages\CreateCompanyRequest::route('/create'),
            'edit' => Pages\EditCompanyRequest::route('/{record}/edit'),
        ];
    }
}
