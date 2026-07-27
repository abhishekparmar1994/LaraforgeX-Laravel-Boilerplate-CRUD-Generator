<?php

declare(strict_types=1);

use App\Domains\User\Controllers\AuthController;
use App\Domains\User\Controllers\UserController;
use App\Domains\User\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {
    // Guest Auth Routes
    Route::prefix('auth')->group(function () {
        Route::get('captcha-config', [AuthController::class, 'captchaConfig']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('magic-link', [AuthController::class, 'magicLink']);
        Route::get('magic-login', [AuthController::class, 'magicLoginVerify']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    // Authenticated Auth & User Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('2fa/enable', [AuthController::class, 'twoFactorEnable']);
            Route::post('2fa/verify', [AuthController::class, 'twoFactorVerify']);
            Route::post('2fa/disable', [AuthController::class, 'twoFactorDisable']);
            Route::put('change-password', [AuthController::class, 'changePassword']);
            Route::get('me', [AuthController::class, 'me']);
        });

        // User CRUD & Lifecycle State Routes
        Route::post('users/{user}/suspend', [UserController::class, 'suspend']);
        Route::post('users/{user}/activate', [UserController::class, 'activate']);
        Route::post('users/{user}/deactivate', [UserController::class, 'deactivate']);
        Route::post('users/{user}/restore', [UserController::class, 'restore']);

        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', \App\Domains\User\Controllers\RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
    });
});
