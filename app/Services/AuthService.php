<?php

namespace App\Services;

use App\Models\OTP;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Mail\OtpMail;   

class AuthService
{
    public function register(object $request) : User 
    {
        $user = User::create([
            'uuid' => Str::uuid(), 
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        return $user;
    }


    public function login(object $request) : ?User 
    {
        $user = User::where('email', $request->email)->first();

        if ( $user  && Hash::check($request->password, $user->password)) {
            return $user;
        }   

        return null;
    }


    public function otp(object $user,string $type = 'verification') : OTP {

        $legalTries = 3;
        $time = Carbon::now()->subMinutes(30);
        $count = OTP::where('user_id', $user->id)->where('type', $type)->where('created_at', '>=', $time)->count();

        if($count >= $legalTries) {
            abort(422,'Too many requests, please try again later');
        }

        $otp = OTP::create([
            'code' => random_int(1000, 9999),
            'type' => $type,
            'active' => 1,
            'user_id' => $user->id
        ]);

        Mail::to($user->email)->send(new OtpMail($otp->code,$otp->type));  

        return $otp;    
    }


    public function verify(object $request,User $user) : ?User 
    {
        $user = JWTAuth::user();
        $otp = $user->otps()->where('type','verification')->where('code', $request->code)->where('active', 1)->first();

        if(!$otp) {
            abort(422,'Invalid Verification code');
        }
        $otp->active = 0;
        $otp->updated_at = Carbon::now();
        $otp->update();

        return tap($user)->update(['email_verified_at' => Carbon::now()]);
    }   


    public function getUserByEmail(string $email) : User {
        return User::where('email', $email)->first();
    }



    public function resetPassword(object $request) : User {
        $user = $this->getUserByEmail($request->email);

        $otp = $user->otps()->where('type','password-reset')->where('code', $request->otp)->where('active', 1)->first();

        if(!$otp) {
            abort(422,'Invalid Verification code');
        }

        $otp->active = 0;
        $otp->updated_at = Carbon::now();
        $otp->update();

        return tap($user)->update(['password' => $request->password, 'updated_at' => Carbon::now()]);
    }   
}
