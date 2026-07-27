<?php

declare(strict_types=1);

namespace App\Domains\Setting\Actions;

use App\Domains\Setting\Repositories\Contracts\SettingRepositoryInterface;

class DeleteSettingAction
{
    public function __construct(
        protected SettingRepositoryInterface $repository
    ) {}

    public function execute(string $id): bool
    {
        return $this->repository->delete($id);
    }
}