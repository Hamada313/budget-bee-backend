<?php

use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test',function(){
    Mail::to('hamadaramadan2004@gmail.com')->send(new OtpMail('1234'));
    return "Mail Sent";          
}); 