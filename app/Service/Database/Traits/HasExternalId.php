<?php

namespace App\Service\Database\Traits;

use function Termwind\render;

trait HasExternalId
{
    public static function bootHasExternalId()
    {
        static::creating(function ($model) {
            $field = defined('self::EXTERNAL_ID') ? self::EXTERNAL_ID : 'external_id';
            $model->{$field} ??= \Str::uuid()->toString();
        });
    }

    public function scopeExternalId($query, $externalId)
    {
        return $query->where( defined('self::EXTERNAL_ID') ? self::EXTERNAL_ID : 'external_id', $externalId);
    }
}
