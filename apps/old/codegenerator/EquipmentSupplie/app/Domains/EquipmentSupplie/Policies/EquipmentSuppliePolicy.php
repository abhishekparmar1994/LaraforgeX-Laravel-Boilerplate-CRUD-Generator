<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Policies;

use App\Domains\User\Models\User;
use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;

class EquipmentSuppliePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, EquipmentSupplie $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, EquipmentSupplie $model): bool
    {
        return true;
    }

    public function delete(User $user, EquipmentSupplie $model): bool
    {
        return true;
    }
}