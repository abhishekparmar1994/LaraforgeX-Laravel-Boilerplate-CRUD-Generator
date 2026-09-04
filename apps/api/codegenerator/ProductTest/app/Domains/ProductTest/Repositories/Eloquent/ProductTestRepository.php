<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Repositories\Eloquent;

use App\Domains\ProductTest\Models\ProductTest;
use App\Domains\ProductTest\Repositories\Contracts\ProductTestRepositoryInterface;
use App\Shared\Services\BaseRepository;

class ProductTestRepository extends BaseRepository implements ProductTestRepositoryInterface
{
    protected function model(): string
    {
        return ProductTest::class;
    }
}