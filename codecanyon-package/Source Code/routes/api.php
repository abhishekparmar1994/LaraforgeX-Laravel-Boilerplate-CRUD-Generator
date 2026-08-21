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

    // Notification Hub API routes
    Route::prefix('notifications')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index']);
        Route::post('/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead']);
    });

    // Activity Audit Logs API routes
    Route::prefix('activity-logs')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\ActivityLogController::class, 'index']);
        Route::delete('/', [\App\Http\Controllers\ActivityLogController::class, 'clear']);
    });

    // System Health & SMTP API routes
    Route::prefix('health')->group(function (): void {
        Route::get('/metrics', [\App\Http\Controllers\HealthController::class, 'metrics']);
        Route::post('/test-mail', [\App\Http\Controllers\HealthController::class, 'testMail']);
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
        Route::post('/publish', [CrudGeneratorController::class, 'publish']);
        Route::get('/download/{module}', [CrudGeneratorController::class, 'download']);
    });

    // Database Studio / Table Manager API routes
    Route::prefix('database-manager')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\DatabaseManagerController::class, 'index']);
        Route::post('/create', [\App\Http\Controllers\DatabaseManagerController::class, 'store']);
        Route::get('/{table}', [\App\Http\Controllers\DatabaseManagerController::class, 'show']);
        Route::get('/{table}/data', [\App\Http\Controllers\DatabaseManagerController::class, 'data']);
        Route::get('/{table}/export', [\App\Http\Controllers\DatabaseManagerController::class, 'exportData']);
        Route::post('/{table}/indexes', [\App\Http\Controllers\DatabaseManagerController::class, 'addIndex']);
        Route::delete('/{table}/indexes/{indexName}', [\App\Http\Controllers\DatabaseManagerController::class, 'dropIndex']);
        Route::post('/bulk-action', [\App\Http\Controllers\DatabaseManagerController::class, 'bulkAction']);
        Route::post('/{table}/drop-columns', [\App\Http\Controllers\DatabaseManagerController::class, 'dropColumns']);
        Route::put('/{table}/columns/{column}', [\App\Http\Controllers\DatabaseManagerController::class, 'modifyColumn']);
        Route::post('/execute-sql', [\App\Http\Controllers\DatabaseManagerController::class, 'executeSql']);
        Route::post('/{table}/truncate', [\App\Http\Controllers\DatabaseManagerController::class, 'truncate']);
        Route::delete('/{table}', [\App\Http\Controllers\DatabaseManagerController::class, 'destroy']);
    });
});
