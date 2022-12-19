<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AuthEmailVerification
{
    public function handle(Request $request, Closure $next)
    {
        $user = User::findOrFail($request->id);

        if ($user->hasVerifiedEmail()){
            return response([
                'message' => 'Error: twice verify not possible!'
            ], 403);
        }

        if ($request->hash !== null) {
            $hash_is_valid = hash_equals($request->hash,sha1($user->getEmailForVerification()));
            if (!$hash_is_valid){
                return response([
                    'message' => 'Error: Hash is not valid'
                ], 401);
            }
        }

        return $next($request);
    }
}
