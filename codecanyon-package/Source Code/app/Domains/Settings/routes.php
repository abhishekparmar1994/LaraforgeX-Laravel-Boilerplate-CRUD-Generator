<?php

declare(strict_types=1);

use App\Domains\Settings\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {
    Route::get('settings', [SettingsController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('settings', [SettingsController::class, 'store']);
        Route::put('settings/{id}', [SettingsController::class, 'update']);
    });
});

