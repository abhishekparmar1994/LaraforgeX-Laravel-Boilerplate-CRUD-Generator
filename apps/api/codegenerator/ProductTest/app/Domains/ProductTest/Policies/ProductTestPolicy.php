<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Policies;

use App\Domains\User\Models\User;
use App\Domains\ProductTest\Models\ProductTest;

class ProductTestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ProductTest $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ProductTest $model): bool
    {
        return true;
    }

    public function delete(User $user, ProductTest $model): bool
    {
        return true;
    }
}