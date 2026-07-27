<?php

declare(strict_types=1);

namespace App\Domains\Media;

use App\Domains\Media\Repositories\Contracts\MediaRepositoryInterface;
use App\Domains\Media\Repositories\Eloquent\MediaRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            MediaRepositoryInterface::class,
            MediaRepository::class
        );
    }

    public function boot(): void
    {
        // Load media routes
        Route::middleware('api')->group(__DIR__ . '/routes.php');

        // Dynamically discover migrations
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        }
    }
}