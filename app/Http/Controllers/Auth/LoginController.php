<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LoginController extends Controller
{
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->where('email', '=', $validated['email'])->first();

        if (!Hash::check($validated['password'], $user->password)) {
            throw new NotFoundHttpException();
        }

        return response()->json([
            'token' => $user->createToken('jwt_token')->plainTextToken
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message'=>'logout successfully'
        ]);
    }
}
