<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories\Eloquent;

use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Shared\Services\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected function model(): string
    {
        return User::class;
    }

    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User|null */
        return $this->model->newQuery()->where('email', $email)->first();
    }

    /**
     * Find an active user by email.
     */
    public function findActiveByEmail(string $email): ?User
    {
        /** @var User|null */
        return $this->model->newQuery()
            ->where('email', $email)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Search and paginate users with filters.
     */
    public function searchAndPaginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Prevent non-administrators from seeing administrator user records
        $currentUser = auth()->user();
        if ($currentUser && !$currentUser->hasRole('administrator')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'administrator');
            });
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['role'])) {
            $query->role($filters['role']);
        }

        return $query->paginate($perPage);
    }
}