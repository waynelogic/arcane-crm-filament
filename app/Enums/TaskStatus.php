<?php namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Collection;

enum TaskStatus : string implements HasColor, HasLabel
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case SUCCESS = 'success';
    case CANCELED = 'canceled';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::NEW => 'Новая',
            self::IN_PROGRESS => 'В работе',
            self::SUCCESS => 'Выполнена',
            self::CANCELED => 'Отменена',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NEW => 'primary',
            self::IN_PROGRESS => 'warning',
            self::SUCCESS => 'success',
            self::CANCELED => 'danger',
        };
    }

    public static function statuses(): Collection
    {
        $arStatuses = [];

        foreach (self::cases() as $status) {
            $arStatuses[] = [
                'id' => $status->value,
                'title' => $status->getLabel(),
            ];
        }

        return collect($arStatuses);
    }
}
