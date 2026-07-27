<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Shared\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordAction
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    /**
     * Verify token and update user's password.
     *
     * @throws BusinessException
     */
    public function execute(string $email, string $token, string $password): void
    {
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            throw new BusinessException('User with this email address was not found.', 404);
        }

        $row = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$row || !Hash::check($token, $row->token)) {
            throw new BusinessException('Invalid or expired password reset token.', 422);
        }

        if (now()->subMinutes(60)->gt($row->created_at)) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            throw new BusinessException('Password reset token has expired.', 422);
        }

        $this->repository->update($user->id, [
            'password' => Hash::make($password)
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }
}
