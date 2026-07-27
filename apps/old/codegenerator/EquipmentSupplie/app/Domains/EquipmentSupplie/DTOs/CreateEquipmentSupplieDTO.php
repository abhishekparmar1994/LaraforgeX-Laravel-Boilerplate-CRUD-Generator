<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\DTOs;

use App\Shared\DTOs\BaseDTO;

class CreateEquipmentSupplieDTO extends BaseDTO
{
    public function __construct(
        public mixed $title = null,
        public mixed $image = null
    ) {}
}