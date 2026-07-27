<?php

declare(strict_types=1);

namespace App\Domains\Settings;

use App\Domains\Settings\Repositories\Contracts\SettingsRepositoryInterface;
use App\Domains\Settings\Repositories\Eloquent\SettingsRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            SettingsRepositoryInterface::class,
            SettingsRepository::class
        );
    }

    public function boot(): void
    {
        // Register Settings routes
        Route::middleware('api')->group(__DIR__ . '/routes.php');

        // Dynamically load migrations if running in console
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        }
    }
}