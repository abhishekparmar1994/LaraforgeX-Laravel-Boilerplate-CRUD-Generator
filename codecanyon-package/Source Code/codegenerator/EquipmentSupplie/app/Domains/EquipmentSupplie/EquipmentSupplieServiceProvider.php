<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie;

use App\Domains\EquipmentSupplie\Repositories\Contracts\EquipmentSupplieRepositoryInterface;
use App\Domains\EquipmentSupplie\Repositories\Eloquent\EquipmentSupplieRepository;
use Illuminate\Support\ServiceProvider;

class EquipmentSupplieServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            EquipmentSupplieRepositoryInterface::class,
            EquipmentSupplieRepository::class
        );
    }

    public function boot(): void
    {
        // Auto-bootstrap domain configurations
    }
}