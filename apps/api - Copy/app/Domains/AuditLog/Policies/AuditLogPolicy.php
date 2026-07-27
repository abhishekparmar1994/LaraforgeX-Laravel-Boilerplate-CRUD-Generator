<?php

declare(strict_types=1);

namespace App\Domains\AuditLog\Policies;

use App\Domains\User\Models\User;
use App\Domains\AuditLog\Models\AuditLog;

class AuditLogPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, AuditLog $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, AuditLog $model): bool
    {
        return true;
    }

    public function delete(User $user, AuditLog $model): bool
    {
        return true;
    }
}