<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Actions;

use App\Domains\ProductTest\DTOs\CreateProductTestDTO;
use App\Domains\ProductTest\Models\ProductTest;
use App\Domains\ProductTest\Repositories\Contracts\ProductTestRepositoryInterface;

class CreateProductTestAction
{
    public function __construct(
        protected ProductTestRepositoryInterface $repository
    ) {}

    public function execute(CreateProductTestDTO $dto): ProductTest
    {
        /** @var ProductTest */
        return $this->repository->create(array_filter($dto->toArray(), fn($v) => $v !== null));
    }
}