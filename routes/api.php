<?php

use App\Http\Controllers\Book\PilotBookController;
use App\Http\Middleware\TenantHeader;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

require_once "auth-api.php";

/**
 * Tenant routes.
 *
 * Each user is related to one Tenant. (User = Tenant)
 * After user authentication an X-Tenant header should be added with tenant-id that relate the request to right database.
 * Middleware order priority is defined in TenancyServiceProvider.php
 *
 */

Route::group(['middleware' => [
    'auth:sanctum',
    TenantHeader::class,
    InitializeTenancyByRequestData::class,
    ]], function () {

    Route::post('/book/add', [PilotBookController::class, 'add']);
});
