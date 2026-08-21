<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Actions;

use App\Domains\EquipmentSupplie\DTOs\CreateEquipmentSupplieDTO;
use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;
use App\Domains\EquipmentSupplie\Repositories\Contracts\EquipmentSupplieRepositoryInterface;

class CreateEquipmentSupplieAction
{
    public function __construct(
        protected EquipmentSupplieRepositoryInterface $repository
    ) {}

    public function execute(CreateEquipmentSupplieDTO $dto): EquipmentSupplie
    {
        /** @var EquipmentSupplie */
        return $this->repository->create(array_filter($dto->toArray(), fn($v) => $v !== null));
    }
}