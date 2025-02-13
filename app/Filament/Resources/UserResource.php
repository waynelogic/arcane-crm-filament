<?php

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Filament\Forms\Components\PhoneInput;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $label = 'Контакт';
    protected static ?string $pluralLabel = 'Контакты';
    protected static ?string $navigationIcon = 'phosphor-address-book';
    protected static ?string $activeNavigationIcon = 'phosphor-address-book-fill';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Имя')
                ->prefixIcon('phosphor-tag')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label('Электронная почта')
                ->prefixIcon('phosphor-envelope-simple')
                ->email()
                ->maxLength(255),
            PhoneInput::make('phone'),
            Forms\Components\TextInput::make('address')
                ->label('Адрес')
                ->prefixIcon('phosphor-map-pin')
                ->maxLength(255),
            Forms\Components\ToggleButtons::make('gender')
                ->label('Пол')
                ->inline()
                ->options(Gender::class),
            Forms\Components\DatePicker::make('birthday')
                ->label('Дата рождения')
                ->prefixIcon('phosphor-calendar'),

            Forms\Components\Section::make('Личный кабинет')->schema([
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Дата верификации')
                    ->prefixIcon('phosphor-calendar-check'),
                Forms\Components\TextInput::make('password')
                    ->label('Пароль')
                    ->prefixIcon('phosphor-lock')
                    ->password()
                    ->maxLength(255),
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('external_id')
                    ->label('Внешний ИД')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Адрес')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Пол')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Дата рождения')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Дата верификации')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
