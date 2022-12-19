<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(AuthLoginRequest $request): Response
    {
        $user = User::query()->where('email', '=', $request['email'])->first();

        if (!($user instanceof User) || !Hash::check($request['password'], $user->password)) {
            return response([
                'message'=>'The provided credentials are incorrect.'
            ],401);
        }

        return response([
            'token' => $user->createToken('jwt_token')->plainTextToken
        ], 200);
    }

    public function logout(Request $request): Response
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response([
            'message'=>'logout successfully'
        ],200);
    }
}
