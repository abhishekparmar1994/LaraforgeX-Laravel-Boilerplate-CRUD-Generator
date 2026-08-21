<?php

declare(strict_types=1);

namespace App\Domains\CrudGenerator;

use App\Domains\CrudGenerator\Services\CrudCodeGenerator;
use App\Domains\CrudGenerator\Services\DatabaseSchemaReader;
use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DatabaseSchemaReader::class);
        $this->app->singleton(CrudCodeGenerator::class);
    }

    public function boot(): void
    {
        // Auto-discovered by AppServiceProvider
    }
}
