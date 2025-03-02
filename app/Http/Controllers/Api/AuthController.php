<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    protected AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|max:255|min:6',
        ]);

        if ($validator->fails()) {
            return response(['message' => "Invalid Credentials"], 422);
        }

        $credentials = $request->only('email', 'password');
        $user = $this->authService->register($request);

        $token = auth('api')->attempt($credentials);


        return response([
            'message' => 'Registration succefull! Please verify account with OTP',
            'results' => [
                'user' => new UserResource($user),
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ],
        ], 201);
    }


    public function login(Request $request): Response
    {
        $user = $this->authService->login($request);
        if ($user === null) {
            return response(['message' => 'The credentials does not match our records'], 401);
        }

        $credentials = $request->only(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response(['message' => 'Unauthorized'], 401);
        }

        return response([
            'message' => $user->email_verified_at ? 'Login succefull' : 'Login succefull! Please verify account with OTP',
            'results' => [
                'user' => $user,
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ],
        ], 200);
    }


    public function refresh(): Response
    {
        return response(['message' =>  'Token has been refreshed', 'results' => ['token' => JWTAuth::refresh(), 'expires_in' => JWTAuth::factory()->getTTL() * 60],], 200);
    }
    public function otp(): Response
    {
        $user = auth('api')->user();

        $otp = $this->authService->otp($user);


        return response(['message' => 'Verification code sent', 'otp' => $otp], 200);
    }


    public function verify(Request $request): Response
    {
        $request->validate(['otp' => 'required|numeric']);

        $user = $this->authService->verify($request, auth('api')->user());

        return response(
            [
                'message' => 'Account verified',
                'results' => [
                    'user' => new UserResource($user),
                ]
            ],
            200,
        );
    }


    public function resetOtp(Request $request): Response
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = $this->authService->getUserByEmail($request->email);

        $this->authService->otp($user, 'password-reset');

        return response(['message' => 'Verification code sent'], 200);
    }


    public function resetPassword(Request $request): Response
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:6|max:255|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        $user = $this->authService->resetPassword($request);

        return response(
            [
                'message' => 'Password reset succefull',
                'results' => [
                    'user' => new UserResource($user),
                ]
            ],
            200,
        );
    }

    public function logout(): Response
    {
        auth('api')->logout();
        return response(['message' => 'Logout succefull'], 200);
    }
}
