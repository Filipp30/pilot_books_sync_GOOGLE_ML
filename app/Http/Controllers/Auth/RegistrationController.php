<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegistrationRequest;
use App\Repository\Dto\UserRegistrationDto;
use App\Repository\Services\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegistrationController extends Controller
{
    public function registration(AuthRegistrationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $dto = UserRegistrationDto::fromArray($validated);
        $user = UserRepository::createUser($dto);
        event(new Registered($user));

        return response()->json([
            'message' => 'Registration successfully. Must verify your email before you can login!',
        ]);
    }
}
