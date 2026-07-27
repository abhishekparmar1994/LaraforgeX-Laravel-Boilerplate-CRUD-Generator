<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\Permission;
use App\Shared\Exceptions\BusinessException;

class CreatePermissionAction
{
    /**
     * Create an enterprise permission.
     *
     * @throws BusinessException
     */
    public function execute(string $name, ?string $group = null, ?string $description = null, string $guardName = 'api'): Permission
    {
        $exists = Permission::where('name', $name)->where('guard_name', $guardName)->exists();

        if ($exists) {
            throw new BusinessException("Permission with name '{$name}' already exists.", 422);
        }

        /** @var Permission */
        return Permission::create([
            'name' => $name,
            'guard_name' => $guardName,
            'group' => $group,
            'description' => $description,
        ]);
    }
}
