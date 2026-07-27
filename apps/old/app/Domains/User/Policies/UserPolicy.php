<?php

declare(strict_types=1);

namespace App\Domains\User\Policies;

use App\Domains\User\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('users.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($model->hasRole('administrator') && !$user->hasRole('administrator')) {
            return false;
        }
        return $user->hasPermissionTo('users.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($model->hasRole('administrator') && !$user->hasRole('administrator')) {
            return false;
        }
        return $user->hasPermissionTo('users.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($model->hasRole('administrator') && !$user->hasRole('administrator')) {
            return false;
        }
        return $user->hasPermissionTo('users.delete');
    }

    /**
     * Determine whether the user can suspend/state-change the model.
     */
    public function suspend(User $user, User $model): bool
    {
        if ($model->hasRole('administrator') && !$user->hasRole('administrator')) {
            return false;
        }
        return $user->hasPermissionTo('users.suspend');
    }
}