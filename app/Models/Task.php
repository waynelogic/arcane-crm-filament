<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\WorkStatus;
use App\Service\Database\Traits\HasExternalId;
use App\Service\Database\Traits\Manageable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use Manageable, HasExternalId;

    protected $casts = [
        'status' => TaskStatus::class,
        'work_status' => WorkStatus::class,
        'completed' => 'boolean',
        'important' => 'boolean',
        'urgent' => 'boolean',
        'cost' => 'float',
        'price' => 'float',
        'deadline' => 'datetime',
        'hours' => 'float',
        'work_time' => 'float',
        'played_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    public function deal() : BelongsTo
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function workArea() : BelongsTo
    {
        return $this->belongsTo(WorkArea::class, 'work_area_id');
    }

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function play(): void
    {
        $this->status = TaskStatus::IN_PROGRESS;
        $this->work_status = WorkStatus::IN_PROGRESS;
        $this->played_at = Carbon::now();
        if (!isset($this->started_at)) {
            $this->started_at = Carbon::now();
        }
        $this->save();
    }

    public function pause(): void
    {
        $this->work_status = WorkStatus::PAUSED;
        $this->work_time += Carbon::now()->timestamp - $this->played_at->timestamp;
        $this->played_at = null;
        $this->save();
    }

    public function stop(): void
    {
        $this->work_status = WorkStatus::STOPPED;
        $this->work_time += Carbon::now()->timestamp - $this->started_at->timestamp;
        $this->completed_at = Carbon::now();
        $this->completed = true;
        $this->hours = (int) ceil($this->work_time / 3600);

        $this->save();
    }
}
