<?php

declare(strict_types=1);

namespace App\Agents\Crm;

use App\Domains\User\Models\User;

/**
 * Immutable security and contextual information constructed server-side for each Agent request.
 */
class AgentContext
{
    /**
     * @param string[] $roles
     * @param string[] $permissions
     */
    public function __construct(
        public readonly User $user,
        public readonly string $userId,
        public readonly string $userName,
        public readonly array $roles,
        public readonly array $permissions,
        public readonly ?string $currentRoute = null,
        public readonly bool $isAdministrator = false
    ) {}

    public static function fromUser(User $user, ?string $currentRoute = null): self
    {
        $roles = $user->getRoleNames()->toArray();
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $isAdministrator = in_array('administrator', $roles, true);

        return new self(
            user: $user,
            userId: (string) $user->id,
            userName: $user->name,
            roles: $roles,
            permissions: $permissions,
            currentRoute: $currentRoute,
            isAdministrator: $isAdministrator
        );
    }

    public function can(string $permission): bool
    {
        if ($this->isAdministrator) {
            return true;
        }

        return in_array($permission, $this->permissions, true);
    }
}
