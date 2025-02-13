<?php namespace App\Models;

use App\Service\Database\Traits\HasExternalId;
use App\Service\Database\Traits\Manageable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use MoveMoveIo\DaData\Facades\DaDataCompany;

class Company extends FileModel
{
    use HasExternalId, Manageable;

    protected $table = 'companies';
    protected $casts = [
        'is_customer' => 'boolean',
        'is_supplier' => 'boolean',
        'active' => 'boolean',
        'inn' => 'double',
        'kpp' => 'double',
    ];

    protected static function boot()
    {
        parent::boot();

        // TODO : Доделать создание пользователя

        self::created(function ($model) {
            if ($model->inn) {
                $inn = $model->inn;
                $dadata = DaDataCompany::id($inn, 1);
                if (empty($dadata['suggestions'])) {
                    return false;
                }

                $arCompany = $dadata['suggestions'][0];
                $model->legal_name = $arCompany['value'];
                $model->kpp = $model->kpp ?? $arCompany['data']['kpp'] ?? null;
                $model->address = $model->address ?? $arCompany['data']['address']['unrestricted_value'] ?? null;
                $model->save();

                $managers = $dadata['suggestions'][0]['data']['management'];
                if ($managers) {
                    foreach ($managers as $manager) {
                        $person = User::create([
                            'name' => $manager['name'],
                        ]);
                        $person->save();

                        $model->сontacts()->attach($person->id, [
                            'position' => $manager['post'],
                        ]);
                    }
                }
            }
        });
    }

    public function contacts() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id')
            ->withPivot(['position', 'role'])
            ->withTimestamps();
    }
}
