<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Domains\User\Services\Google2FAService;
use App\Shared\Exceptions\BusinessException;
use Illuminate\Support\Str;

class TwoFactorVerifyAction
{
    public function __construct(
        protected UserRepositoryInterface $repository,
        protected Google2FAService $totpService
    ) {}

    /**
     * Verify the 2FA code and activate 2FA for the user.
     *
     * @return array<int, string> The recovery codes.
     * @throws BusinessException
     */
    public function execute(User $user, string $code): array
    {
        if (empty($user->two_factor_secret)) {
            throw new BusinessException('Two factor authentication is not initialized.', 400);
        }

        $secret = decrypt($user->two_factor_secret);

        if (!$this->totpService->verifyCode($secret, $code)) {
            throw new BusinessException('Invalid verification code.', 422);
        }

        // Generate 8 backup recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = Str::random(10) . '-' . Str::random(10);
        }

        // Encrypt recovery codes for storage
        $encryptedCodes = encrypt(json_encode($recoveryCodes));

        $this->repository->update($user->id, [
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => $encryptedCodes,
        ]);

        return $recoveryCodes;
    }
}
