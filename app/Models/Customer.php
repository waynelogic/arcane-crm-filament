<?php namespace App\Models;

class Customer extends Company
{
    protected $attributes = [
        'is_customer' => true,
    ];

    protected static function boot() : void
    {
        parent::boot();

        static::addGlobalScope('customer', function ($builder) {
            $builder->where('is_customer', true);
        });
    }
}
