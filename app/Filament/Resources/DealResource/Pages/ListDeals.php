<?php

namespace App\Filament\Resources\DealResource\Pages;

use App\Enums\DealStatus;
use App\Filament\Resources\DealResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListDeals extends ListRecords
{
    protected static string $resource = DealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $enumStatus = DealStatus::cases();

        $arTabs = [
            null => Tab::make('Все'),
        ];
        foreach ($enumStatus as $status) {
            $arTabs[$status->value] = Tab::make($status->getLabel())->query(fn ($query) => $query->where('status', $status->value));
        }
        return $arTabs;
    }
}
