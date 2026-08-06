<?php

use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/install', [\App\Http\Controllers\InstallController::class, 'index']);

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return "All caches cleared successfully";
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return "Storage link created successfully";
});

Route::get('/migrate-db', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "Database migrations executed successfully: <br><pre>" . Artisan::output() . "</pre>";
});

Route::get('/seed-db', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return "Database seeders executed successfully: <br><pre>" . Artisan::output() . "</pre>";
});
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
    Route::get('/backups', [\App\Http\Controllers\AdminController::class, 'backups']);
    Route::get('/health', [\App\Http\Controllers\AdminController::class, 'health']);
    Route::get('/webhooks', [\App\Http\Controllers\AdminController::class, 'webhooks']);
    Route::get('/docs', [\App\Http\Controllers\AdminController::class, 'docs']);
    Route::get('/activity-logs', [\App\Http\Controllers\AdminController::class, 'activityLogs']);
    Route::get('/database-manager', [\App\Http\Controllers\AdminController::class, 'databaseManager']);
    Route::get('/database-manager/create', [\App\Http\Controllers\AdminController::class, 'databaseManagerCreate']);
    Route::get('/database-manager/console', [\App\Http\Controllers\AdminController::class, 'databaseManagerConsole']);
    Route::get('/database-manager/manage/{table}', [\App\Http\Controllers\AdminController::class, 'databaseManagerManage']);
});
