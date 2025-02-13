<?php

namespace App\Filament\Forms\Components;
use Filament\Forms\Components\TextInput;
class PhoneInput extends TextInput
{
    public static function make(string $name): static
    {
        return parent::make($name)->label('Телефон')
            ->prefixIcon('phosphor-microphone')
            ->mask('+7 (999) 999-99-99')
            ->minLength(18)
            ->maxLength(255);
    }
}
