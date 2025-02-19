<?php

namespace App\Service\Api;

use App\Models\User;
use MoveMoveIo\DaData\Facades\DaDataCompany;

class DaData
{
    public static function fillCompany($model, $createManager = false)
    {
        if (empty($model->inn)) {
            return false;
        }

        $DaData = DaDataCompany::id($model->inn, 1);
        if (empty($DaData['suggestions'])) {
            return false;
        }

        $company = $DaData['suggestions'][0]['data'] ?? [];
        $model->legal_name = $DaData['suggestions'][0]['value'];
        $model->kpp = $model->kpp ?? $company['kpp'] ?? null;
        $model->address = $model->address ?? $company['address']['unrestricted_value'] ?? null;
        $model->save();

        if ($createManager && isset($company['management'])) {
            $manager = User::create(['name' => $company['management']['name']]);
            $model->contacts()->attach($manager->id, [
                'position' => $company['management']['post'] ?? 'Генеральный директор',
            ]);
        }

        return $model;
    }
}
