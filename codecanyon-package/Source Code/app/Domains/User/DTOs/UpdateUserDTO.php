<?php

declare(strict_types=1);

namespace App\Domains\User\DTOs;

use App\Shared\DTOs\BaseDTO;

class UpdateUserDTO extends BaseDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly ?string $status = null,
        public readonly ?array $roles = null
    ) {}
}
