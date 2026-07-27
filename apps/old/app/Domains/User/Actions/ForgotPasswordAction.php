<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Shared\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForgotPasswordAction
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    /**
     * Generate password reset token.
     *
     * @throws BusinessException
     */
    public function execute(string $email): string
    {
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            throw new BusinessException('User with this email address was not found.', 404);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        return $token;
    }
}
