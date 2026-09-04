<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Actions;

use App\Domains\ProductTest\Repositories\Contracts\ProductTestRepositoryInterface;

class DeleteProductTestAction
{
    public function __construct(
        protected ProductTestRepositoryInterface $repository
    ) {}

    public function execute(string $id): bool
    {
        return $this->repository->delete($id);
    }
}