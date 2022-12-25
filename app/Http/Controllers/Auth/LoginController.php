<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LoginController extends Controller
{
    public function login(AuthLoginRequest $request): Response
    {
        $validated = $request->validated();

        $user = User::query()->where('email', '=', $validated['email'])->first();

        if (!Hash::check($validated['password'], $user->password)) {
            throw new NotFoundHttpException();
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
