<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $label = 'Клиент';
    protected static ?string $pluralLabel = 'Клиенты';

    protected static ?string $navigationGroup = 'Взаимодействия';
    protected static ?string $navigationIcon = 'phosphor-users';
    protected static ?string $activeNavigationIcon = 'phosphor-users-fill';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make([
                Forms\Components\Fieldset::make('Отношения')->schema([
                    Forms\Components\Toggle::make('is_customer')
                        ->label('Покупатель')
                        ->default(true)
                        ->required(),
                    Forms\Components\Toggle::make('is_supplier')
                        ->label('Поставщик')
                        ->required(),
                ]),
                Forms\Components\TextInput::make('legal_name')
                    ->label('Юридическое наименование')
                    ->prefixIcon('phosphor-building')
                    ->maxLength(255),


                Forms\Components\TextInput::make('inn')
                    ->label('ИНН')
                    ->prefixIcon('phosphor-book-open-text')
                    ->maxLength(12),
                Forms\Components\TextInput::make('kpp')
                    ->label('КПП')
                    ->prefixIcon('phosphor-arrow-bend-down-left')
                    ->disabled(fn(Forms\Get $get) => strlen($get('inn')) > 10)
                    ->maxLength(9),
                Forms\Components\TextInput::make('email')
                    ->label('Электронная почта')
                    ->prefixIcon('phosphor-envelope-simple')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Адрес')
                    ->prefixIcon('phosphor-map-pin')
                    ->maxLength(255),
                Forms\Components\Toggle::make('active')
                    ->required(),

            ])->columnSpan(['lg' => 2])->columns(2),

            Forms\Components\Group::make([

                SpatieMediaLibraryFileUpload::make('company_logo')
                    ->label('Логотип')
                    ->collection('company_logos'),

                Forms\Components\TextInput::make('name')
                    ->label('Название')
                    ->prefixIcon('phosphor-tag')
                    ->required()
                    ->maxLength(255),

//                Forms\Components\TextInput::make('external_id')
//                    ->label('Идентификатор')
//                    ->disabled()
//                    ->maxLength(36),
                Forms\Components\Select::make('manager_id')
                    ->label('Ответственный')
                    ->prefixIcon('phosphor-user')
                    ->preload()
                    ->native(false)
                    ->default(auth()->user()->id)
                    ->relationship('manager', 'name'),
            ]),

        ])->columns(['lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('legal_name')
                    ->label('Юр. наим.')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inn')
                    ->label('ИНН')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kpp')
                    ->label('КПП')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            CustomerResource\RelationManagers\ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
