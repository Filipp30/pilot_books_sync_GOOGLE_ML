<?php

use App\Http\Middleware\TenantHeader;
use App\Models\UlmBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

require_once "auth-api.php";

Route::group(['middleware' => [
    'auth:sanctum',
    TenantHeader::class,
    InitializeTenancyByRequestData::class,
    ]], function () {

    Route::get('/user', function (Request $request) {
        $ulmBook = UlmBook::all();

        return response([
            'book' => $ulmBook
        ], 200);
    });
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact filipp-tts@outlook.com'], 404);
});
