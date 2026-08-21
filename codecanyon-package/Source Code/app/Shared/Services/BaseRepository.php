<?php

declare(strict_types=1);

namespace App\Shared\Services;

use App\Shared\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * The model instance.
     */
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    /**
     * Specify the Model class name.
     */
    abstract protected function model(): string;

    /**
     * Get all resource models.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * Find a model by ID.
     */
    public function find(string $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find a model by ID or throw exception.
     */
    public function findOrFail(string $id, array $columns = ['*']): Model
    {
        $result = $this->find($id, $columns);
        
        if (!$result) {
            throw new ModelNotFoundException("Model not found with ID {$id}");
        }

        return $result;
    }

    /**
     * Create a new model instance.
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * Update an existing model instance.
     */
    public function update(string $id, array $attributes): bool
    {
        $record = $this->findOrFail($id);
        return $record->update($attributes);
    }

    /**
     * Delete a model instance by ID.
     */
    public function delete(string $id): bool
    {
        $record = $this->findOrFail($id);
        return (bool) $record->delete();
    }
}
