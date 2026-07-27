<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Domains\User\Repositories\Eloquent\UserRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    public function boot(): void
    {
        // Dynamically load the user domain routes
        Route::middleware('api')->group(__DIR__ . '/routes.php');

        // Dynamically load domain migrations if running in CLI
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        }
    }
}