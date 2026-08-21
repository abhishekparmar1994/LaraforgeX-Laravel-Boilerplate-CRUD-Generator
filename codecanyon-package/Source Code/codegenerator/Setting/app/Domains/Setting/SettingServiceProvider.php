<?php

declare(strict_types=1);

namespace App\Domains\Setting;

use App\Domains\Setting\Repositories\Contracts\SettingRepositoryInterface;
use App\Domains\Setting\Repositories\Eloquent\SettingRepository;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            SettingRepositoryInterface::class,
            SettingRepository::class
        );
    }

    public function boot(): void
    {
        // Auto-bootstrap domain configurations
    }
}