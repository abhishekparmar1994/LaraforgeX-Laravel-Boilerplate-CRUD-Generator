<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\DTOs\UpdateUserDTO;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function execute(string $userId, UpdateUserDTO $dto): User
    {
        /** @var User $user */
        $user = $this->repository->findOrFail($userId);

        $updateData = [
            'name' => $dto->name,
            'email' => $dto->email,
        ];

        if (!empty($dto->password)) {
            $updateData['password'] = Hash::make($dto->password);
        }

        if (!empty($dto->status)) {
            $updateData['status'] = $dto->status;
        }

        $this->repository->update($userId, $updateData);

        // Reload fresh user to apply updates to relations
        $user = $user->fresh();

        if ($dto->roles !== null) {
            $user->syncRoles($dto->roles);
        }

        return $user;
    }
}
