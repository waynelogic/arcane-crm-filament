<?php namespace App\Models;

use App\Service\Database\Traits\HasExternalId;
use App\Service\Database\Traits\Manageable;
use Guava\Calendar\Contracts\Eventable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model implements Eventable
{
    use HasExternalId, Manageable;
    protected $casts = [
        'properties' => 'array',
        'start' => 'datetime',
        'end' => 'datetime',
        'allDay' => 'boolean'
    ];

    public function manager() : BelongsTo
    {
        return $this->belongsTo(Manager::class, 'manager_id');
    }

    public function contact() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toEvent(): array|\Guava\Calendar\ValueObjects\Event
    {
        return [
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'allDay' => $this->allDay,
        ];
    }
}
