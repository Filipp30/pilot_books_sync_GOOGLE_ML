<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserIsVerified;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailVerificationController extends Controller
{
    public function emailVerify(Request $request): Response
    {
        $user = User::findOrFail($request->id);
        $email_is_verified = $user->markEmailAsVerified();

        UserIsVerified::dispatch($user->id);

        return response([
            'message' => 'Email is verified successfully.',
            'verified' => $email_is_verified,
        ], 200);
    }

    public function sendEmailVerificationNotification(Request $request): Response
    {
        $user = User::findOrFail($request->id);
        $user->sendEmailVerificationNotification();

        return response([
            'message' => 'Verification email send.',
        ], 200);
    }
}
