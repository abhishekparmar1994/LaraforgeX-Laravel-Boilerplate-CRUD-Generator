<?php

declare(strict_types=1);

namespace App\Shared\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get all resource models.
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Find a model by ID.
     */
    public function find(string $id, array $columns = ['*']): ?Model;

    /**
     * Find a model by ID or throw exception.
     */
    public function findOrFail(string $id, array $columns = ['*']): Model;

    /**
     * Create a new model instance.
     */
    public function create(array $attributes): Model;

    /**
     * Update an existing model instance.
     */
    public function update(string $id, array $attributes): bool;

    /**
     * Delete a model instance by ID.
     */
    public function delete(string $id): bool;
}
