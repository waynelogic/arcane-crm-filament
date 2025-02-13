<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Gender : string implements HasLabel, HasColor
{
    case MALE = 'male';
    case FEMALE = 'female';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::MALE => 'Мужской',
            self::FEMALE => 'Женский',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MALE => 'success',
            self::FEMALE => 'warning',
        };
    }
}
