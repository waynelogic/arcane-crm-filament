<?php

namespace App\Service;

use App\Models\Company;

class CurrentCompany
{
    public static function get($onlyId = true): Company
    {
        $obSession = session();
        if ($obSession->has('current_company')) {
            $obCompany = Company::find($obSession->get('current_company'));
        } else {
            $obCompany = request()->user()->companies()->first();
            $obSession->put('current_company', $obCompany->id);
        }

        return $onlyId ? $obCompany->id : $obCompany;
    }
}
