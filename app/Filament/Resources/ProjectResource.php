<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $label = 'Проект';
    protected static ?string $pluralLabel = 'Проекты';
    protected static ?string $navigationGroup = 'Деяльность';
    protected static ?string $navigationIcon = 'phosphor-blueprint';
    protected static ?string $activeNavigationIcon = 'phosphor-blueprint-fill';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Данные')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Название')
                        ->prefixIcon('phosphor-tag')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('active')
                        ->label('Активен')
                        ->inline(false)
                        ->default(true)
                        ->required(),

                    Forms\Components\Select::make('customer_id')
                        ->label('Компания')
                        ->prefixIcon('phosphor-users')
                        ->preload()
                        ->native(false)
                        ->relationship('company', 'name'),

                    Forms\Components\Select::make('manager_id')
                        ->label('Менеджер')
                        ->prefixIcon('phosphor-users-three')
                        ->preload()
                        ->native(false)
                        ->relationship('manager', 'name')
                        ->default(auth()->user()->id)
                        ->required(),
                ])->columns(3),


                Forms\Components\RichEditor::make('description')
                    ->label('Описание')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('external_id')
                    ->label('Внешний ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Менеджер')
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Компания')
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->label('В работе')
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
                Tables\Actions\EditAction::make()
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
            ProjectResource\RelationManagers\TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'kanban' => Pages\ProjectTasksKanban::route('/{record}/kanban'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
