// Route declaration snippet for routes/web.php:
Route::prefix('admin')->group(function () {
    Route::get('/product_test', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'index'])->name('admin.product_test.index');
    Route::get('/product_test/create', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'create'])->name('admin.product_test.create');
    Route::post('/product_test', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'store'])->name('admin.product_test.store');
    Route::get('/product_test/{id}', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'show'])->name('admin.product_test.show');
    Route::get('/product_test/{id}/edit', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'edit'])->name('admin.product_test.edit');
    Route::put('/product_test/{id}', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'update'])->name('admin.product_test.update');
    Route::delete('/product_test/{id}', [\App\Domains\ProductTest\Http\Controllers\ProductTestController::class, 'destroy'])->name('admin.product_test.destroy');
});