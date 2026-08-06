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
    try {
        Artisan::call('migrate', ['--force' => true]);
        return "<h2 style='color:green'>✅ Database migrations executed successfully!</h2><pre>" . e(Artisan::output()) . "</pre>";
    } catch (\Throwable $e) {
        return "<h2 style='color:red'>❌ Migration Error:</h2><pre>" . e($e->getMessage()) . "\n\n" . e($e->getTraceAsString()) . "</pre>";
    }
});

Route::get('/seed-db', function () {
    try {
        Artisan::call('db:seed', ['--force' => true]);
        return "<h2 style='color:green'>✅ Database seeders executed successfully!</h2><pre>" . e(Artisan::output()) . "</pre>";
    } catch (\Throwable $e) {
        return "<h2 style='color:red'>❌ Seeder Error:</h2><pre>" . e($e->getMessage()) . "\n\n" . e($e->getTraceAsString()) . "</pre>";
    }
});

Route::get('/demo-reset', function () {
    $key = request('key') ?? request('DEMO_RESET_KEY');
    $secret = env('DEMO_RESET_KEY', 'laraforgex_reset_secret_2026');

    if ($key !== $secret) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized: Invalid demo reset key.'
        ], 403);
    }

    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        $migrateOutput = Artisan::output();

        Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = Artisan::output();

        return response()->json([
            'success' => true,
            'message' => '✅ Demo database successfully reset to clean default state!',
            'output' => $migrateOutput . "\n" . $seedOutput
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Reset error: ' . $e->getMessage()
        ], 500);
    }
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
