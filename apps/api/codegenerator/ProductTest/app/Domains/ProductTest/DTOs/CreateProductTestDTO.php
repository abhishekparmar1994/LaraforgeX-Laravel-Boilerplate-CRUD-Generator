<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\DTOs;

use App\Shared\DTOs\BaseDTO;

class CreateProductTestDTO extends BaseDTO
{
    public function __construct(
        public mixed $name = null,
        public mixed $price = null
    ) {}
}