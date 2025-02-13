<?php namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PersonRole : string implements HasLabel
{
    case ACCOUNTANT = 'accountant';
    case CEO = 'ceo';
    case MANAGER = 'manager';
    case PROJECT_MANAGER = 'project_manager';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CEO => 'Генеральный директор',
            self::ACCOUNTANT => 'Бухгалтер',
            self::MANAGER => 'Менеджер',
            self::PROJECT_MANAGER => 'Менеджер проекта',
        };
    }
}
