// Route declaration snippet for routes/api.php:
Route::prefix('v1')->group(function () {
    Route::apiResource('setting', \App\Domains\Setting\Http\Controllers\SettingController::class);
    Route::post('setting/bulk-delete', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'bulkDestroy']);
    Route::post('setting/export', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'export']);
});