<?php

declare(strict_types=1);

namespace App\Domains\User\DTOs;

use App\Shared\DTOs\BaseDTO;

class LoginUserDTO extends BaseDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $remember = false,
        public readonly ?string $ipAddress = null
    ) {}
}
