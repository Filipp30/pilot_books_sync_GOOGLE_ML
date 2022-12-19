<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegistrationRequest;
use App\Repository\Models\UserRegistrationModel;
use App\Repository\Services\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;

class RegistrationController extends Controller
{
    public function registration(AuthRegistrationRequest $request): Response
    {
        $validated = $request->validated();

        if ($validated) {
            $user = new UserRegistrationModel($validated['name'], $validated['email'], $validated['phone_number'], $validated['password']);
            $user = UserRepository::createUser($user);
            event(new Registered($user));
        }

        return response([
            'message' => 'Registration successfully.',
            'email' => 'Must verify your email before you can login!',
            'user_name' => $user->name,
            'user_email' => $user->email,
        ], 200);
    }
}
