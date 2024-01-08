<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $token = auth()->attempt($request->validated());
        if ($token) {
            return $this->responseWithToken($token, auth()->user());
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401); 
        }
    }

    public function register(RegistrationRequest $request)
    {
        $user = User::create($request->validated());
        if ($user) {
            $token = auth()->login($user);
            return $this->responseWithToken($token, $user);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occure while tying to create user'
            ], 500); 
        }
    }

    public function responseWithToken($token, $user)
    {
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
            'type' => 'bearer'
        ]);
    }
}
