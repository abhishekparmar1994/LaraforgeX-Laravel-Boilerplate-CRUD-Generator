<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Shared\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateMagicLinkAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Generate a magic login link for a user email.
     *
     * @throws BusinessException
     */
    public function execute(string $email): string
    {
        /** @var User|null $user */
        $user = $this->userRepository->findActiveByEmail($email);

        if (!$user) {
            throw new BusinessException('No active user account found with this email.', 404);
        }

        $rawToken = Str::random(64);
        $hashedToken = hash('sha256', $rawToken);

        DB::table('magic_links')->insert([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'token' => $hashedToken,
            'expires_at' => now()->addMinutes(15),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Construct magic link redirecting to the API endpoint
        return config('app.url') . "/api/v1/auth/magic-login?token=" . $rawToken;
    }
}
