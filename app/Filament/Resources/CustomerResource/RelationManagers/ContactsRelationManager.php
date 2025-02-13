<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Enums\PersonRole;
use App\Filament\Forms\Components\PhoneInput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactsRelationManager extends RelationManager
{
    protected static ?string $title = 'Сотрудники';
    protected static string $relationship = 'contacts';

    protected static ?string $inverseRelationship = 'companies';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Имя')
                ->prefixIcon('phosphor-tag')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('position')
                ->prefixIcon('phosphor-identification-badge')
                ->label('Должность')
                ->maxLength(255),
            Forms\Components\Select::make('role')
                ->label('Роль')
                ->options(PersonRole::class),
//            Forms\Components\TextInput::make('phone')
//                ->label('Телефон')
//                ->prefixIcon('phosphor-microphone')
//                ->mask('+7 (999) 999-99-99')
//                ->maxLength(255),
            PhoneInput::make('phone'),
        ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя'),
                Tables\Columns\TextColumn::make('position')
                    ->label('Должность'),
                Tables\Columns\SelectColumn::make('role')
                    ->label('Роль')
                    ->options(PersonRole::class),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn ($action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('position')
                            ->label('Должность')
                            ->prefixIcon('phosphor-identification-badge')
                            ->maxLength(255),
                        Forms\Components\Select::make('role')
                            ->options(PersonRole::class)
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
