<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\User;

class LogoutUserAction
{
    /**
     * Terminate the user session/tokens.
     */
    public function execute(User $user): bool
    {
        return (bool) $user->currentAccessToken()->delete();
    }
}
