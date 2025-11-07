<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Public APK Download Route (no authentication required)
Route::get('/download/apk/{appVersion}', [App\Http\Controllers\AppVersionController::class, 'publicDownload'])
    ->name('apk.download');

// Public Location Logo Route (no authentication required)
Route::get('/public/storage/location-logos/{filename}', function ($filename) {
    $path = public_path('storage/location-logos/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path);
})->where('filename', '.*');

// Admin Authentication Routes
Route::get('/admin/login', [App\Http\Controllers\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [App\Http\Controllers\AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Protected Routes
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // User Management Routes
    Route::resource('users', App\Http\Controllers\UserController::class);
    
    // Location Management Routes
    Route::resource('locations', App\Http\Controllers\LocationManagementController::class);
    
    // App Version Management Routes
    Route::resource('app-versions', App\Http\Controllers\AppVersionController::class);
    Route::get('app-versions/{appVersion}/download', [App\Http\Controllers\AppVersionController::class, 'download'])
        ->name('app-versions.download');
});
