<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('api.auth.register');
    
    Route::post('/login', 'login')->name('api.auth.login');

    Route::group(['prefix' => 'reset'], function () {
        Route::post('/otp', 'resetOtp')->name('api.auth.reset.otp');
        Route::post('/password', 'resetPassword')->name('api.auth.reset.password');  
    },);

    Route::middleware('auth:api')->group(function () {
        Route::post('/otp', 'otp')->name('api.auth.otp');
        Route::post('/verify', 'verify')->name('api.auth.verify');
    });

});
