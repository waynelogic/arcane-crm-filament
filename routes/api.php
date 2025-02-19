<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
//    'prefix' => 'api',
//    'middleware' => [ 'auth.basic' ]
], function () {
    Route::any('1c_exchange', Controllers\ExchangeController::class)->name('1c_exchange');
});
