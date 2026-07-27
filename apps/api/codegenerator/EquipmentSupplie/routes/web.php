// Route declaration snippet for routes/web.php:
Route::prefix('admin')->group(function () {
    Route::get('/equipment_supplie', [\App\Domains\EquipmentSupplie\Http\Controllers\EquipmentSupplieController::class, 'index']);
});