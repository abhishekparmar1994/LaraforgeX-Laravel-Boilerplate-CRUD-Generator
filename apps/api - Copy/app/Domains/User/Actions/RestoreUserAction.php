<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\User;

class RestoreUserAction
{
    public function execute(string $userId): bool
    {
        /** @var User|null $user */
        $user = User::withTrashed()->find($userId);

        if ($user && $user->trashed()) {
            return $user->restore();
        }

        return false;
    }
}
