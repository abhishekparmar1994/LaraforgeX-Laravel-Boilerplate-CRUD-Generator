<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\DTOs\CreateUserDTO;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function execute(CreateUserDTO $dto): User
    {
        $userData = [
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'status' => $dto->status,
        ];

        /** @var User $user */
        $user = $this->repository->create($userData);

        if (!empty($dto->roles)) {
            $user->assignRole($dto->roles);
        }

        return $user;
    }
}