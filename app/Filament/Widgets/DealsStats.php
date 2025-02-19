<?php

namespace App\Filament\Widgets;

use App\Enums\TaskStatus;
use App\Models\Deal;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DealsStats extends BaseWidget
{
    protected function getStats(): array
    {
        $startDate = Carbon::now()->startOfWeek(); // Понедельник
        $endDate = Carbon::now()->endOfWeek();     // Воскресенье
        $currentWeekDeals = Deal::whereBetween('created_at', [$startDate, $endDate])->get();

        $deals = $currentWeekDeals->groupBy(function ($deal) {
            return $deal->created_at->format('N');
        });

        $arDays = [];
        for ($i = 1; $i <= 7; $i++) {
            if ($deals->has($i)) {
                $arDays[$i] = $deals[$i]->count();
            } else {
                $arDays[$i] = 0;
            }
        }
        $status = $arDays[6] < $arDays[7] ? 'success' : 'danger';

        $activeTasks = Task::where('status', TaskStatus::IN_PROGRESS)->count();
        $activeProjects = Project::where('active', true)->count();
        return [
            Stat::make('Сделок на неделю', $currentWeekDeals->count())
                ->chart($arDays)
                ->color($status),
            Stat::make('Количество активных задач', $activeTasks),
            Stat::make('Проектов в работе', $activeProjects),
        ];
    }
}
