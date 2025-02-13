<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('make-from-inn')
                ->label('Создать по ИНН')
                ->icon('phosphor-blueprint')
                ->form([
                    TextInput::make('name')
                        ->label('Название')
                        ->required(),
                    TextInput::make('inn')
                        ->label('ИНН')
                        ->maxLength(12)
                        ->numeric()
                        ->required(),
                ])
                ->modalWidth('lg')
                ->action(function (array $data) {
                    $inn = $data['inn'];
                    $obCompany = Customer::where('inn', $inn)->first();
                    if (!$obCompany) {
                        $obCompany = Customer::query()->create([
                            'inn' => $inn,
                            'name' => $data['name'],
                        ]);
                    }
                    $url = CustomerResource::getUrl('edit', ['record' => $obCompany]);
                    $this->redirect($url);
                })
        ];
    }
}
