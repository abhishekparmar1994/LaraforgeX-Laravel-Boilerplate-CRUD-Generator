<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Shared\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;

class AuthenticateMagicLinkAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Authenticate token from magic link and return user and auth token.
     *
     * @return array{user: User, token: string}
     * @throws BusinessException
     */
    public function execute(string $rawToken, ?string $ipAddress = null): array
    {
        $hashedToken = hash('sha256', $rawToken);

        $link = DB::table('magic_links')
            ->where('token', $hashedToken)
            ->first();

        if (!$link) {
            throw new BusinessException('Invalid or expired login link.', 401);
        }

        if ($link->used_at !== null) {
            throw new BusinessException('This login link has already been used.', 401);
        }

        if (now()->greaterThan($link->expires_at)) {
            throw new BusinessException('This login link has expired.', 401);
        }

        // Mark as used
        DB::table('magic_links')
            ->where('id', $link->id)
            ->update([
                'used_at' => now(),
            ]);

        /** @var User|null $user */
        $user = $this->userRepository->find($link->user_id);

        if (!$user || !$user->isActive()) {
            throw new BusinessException('User account is suspended or inactive.', 403);
        }

        // Audit login
        $this->userRepository->update($user->id, [
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
        ]);

        $token = $user->createToken('magic-link-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
