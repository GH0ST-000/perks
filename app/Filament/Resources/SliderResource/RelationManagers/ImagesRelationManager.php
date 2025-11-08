<?php

namespace App\Filament\Resources\SliderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('sliders')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(5120)
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('link')
                    ->url()
                    ->maxLength(255)
                    ->columnSpan(1),
                Forms\Components\TextInput::make('link_text')
                    ->label('Link Text')
                    ->maxLength(255)
                    ->columnSpan(1),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required()
                    ->columnSpan(1),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->disk('public')
                    ->size(100),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('link')
                    ->limit(30)
                    ->copyable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All images')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('bulk_upload')
                    ->label('Bulk Upload Images')
                    ->icon('heroicon-o-photo')
                    ->color('success')
                    ->form([
                        Forms\Components\FileUpload::make('images')
                            ->label('Upload Images (5-6 images)')
                            ->image()
                            ->disk('public')
                            ->directory('sliders')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->multiple()
                            ->minFiles(1)
                            ->maxFiles(6)
                            ->required()
                            ->helperText('You can upload up to 6 images at once')
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data) {
                        $slider = $this->ownerRecord;
                        $order = $slider->images()->max('order') ?? 0;
                        
                        foreach ($data['images'] as $imagePath) {
                            $slider->images()->create([
                                'image_path' => $imagePath,
                                'order' => ++$order,
                                'is_active' => true,
                            ]);
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Images Uploaded')
                            ->body(count($data['images']) . ' images uploaded successfully')
                            ->send();
                    }),
                Tables\Actions\CreateAction::make(),
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
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }
}
