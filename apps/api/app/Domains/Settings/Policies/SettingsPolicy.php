<?php

declare(strict_types=1);

namespace App\Domains\Settings\Policies;

use App\Domains\User\Models\User;
use App\Domains\Settings\Models\Settings;

class SettingsPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Settings $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Settings $model): bool
    {
        return true;
    }

    public function delete(User $user, Settings $model): bool
    {
        return true;
    }
}