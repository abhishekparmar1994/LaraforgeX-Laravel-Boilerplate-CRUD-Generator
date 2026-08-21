<?php

declare(strict_types=1);

namespace App\Domains\Setting\Policies;

use App\Domains\User\Models\User;
use App\Domains\Setting\Models\Setting;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Setting $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Setting $model): bool
    {
        return true;
    }

    public function delete(User $user, Setting $model): bool
    {
        return true;
    }
}