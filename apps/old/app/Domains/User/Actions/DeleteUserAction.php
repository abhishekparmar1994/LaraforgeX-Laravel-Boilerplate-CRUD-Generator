<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;

class DeleteUserAction
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function execute(string $userId): bool
    {
        return $this->repository->delete($userId);
    }
}
