<?php

namespace App\Filament\Resources;

use App\Enums\ProductType;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $label = 'Товар';
    protected static ?string $pluralLabel = 'Товары';

    protected static ?string $navigationGroup = 'Справочники';
    protected static ?string $navigationIcon = 'phosphor-shopping-cart-simple';
    protected static ?string $activeNavigationIcon = 'phosphor-shopping-cart-simple-fill';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->unique(Product::class, 'slug', ignoreRecord: true),

                Forms\Components\Textarea::make('description')
                    ->label('Описание')
                    ->columnSpanFull(),

                Forms\Components\Repeater::make('offers')
                    ->label('Предложения')
                    ->defaultItems(0)
                    ->schema([
                        Forms\Components\TextInput::make('external_id')
                            ->label('Внешний ID')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('price')
                            ->label('Цена')
                            ->numeric()
                            ->required(),
                    ])->columnSpan('full')
            ])->columnSpan(['lg' => 2]),

            Forms\Components\Group::make([
                Forms\Components\Section::make('Параметры')->schema([
                    Forms\Components\Toggle::make('active')
                        ->label('Активен')
                        ->required(),
                    Forms\Components\TextInput::make('external_id')
                        ->label('Внешний ID')
                        ->maxLength(36)
                        ->disabled(),
                    Forms\Components\TextInput::make('price')
                        ->label('Цена')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('code')
                        ->label('Код')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('sku')
                        ->label('SKU')
                        ->maxLength(255),
                    Forms\Components\Select::make('type')
                        ->label('Тип')
                        ->options(ProductType::class)
                        ->default(ProductType::PRODUCT)
                        ->native(false)
                        ->required(),
                ])->columns(1),
            ]),
        ])->columns(['lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('external_id')
                    ->label('Внешний ИД')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('rub')
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Код')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('Артикул')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
