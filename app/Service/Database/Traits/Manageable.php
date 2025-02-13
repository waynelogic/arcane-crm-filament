<?php

namespace App\Service\Database\Traits;

use App\Models\Manager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Manageable
{
    public function manager() : BelongsTo
    {
        return $this->belongsTo(Manager::class, 'manager_id');
    }

    public static function bootManageable()
    {
        static::creating(function ($model) {
            $model->manager_id ??= auth()->user()->id;
        });
    }
}
