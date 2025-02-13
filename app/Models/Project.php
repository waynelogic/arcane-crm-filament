<?php namespace App\Models;

use App\Service\Database\Traits\HasExternalId;
use App\Service\Database\Traits\Manageable;
use App\Service\Database\Traits\Sortable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use Manageable, HasExternalId, Sortable;

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function tasks() : HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
