<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Actions;

use App\Domains\EquipmentSupplie\DTOs\UpdateEquipmentSupplieDTO;
use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;
use App\Domains\EquipmentSupplie\Repositories\Contracts\EquipmentSupplieRepositoryInterface;

class UpdateEquipmentSupplieAction
{
    public function __construct(
        protected EquipmentSupplieRepositoryInterface $repository
    ) {}

    public function execute(string $id, UpdateEquipmentSupplieDTO $dto): EquipmentSupplie
    {
        $data = array_filter($dto->toArray(), fn($v) => $v !== null);
        $this->repository->update($id, $data);
        return $this->repository->findOrFail($id);
    }
}