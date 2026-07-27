<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\DTOs\LoginUserDTO;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Shared\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginUserAction
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    /**
     * Authenticate user credentials and return user and auth token.
     *
     * @return array{user: User, token: string}
     * @throws BusinessException
     */
    public function execute(LoginUserDTO $dto): array
    {
        /** @var User|null $user */
        $user = $this->repository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new BusinessException('Invalid login credentials.', 401);
        }

        if ($user->isSuspended()) {
            throw new BusinessException('Your account has been suspended.', 403);
        }

        if (!$user->isActive()) {
            throw new BusinessException('Your account is currently inactive.', 403);
        }

        // Update login audit fields
        $this->repository->update($user->id, [
            'last_login_at' => now(),
            'last_login_ip' => $dto->ipAddress,
        ]);

        // Generate Sanctum api token
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user->fresh(),
            'token' => $token,
        ];
    }
}
