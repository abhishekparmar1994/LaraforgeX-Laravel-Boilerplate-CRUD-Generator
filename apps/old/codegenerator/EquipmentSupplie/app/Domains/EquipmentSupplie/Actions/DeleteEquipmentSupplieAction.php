<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Actions;

use App\Domains\EquipmentSupplie\Repositories\Contracts\EquipmentSupplieRepositoryInterface;

class DeleteEquipmentSupplieAction
{
    public function __construct(
        protected EquipmentSupplieRepositoryInterface $repository
    ) {}

    public function execute(string $id): bool
    {
        return $this->repository->delete($id);
    }
}