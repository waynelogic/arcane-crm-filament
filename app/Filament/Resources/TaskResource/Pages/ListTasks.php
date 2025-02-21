<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Enums\TaskStatus;
use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $enumStatus = TaskStatus::cases();

        $arTabs = [
            null => Tab::make('Все'),
        ];
        foreach ($enumStatus as $status) {
            $arTabs[$status->value] = Tab::make($status->getLabel())->query(fn ($query) => $query->where('status', $status->value));
        }
        return $arTabs;
    }

    protected ?string $maxContentWidth = 'full';
}
