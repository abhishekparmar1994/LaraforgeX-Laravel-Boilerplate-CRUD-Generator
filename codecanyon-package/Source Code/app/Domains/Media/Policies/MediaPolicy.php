<?php

declare(strict_types=1);

namespace App\Domains\Media\Policies;

use App\Domains\User\Models\User;
use App\Domains\Media\Models\Media;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Media $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Media $model): bool
    {
        return true;
    }

    public function delete(User $user, Media $model): bool
    {
        return true;
    }
}