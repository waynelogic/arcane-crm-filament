<?php namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
enum ProductType : string implements HasLabel, HasColor
{
    case SERVICE = 'service';
    case PRODUCT = 'product';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SERVICE => 'Услуга',
            self::PRODUCT => 'Товар',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SERVICE => 'success',
            self::PRODUCT => 'primary',
        };
    }
}
