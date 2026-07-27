// Route declaration snippet for routes/web.php:
Route::prefix('admin')->group(function () {
    Route::get('/setting', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'index'])->name('admin.setting.index');
    Route::get('/setting/create', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'create'])->name('admin.setting.create');
    Route::post('/setting', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'store'])->name('admin.setting.store');
    Route::get('/setting/{id}', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'show'])->name('admin.setting.show');
    Route::get('/setting/{id}/edit', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'edit'])->name('admin.setting.edit');
    Route::put('/setting/{id}', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'update'])->name('admin.setting.update');
    Route::delete('/setting/{id}', [\App\Domains\Setting\Http\Controllers\SettingController::class, 'destroy'])->name('admin.setting.destroy');
});