<?php

namespace App\Filament\Resources;

use App\Enums\DealStatus;
use App\Enums\ProductType;
use App\Filament\Forms\Components\StatusButtons;
use App\Filament\Resources\DealResource\Pages;
use App\Filament\Resources\DealResource\RelationManagers;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DealResource extends Resource
{
    protected static ?string $model = Deal::class;

    protected static ?string $label = 'Сделка';
    protected static ?string $pluralLabel = 'Сделки';

    protected static ?string $navigationGroup = 'Деяльность';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $activeNavigationIcon = 'heroicon-s-briefcase';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make([

                StatusButtons::make('status')
                    ->hiddenLabel()
                    ->options(DealStatus::class)
                    ->default(DealStatus::NEW)
                    ->required(),

                Forms\Components\Group::make([

                    Forms\Components\Section::make('Данные')->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description')
                            ->label('Описание')
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('deadline')
                            ->label('Дедлайн')
                            ->default(now()->addDays(7))
                            ->required(),
                    ]),

                    Forms\Components\Section::make('Расчеты')->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Стоимость')
                            ->required()
                            ->prefixIcon('heroicon-o-banknotes')
                            ->numeric(),
                        Forms\Components\TextInput::make('total_discount')
                            ->prefixIcon('heroicon-o-sparkles')
                            ->label('Скидка')
                            ->numeric(),
                        Forms\Components\Toggle::make('auto_price')
                            ->label('Автоматическая цена')
                            ->required(),
                    ])->columns(2),

                    Forms\Components\Repeater::make('product_items')->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Товар')
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('price', Product::find($state)->price) : null)
                            ->native(false)
                            ->options(Product::query()->where('type', ProductType::PRODUCT)->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Количество')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('Цена')
                            ->numeric()
                            ->required(),
                    ])->defaultItems(0)
                        ->label('Товары')
                        ->addActionLabel('Добавить товар')
                        ->columns(1),

                ]),

            ])->columnSpan(['lg' => 2]),

            Forms\Components\Group::make([

                Forms\Components\Section::make('Связи')->schema([

                    Forms\Components\TextInput::make('number')
                        ->label('Номер')
                        ->maxLength(36)
                        ->disabled(),

                    Forms\Components\Select::make('parent_id')
                        ->label('Родительская сделка')
                        ->native(false)
                        ->relationship('parent', 'title'),

                    Forms\Components\Select::make('customer_id')
                        ->label('Компания')
                        ->preload()
                        ->native(false)
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required(),
                            Forms\Components\TextInput::make('inn')
                                ->maxLength(12)
                                ->numeric(),
                        ])
                        ->relationship('customer', 'name'),

                    Forms\Components\Select::make('contact_id')
                        ->label('Контакт')
                        ->preload()
                        ->native(false)
                        ->relationship(
                            name: 'contact',
                            titleAttribute: 'name'
                        ),

                    Forms\Components\Select::make('manager_id')
                        ->label('Менеджер')
                        ->preload()
                        ->native(false)
                        ->relationship('manager', 'name')
                        ->default(auth()->user()->id)
                        ->required(),
                ]),

            ]),

        ])->columns(['lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Компания')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact.name')
                    ->label('Контакт')
                    ->sortable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Менеджер')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Стоимость')
                    ->money('rub')
                    ->weight(FontWeight::Bold)
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()->money('rub'),
                    ])
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('auto_price')
                    ->label('Авторасчет')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Дедлайн')
                    ->dateTime()
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
            DealResource\RelationManagers\TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeals::route('/'),
            'create' => Pages\CreateDeal::route('/create'),
            'edit' => Pages\EditDeal::route('/{record}/edit'),
        ];
    }
}
