<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories\Contracts;

use App\Domains\User\Models\User;
use App\Shared\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find an active user by email.
     */
    public function findActiveByEmail(string $email): ?User;

    /**
     * Search and paginate users with filters.
     */
    public function searchAndPaginate(array $filters, int $perPage = 15): LengthAwarePaginator;
}