// Route declaration snippet for routes/api.php:
Route::prefix('v1')->group(function () {
    Route::apiResource('equipment_supplie', \App\Domains\EquipmentSupplie\Http\Controllers\EquipmentSupplieController::class);
    Route::post('equipment_supplie/bulk-delete', [\App\Domains\EquipmentSupplie\Http\Controllers\EquipmentSupplieController::class, 'bulkDestroy']);
    Route::post('equipment_supplie/export', [\App\Domains\EquipmentSupplie\Http\Controllers\EquipmentSupplieController::class, 'export']);
});