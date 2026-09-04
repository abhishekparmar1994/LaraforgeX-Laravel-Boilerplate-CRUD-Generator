<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Actions;

use App\Domains\ProductTest\DTOs\UpdateProductTestDTO;
use App\Domains\ProductTest\Models\ProductTest;
use App\Domains\ProductTest\Repositories\Contracts\ProductTestRepositoryInterface;

class UpdateProductTestAction
{
    public function __construct(
        protected ProductTestRepositoryInterface $repository
    ) {}

    public function execute(string $id, UpdateProductTestDTO $dto): ProductTest
    {
        $data = array_filter($dto->toArray(), fn($v) => $v !== null);
        $this->repository->update($id, $data);
        return $this->repository->findOrFail($id);
    }
}