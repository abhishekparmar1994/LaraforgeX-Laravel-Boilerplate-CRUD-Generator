<?php

declare(strict_types=1);

namespace App\Domains\AuditLog;

use App\Domains\AuditLog\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Domains\AuditLog\Repositories\Eloquent\AuditLogRepository;
use Illuminate\Support\ServiceProvider;

class AuditLogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuditLogRepositoryInterface::class,
            AuditLogRepository::class
        );
    }

    public function boot(): void
    {
        // Load migrations, policies, routes if applicable
        if ($this->app->runningInConsole()) {
            // Load migrations dynamically from Domain folder
            $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        }
    }
}