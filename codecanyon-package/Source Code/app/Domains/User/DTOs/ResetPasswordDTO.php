<?php

declare(strict_types=1);

namespace App\Domains\User\DTOs;

use App\Shared\DTOs\BaseDTO;

class ResetPasswordDTO extends BaseDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $token,
        public readonly string $password
    ) {}
}
