// Route declaration snippet for routes/api.php:
Route::prefix('v1')->group(function () {
    Route::apiResource('product_test', \App\Domains\ProductTest\Http\Controllers\ProductTestController::class);
    Route::post('product_test/bulk-delete', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'bulkDestroy']);
    Route::post('product_test/export', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'export']);
});