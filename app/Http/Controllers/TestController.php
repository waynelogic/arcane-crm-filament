<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;

class TestController extends Controller
{
    public function index() {
        $arUsers = User::all();
        foreach ($arUsers as $obUser) {
            $obUser->generateAvatar();
        }

//        $arCustomers = Company::all();
//
//        foreach ($arCustomers as $obCompany) {
//            $generatedAvatarUrl = 'https://ui-avatars.com/api/?background=random&name=' . $obCompany->name;
//
//            if ($obCompany->hasMedia('company_logos')) {
//                $obCompany->clearMediaCollection('company_logos');
//            }
//            $obCompany->addMediaFromUrl($generatedAvatarUrl)->toMediaCollection('company_logos');
//        }
    }
}
