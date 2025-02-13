<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Panel;
use Filament\Resources\Pages\PageRegistration;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use Filament\Forms\Components\Concerns;

class ProjectTasksKanban extends KanbanBoard
{
    use Concerns\HasColors;

    protected static string $model = Task::class;
    protected static string $statusEnum = TaskStatus::class;


    protected static string $headerView = 'filament.kanban.project.kanban-header';
//    protected function records(): Collection
//    {
//        return $this->getEloquentQuery()
//            ->when(method_exists(static::$model, 'scopeOrdered'), fn ($query) => $query->ordered())
//            ->where('project_id', RouteFacade::current()->parameter('record'))
//            ->get();
//    }


    public static function route(string $path): PageRegistration
    {
        return new PageRegistration(
            page: static::class,
            route: fn (Panel $panel): Route => RouteFacade::get($path, static::class)
                ->middleware(static::getRouteMiddleware($panel))
                ->withoutMiddleware(static::getWithoutRouteMiddleware($panel)),
        );
    }

    protected ?string $maxContentWidth = 'full';
}
