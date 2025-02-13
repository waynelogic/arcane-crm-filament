<?php namespace App\Models;

use App\Service\Database\Traits\Sortable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkArea extends Model
{
    use Sortable;
    public function managers() : BelongsToMany
    {
        return $this->belongsToMany(Manager::class, 'work_areas_managers', 'manager_id', 'work_area_id')
            ->withPivot('rating', 'comment', 'sort_order');
    }
}
