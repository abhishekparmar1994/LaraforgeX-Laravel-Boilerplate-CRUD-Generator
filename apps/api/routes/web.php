<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/install', [\App\Http\Controllers\InstallController::class, 'index']);

Route::get('/landing', function () {
    return file_get_contents(public_path('codecanyon_landing.html'));
});

Route::prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index']);
    Route::get('/login', [\App\Http\Controllers\AdminController::class, 'login']);
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard']);
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users']);
    Route::get('/roles', [\App\Http\Controllers\AdminController::class, 'roles']);
    Route::get('/permissions', [\App\Http\Controllers\AdminController::class, 'permissions']);
    Route::get('/media', [\App\Http\Controllers\AdminController::class, 'media']);
    Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings']);
    Route::get('/profile', [\App\Http\Controllers\AdminController::class, 'profile']);
    Route::get('/forgot-password', [\App\Http\Controllers\AdminController::class, 'forgotPassword']);
    Route::get('/reset-password', [\App\Http\Controllers\AdminController::class, 'resetPassword']);
    Route::get('/crud-generator', [\App\Http\Controllers\AdminController::class, 'crudGenerator']);
});
