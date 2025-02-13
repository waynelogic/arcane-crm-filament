<?php namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum WorkStatus : string
{
    case IN_PROGRESS = 'in-progress';
    case PAUSED = 'paused';
    case STOPPED = 'stopped';
}
