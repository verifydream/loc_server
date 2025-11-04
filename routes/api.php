<?php

use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\VersionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Location Server API and App Version API
Route::middleware(['throttle:60,1', 'log.api', 'auth.apikey'])->group(function () {
    Route::post('/check-location', [LocationController::class, 'checkLocation']);
    Route::get('/check-location', [LocationController::class, 'checkLocation']);
    Route::get('/latest-version', [VersionController::class, 'getLatestVersion']);
});
