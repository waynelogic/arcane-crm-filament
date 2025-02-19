<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkAreaResource\Pages;
use App\Filament\Resources\WorkAreaResource\RelationManagers;
use App\Models\WorkArea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkAreaResource extends Resource
{
    protected static ?string $model = WorkArea::class;

    protected static ?string $label = 'Направление работы';
    protected static ?string $pluralLabel = 'Направления';
    protected static ?string $navigationGroup = 'Справочники';
    protected static ?string $navigationIcon = 'phosphor-intersect-three';
    protected static ?string $activeNavigationIcon = 'phosphor-intersect-three-fill';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название')
                    ->prefixIcon('phosphor-tag')
                    ->required()
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->prefixIcon('phosphor-hand')
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->unique(WorkArea::class, 'slug', ignoreRecord: true),

                Forms\Components\TextInput::make('hour_price')
                    ->label('Цена за час работы')
                    ->prefixIcon('phosphor-money')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Описание'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Handle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hour_price')
                    ->label('Цена за час работы')
                    ->money('rub')
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListWorkAreas::route('/'),
            'create' => Pages\CreateWorkArea::route('/create'),
            'edit' => Pages\EditWorkArea::route('/{record}/edit'),
        ];
    }
}
