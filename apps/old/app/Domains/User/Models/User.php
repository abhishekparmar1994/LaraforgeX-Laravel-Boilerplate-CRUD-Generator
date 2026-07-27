<?php

declare(strict_types=1);

namespace App\Domains\User\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, HasUUID, \App\Shared\Traits\HasAuditTrail;

    /**
     * Enforce Spatie roles and permissions to resolve using the web guard
     */
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'status',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_confirmed_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Determine if 2FA is enabled.
     */
    public function isTwoFactorEnabled(): bool
    {
        return !empty($this->two_factor_secret) && !empty($this->two_factor_confirmed_at);
    }

    /**
     * Determine if the user is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Determine if the user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Determine if the user has the given permission.
     * Overrides Spatie's method to support role hierarchies.
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        // 1. Check if user directly has the permission
        try {
            if ($this->hasDirectPermission($permission)) {
                return true;
            }
        } catch (\Throwable) {
            // Handle edge case where permission doesn't exist
        }

        // 2. Fetch all user roles
        $roles = $this->roles;

        // 3. Check roles and recursive children
        foreach ($roles as $role) {
            try {
                if ($role->hasPermissionTo($permission)) {
                    return true;
                }
            } catch (\Throwable) {
                // Ignore missing permissions in role check
            }

            /** @var \App\Domains\User\Models\Role $role */
            if (method_exists($role, 'getAllChildren')) {
                $children = $role->getAllChildren();
                foreach ($children as $child) {
                    try {
                        if ($child->hasPermissionTo($permission)) {
                            return true;
                        }
                    } catch (\Throwable) {
                        // Ignore missing permission on child checks
                    }
                }
            }
        }

        return false;
    }
}