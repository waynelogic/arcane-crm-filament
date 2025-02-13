<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\WorkStatus;
use App\Service\Database\Traits\HasExternalId;
use App\Service\Database\Traits\Manageable;
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
}
