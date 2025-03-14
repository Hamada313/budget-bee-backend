<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('api.auth.register');

    Route::post('/login', 'login')->name('api.auth.login');

    Route::post('/refresh', 'refresh')->name('api.auth.refresh');

    Route::group(['prefix' => 'reset'], function () {
        Route::post('/otp', 'resetOtp')->name('api.auth.reset.otp');
        Route::post('/password', 'resetPassword')->name('api.auth.reset.password');
    },);

    Route::middleware('auth:api')->group(function () {
        Route::post('/otp', 'otp')->name('api.auth.otp');
        Route::post('/verify', 'verify')->name('api.auth.verify');
        Route::post('/logout', 'logout')->name('api.auth.logout');
        Route::get('/me', 'currentUser')->name('api.auth.me');
    });
});

Route::middleware('auth:api')->controller(AccountController::class)->group(function () {
    Route::post('/create-account', 'store')->name('api.account.store');
    Route::post('/create-default-account', 'storeDefault')->name('api.account.store-default');  
    Route::get('/accounts', 'index')->name('api.account.index');
    Route::get('/account/{uuid}', 'show')->name('api.account.show');
    Route::patch('/account/{uuid}/update', 'update')->name('api.account.update');
    Route::delete('/account/{uuid}/delete', 'delete')->name('api.account.delete');
    Route::post('/account/{uuid}/set-default', 'setDefault')->name('api.account.set-default');
    Route::get('/account-default', 'getDefault')->name('api.account.get-default');  
});
