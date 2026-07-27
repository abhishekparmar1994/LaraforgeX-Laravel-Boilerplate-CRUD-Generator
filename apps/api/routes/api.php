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

    // Outgoing Webhooks API routes
    Route::prefix('webhooks')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\WebhookController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\WebhookController::class, 'store']);
        Route::post('/test', [\App\Http\Controllers\WebhookController::class, 'test']);
        Route::delete('/{id}', [\App\Http\Controllers\WebhookController::class, 'destroy']);
    });

    // Database Backups API routes
    Route::prefix('backups')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\BackupController::class, 'index']);
        Route::post('/generate', [\App\Http\Controllers\BackupController::class, 'generate']);
        Route::get('/download/{filename}', [\App\Http\Controllers\BackupController::class, 'download']);
        Route::delete('/{filename}', [\App\Http\Controllers\BackupController::class, 'destroy']);
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
