<?php

declare(strict_types=1);

use App\Domains\CrudGenerator\Http\Controllers\CrudGeneratorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // Web Installer API routes
    Route::prefix('install')->group(function (): void {
        Route::get('/requirements', [\App\Http\Controllers\InstallController::class, 'checkRequirements']);
        Route::get('/permissions', [\App\Http\Controllers\InstallController::class, 'checkPermissions']);
        Route::post('/test-db', [\App\Http\Controllers\InstallController::class, 'testDatabase']);
        Route::post('/process', [\App\Http\Controllers\InstallController::class, 'install']);
    });

    // CRUD Generator API routes
    Route::prefix('crud-generator')->group(function (): void {
        Route::get('/connections', [CrudGeneratorController::class, 'connections']);
        Route::get('/databases', [CrudGeneratorController::class, 'databases']);
        Route::get('/tables', [CrudGeneratorController::class, 'tables']);
        Route::get('/schema', [CrudGeneratorController::class, 'schema']);
        Route::post('/preview', [CrudGeneratorController::class, 'preview']);
        Route::post('/generate', [CrudGeneratorController::class, 'generate']);
        Route::get('/download/{module}', [CrudGeneratorController::class, 'download']);
    });
});
