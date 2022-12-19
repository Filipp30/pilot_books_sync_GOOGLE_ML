<?php

namespace App\Repository\Services;

use App\Models\User;
use App\Repository\Dto\UserRegistrationDto;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    static public function createUser(UserRegistrationDto $dto): User
    {
        return User::create([
            'name' => $dto->getName(),
            'email' => $dto->getEmail(),
            'phone_number' => $dto->getPhoneNumber(),
            'password' => Hash::make($dto->getPassword()),
        ]);
    }
}
