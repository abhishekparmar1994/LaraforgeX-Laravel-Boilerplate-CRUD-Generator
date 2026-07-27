<?php

declare(strict_types=1);

namespace App\Domains\User\DTOs;

use App\Shared\DTOs\BaseDTO;

class CreateUserDTO extends BaseDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $status = 'active',
        public readonly ?array $roles = null
    ) {}
}