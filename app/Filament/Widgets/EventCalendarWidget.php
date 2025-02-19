<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\TextInput;
use \Guava\Calendar\Widgets\CalendarWidget;
use Guava\Calendar\ValueObjects\Event;
use Illuminate\Support\Collection;
class EventCalendarWidget extends CalendarWidget
{
    protected int $firstDay = 1;


    public function getSchema(?string $model = null): ?array
    {
        return [
            TextInput::make('title')
        ];
    }

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        $arEvents = \App\Models\Event::all();
        return  $arEvents;

//        return [
//            // Chainable object-oriented variant
//            Event::make()
//                ->title('My first event')
//                ->start(today())
//                ->end(today()),
//
//            // Array variant
//            ['title' => 'My second event', 'start' => today()->addDays(3), 'end' => today()->addDays(3)],
//
//        ];
    }
//    protected static string $view = 'filament.widgets.event-calendar-widget';
}
