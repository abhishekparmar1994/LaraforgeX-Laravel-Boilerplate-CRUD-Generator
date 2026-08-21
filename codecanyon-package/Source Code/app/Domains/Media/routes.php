<?php

declare(strict_types=1);

use App\Domains\Media\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware('auth:sanctum')->group(function () {
    Route::get('media', [MediaController::class, 'index']);
    Route::post('media/folders', [MediaController::class, 'createFolder']);
    Route::post('media/presign', [MediaController::class, 'generatePresignedUrl']);
    Route::post('media/{id}/confirm', [MediaController::class, 'confirmUpload']);
    Route::post('media/local-upload/{id}', [MediaController::class, 'localUpload']);
    Route::delete('media/{id}', [MediaController::class, 'destroy']);
});
