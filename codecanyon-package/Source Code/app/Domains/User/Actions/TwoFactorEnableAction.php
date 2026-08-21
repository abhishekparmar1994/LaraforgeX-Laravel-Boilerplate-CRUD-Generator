<?php

declare(strict_types=1);

namespace App\Domains\User\Actions;

use App\Domains\User\Models\User;
use App\Domains\User\Repositories\Contracts\UserRepositoryInterface;
use App\Domains\User\Services\Google2FAService;

class TwoFactorEnableAction
{
    public function __construct(
        protected UserRepositoryInterface $repository,
        protected Google2FAService $totpService
    ) {}

    /**
     * Set up 2FA for the user.
     *
     * @return array{secret: string, qr_code_url: string}
     */
    public function execute(User $user): array
    {
        $secret = $this->totpService->generateSecretKey();

        $this->repository->update($user->id, [
            'two_factor_secret' => encrypt($secret),
            'two_factor_confirmed_at' => null, // Needs validation first
        ]);

        $qrCodeUrl = $this->totpService->getQRCodeUrl(
            config('app.name', 'LaraforgeX'),
            $user->email,
            $secret
        );

        return [
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
        ];
    }
}
