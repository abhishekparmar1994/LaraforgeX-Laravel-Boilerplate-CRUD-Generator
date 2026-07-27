<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\Role;
use App\Shared\Exceptions\BusinessException;

class CreateRoleAction
{
    /**
     * Create an enterprise role.
     *
     * @throws BusinessException
     */
    public function execute(string $name, ?string $description = null, ?string $parentId = null, string $guardName = 'api'): Role
    {
        $exists = Role::where('name', $name)->where('guard_name', $guardName)->exists();

        if ($exists) {
            throw new BusinessException("Role with name '{$name}' already exists.", 422);
        }

        if ($parentId !== null) {
            $parentExists = Role::where('id', $parentId)->exists();
            if (!$parentExists) {
                throw new BusinessException("Parent role not found.", 404);
            }
        }

        /** @var Role */
        return Role::create([
            'name' => $name,
            'guard_name' => $guardName,
            'description' => $description,
            'parent_id' => $parentId,
        ]);
    }
}
