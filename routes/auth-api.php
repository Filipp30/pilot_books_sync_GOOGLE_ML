<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\UserEmailIsVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegistrationController;

### AUTH: Authentication and Authorization ###
    Route::post('/auth/registration',[RegistrationController::class,'registration']);
    Route::post('/auth/login',[LoginController::class,'login'])->middleware([UserEmailIsVerified::class]);
    Route::get('/auth/logout',[LoginController::class,'logout'])->middleware('auth:sanctum');
    Route::get('/auth/user', function (Request $request){
        return response([
            'user' => $request->user(),
            'tenant' => $request->user()?->tenant()->getResults(),
        ], 200);
    })->middleware('auth:sanctum');

### Email Verification ###
    Route::get('/email/verify/{id}/{hash}',[EmailVerificationController::class,'emailVerify'])
        ->middleware(['auth.email.verification'])->name('verification.verify');

    Route::get('/email/verification-notification/{id}',[EmailVerificationController::class,'sendEmailVerificationNotification'])
        ->middleware(['auth.email.verification'])->name('verification.send');


