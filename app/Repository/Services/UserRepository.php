<?php

namespace App\Repository\Services;

use App\Models\User;
use App\Repository\Models\UserRegistrationModel;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    static public function createUser(UserRegistrationModel $user): User
    {
        return User::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone_number' => $user->getPhoneNumber(),
            'password' => Hash::make($user->getPassword()),
        ]);
    }
}
