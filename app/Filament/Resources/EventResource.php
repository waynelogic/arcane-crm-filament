<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $label = 'Событие';
    protected static ?string $pluralLabel = 'События';
    protected static ?string $navigationGroup = 'Взаимодействия';
    protected static ?string $navigationIcon = 'phosphor-calendar';
    protected static ?string $activeNavigationIcon = 'phosphor-calendar-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Название')
                    ->prefixIcon('phosphor-tag')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Описание')
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('start')
                    ->label('Начало')
                    ->seconds(false)
                    ->prefixIcon('phosphor-calendar-plus')
                    ->required(),
                Forms\Components\DateTimePicker::make('end')
                    ->label('Конец')
                    ->seconds(false)
                    ->prefixIcon('phosphor-calendar-check')
                    ->required(),

                Forms\Components\Select::make('manager_id')
                    ->label('Менеджер')
                    ->native(false)
                    ->prefixIcon('heroicon-o-users')
                    ->relationship('manager', 'name')
                    ->default(auth()->user()->id)
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Контакт')
                    ->prefixIcon('phosphor-address-book')
                    ->relationship('contact', 'name'),

                Forms\Components\Toggle::make('allDay')
                    ->label('Весь день')
                    ->default(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start')
                    ->label('Начало')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end')
                    ->label('Конец')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Менеджер')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contact.name')
                    ->label('Контакт')
                    ->sortable(),

                Tables\Columns\IconColumn::make('allDay')
                    ->label('Весь день')
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
            'index' => Pages\ListEvents::route('/'),
//            'create' => Pages\CreateEvent::route('/create'),
//            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
