<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::controller(\App\Http\Controllers\TestController::class)->group(function () {
    Route::get('/test', 'index');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', \App\Http\Middleware\UserCompany::class])->group(function () {

    Route::resource('/users', Controllers\UserController::class);
    Route::resource('/events', Controllers\EventController::class);
    Route::resource('/projects', Controllers\ProjectController::class);
    Route::resource('/deals', Controllers\DealController::class);

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('/', 'edit')->name('profile.edit');
        Route::put('/', 'update')->name('profile.update');
        Route::delete('/', 'destroy')->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';


//if (class_exists(\Debugbar::class)) {
//    \Debugbar::disable();
//}
//Route::group([
//    'prefix' => 'api',
////    'middleware' => [ 'auth.basic' ]
//], function () {
//    Route::any('1c_exchange', Controllers\ExchangeController::class)->name('1c_exchange');
//});
