<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $validated = Validator::make($request->all(), [
            'email' => ['required','email','max:35',],
        ]);
        if($validated->fails()){
            return response($validated->errors(),422);
        }

        $user = User::query()->where('email', '=', $request['email'])->first();

        if ($user->hasVerifiedEmail()) {
            return $next($request);
        }

        return response([
            'message' => 'Email must be verified.'
        ], 401);

    }
}
