<?php

declare(strict_types=1);

namespace App\Domains\Settings\Actions;

use App\Domains\Settings\Repositories\Contracts\SettingsRepositoryInterface;

class SaveSettingsAction
{
    public function __construct(
        protected SettingsRepositoryInterface $repository
    ) {}

    public function execute(string $key, mixed $value, string $group = 'general', bool $encrypt = false): void
    {
        $this->repository->set($key, $value, $group, $encrypt);
    }
}
