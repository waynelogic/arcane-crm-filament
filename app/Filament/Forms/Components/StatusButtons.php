<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\ToggleButtons;

class StatusButtons extends ToggleButtons
{

    public const GROUPED_VIEW = 'filament.forms.components.status-buttons';

    protected string $view = 'filament.forms.components.status-buttons';

    public static function make(string $name): static
    {
        return parent::make($name)->grouped()->inline();
    }
}
