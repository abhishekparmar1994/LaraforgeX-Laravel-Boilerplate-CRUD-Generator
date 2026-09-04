<?php

declare(strict_types=1);

namespace App\Domains\ProductTest;

use App\Domains\ProductTest\Repositories\Contracts\ProductTestRepositoryInterface;
use App\Domains\ProductTest\Repositories\Eloquent\ProductTestRepository;
use Illuminate\Support\ServiceProvider;

class ProductTestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProductTestRepositoryInterface::class,
            ProductTestRepository::class
        );
    }

    public function boot(): void
    {
        // Auto-bootstrap domain configurations
    }
}