<?php

namespace App\Filament\Resources;

use App\Enums\TaskStatus;
use App\Filament\Forms\Components\StatusButtons;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $label = 'Задача';
    protected static ?string $pluralLabel = 'Задачи';
    protected static ?string $navigationGroup = 'Деяльность';
    protected static ?string $navigationIcon = 'phosphor-cards-three';
    protected static ?string $activeNavigationIcon = 'phosphor-cards-three-fill';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make([

                StatusButtons::make('status')
                    ->hiddenLabel()
                    ->options(TaskStatus::class)
                    ->default(TaskStatus::NEW)
                    ->required(),

                Forms\Components\Fieldset::make('Параметры')->schema([
                    Forms\Components\Toggle::make('completed')
                        ->label('Выполнено')
                        ->required(),
                    Forms\Components\Toggle::make('important')
                        ->label('Важное')
                        ->required(),
                    Forms\Components\Toggle::make('urgent')
                        ->label('Срочное')
                        ->required(),
                ])->columns(3),

                Forms\Components\Section::make('Данные')->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Заголовок')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\RichEditor::make('description')
                        ->label('Описание')
                        ->columnSpanFull(),
                ]),

            ])->columnSpan(['lg' => 2]),

            Forms\Components\Group::make([
                Forms\Components\Section::make('Парамерты')->schema([
                    Forms\Components\DateTimePicker::make('deadline')
                        ->label('Дедлайн'),
//                    Forms\Components\TextInput::make('hour_price')
//                        ->numeric(),

                    Forms\Components\TextInput::make('hours')
                        ->label('Часы')
                        ->numeric(),
                ]),
                Forms\Components\Section::make('Связи')->schema([
                    Forms\Components\Select::make('manager_id')
                        ->label('Менеджер')
                        ->prefixIcon('phosphor-users-three')
                        ->preload()
                        ->native(false)
                        ->relationship('manager', 'name')
                        ->default(auth()->user()->id)
                        ->required(),
                    Forms\Components\Select::make('project_id')
                        ->label('Проект')
                        ->relationship('project', 'name'),
                    Forms\Components\Select::make('deal_id')
                        ->label('Сделка')
                        ->prefixIcon('heroicon-o-briefcase')
                        ->relationship('deal', 'title'),
                    Forms\Components\Select::make('parent_id')
                        ->label('Родитель')
                        ->relationship(name: 'parent', titleAttribute: 'title'),
                    Forms\Components\Select::make('work_area_id')
                        ->label('Рабочая область')
                        ->relationship('workArea', 'name'),
                ]),
            ]),












//            Forms\Components\TextInput::make('work_time')
//                ->numeric(),
//            Forms\Components\DateTimePicker::make('played_at'),
//            Forms\Components\DateTimePicker::make('started_at'),
//            Forms\Components\DateTimePicker::make('completed_at'),
        ])->columns(['lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('external_id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Менеджер')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deal.title')
                    ->label('Сделка')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Родитель')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('workArea.name')
                    ->label('Рабочая область')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Проект')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->searchable(),
                Tables\Columns\IconColumn::make('completed')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                Tables\Columns\IconColumn::make('important')
                    ->label('Важная')
                    ->boolean(),
                Tables\Columns\IconColumn::make('urgent')
                    ->label('Срочная')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Дедлайн')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hour_price')
                    ->label('Цена часа')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('hours')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('work_time')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\Action::make('start')
                    ->button()
                    ->label('Начать')
                    ->color('success')
                    ->hidden(fn ($record) => isset($record->played_at))
                    ->action(fn ($record) => $record->play()),

                Tables\Actions\Action::make('pause')
                    ->button()
                    ->label('Пауза')
                    ->color('warning')
                    ->hidden(fn ($record) => !isset($record->played_at))
                    ->action(fn ($record) => $record->pause()),

                Tables\Actions\Action::make('stop')
                    ->button()
                    ->label('Стоп')
                    ->color('danger')
                    ->hidden(fn ($record) => !isset($record->played_at))
                    ->action(fn ($record) => $record->stop()),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
