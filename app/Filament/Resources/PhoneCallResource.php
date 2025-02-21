<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\PhoneInput;
use App\Filament\Resources\PhoneCallResource\Pages;
use App\Filament\Resources\PhoneCallResource\RelationManagers;
use App\Models\PhoneCall;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PhoneCallResource extends Resource
{
    protected static ?string $model = PhoneCall::class;

    protected static ?string $label = 'Звонок';
    protected static ?string $pluralLabel = 'Звонки';
    protected static ?string $navigationGroup = 'Взаимодействия';
    protected static ?string $navigationIcon = 'phosphor-phone-call';
    protected static ?string $activeNavigationIcon = 'phosphor-phone-call-fill';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(null)->schema([
                    PhoneInput::make('phone'),

                    Forms\Components\Select::make('contact_id')
                        ->label('Контакт')
                        ->prefixIcon('phosphor-address-book')
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('new-contact')
                                ->label('Новый контакт')
                                ->icon('heroicon-o-user')
                                ->form([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255)
                                ])
                                ->action(function ($action, array $data, Forms\Get $get, Forms\Set $set) {
                                    $phone = $get('phone');
                                    $obContact = User::query()
                                        ->where('phone', $phone)
                                        ->first();

                                    if ($obContact) {
                                        Notification::make()
                                            ->title('Контакт уже существует')
                                            ->warning()
                                            ->send();
                                    } else {
                                        $obContact = User::create([
                                            'name' => $data['name'],
                                            'phone' => $phone
                                        ]);
                                        $set('contact_id', $obContact->id);
                                    }
                                })
                        )
                        ->relationship('contact', 'name'),
                ])->columns(2),


                Forms\Components\Select::make('deal_id')
                    ->label('Сделка')
                    ->prefixIcon('heroicon-s-briefcase')
                    ->relationship('deal', 'title'),

                Forms\Components\Select::make('manager_id')
                    ->label('Менеджер')
                    ->native(false)
                    ->prefixIcon('heroicon-o-users')
                    ->relationship('manager', 'name')
                    ->default(auth()->user()->id)
                    ->required(),

                Forms\Components\Textarea::make('comment')
                    ->label('Комментарий'),

                SpatieMediaLibraryFileUpload::make('call_file')
                    ->label('Файл звонка')
                    ->collection('call_files'),

                Forms\Components\Textarea::make('transcription')
                    ->label('Транскрипция')
                    ->autosize()
                    ->hidden(fn ($record) => !$record || empty($record->transcription))
                    ->columnSpan('full'),

                Forms\Components\Tabs::make('Tabs')->tabs([

                    Forms\Components\Tabs\Tab::make('События')->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Заголовок'),
                        Forms\Components\Textarea::make('description')
                            ->label('Описание'),
                        Forms\Components\DateTimePicker::make('start')
                            ->label('Начало')
                            ->seconds(false),
                        Forms\Components\TextInput::make('duration')
                            ->label('Длительность')
                            ->numeric(),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('create_event')
                                ->label('Создать событие')
                                ->hidden(fn (PhoneCall $record) => $record->ai_payload['event']['used'] ?? false)
                                ->action(function (PhoneCall $record) {
                                    $record->createEvent();
                                }),
                        ]),
                    ])->statePath('event'),

                    Forms\Components\Tabs\Tab::make('Сделка')->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Заголовок'),
                        Forms\Components\Textarea::make('description')
                            ->label('Описание')
                            ->rows(4),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->label('Цена'),
                    ])->statePath('deal'),

                ])
                    ->columnSpan('full')
                    ->hidden(function (PhoneCall $record) {
                        if (isset($record->ai_payload['event'])) {
                            return false;
                        }
                        return true;
                    })
                    ->statePath('ai_payload'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->description(fn($record) => $record->contact?->name ?? null)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contact.name')
                    ->label('Контакт')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deal.title')
                    ->label('Сделка')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Менеджер')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y - H:i')
                    ->sortable(),
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
            'index' => Pages\ListPhoneCalls::route('/'),
            'create' => Pages\CreatePhoneCall::route('/create'),
            'edit' => Pages\EditPhoneCall::route('/{record}/edit'),
        ];
    }
}
