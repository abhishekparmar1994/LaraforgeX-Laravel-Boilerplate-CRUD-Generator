<?php

declare(strict_types=1);

namespace App\Domains\Setting\DTOs;

use App\Shared\DTOs\BaseDTO;

class UpdateSettingDTO extends BaseDTO
{
    public function __construct(
        public mixed $key = null,
        public mixed $value = null,
        public mixed $group = null,
        public mixed $is_encrypted = null
    ) {}
}