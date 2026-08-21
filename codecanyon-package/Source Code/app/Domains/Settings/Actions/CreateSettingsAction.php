<?php

declare(strict_types=1);

namespace App\Domains\Settings\Actions;

use App\Domains\Settings\DTOs\CreateSettingsDTO;
use App\Domains\Settings\Models\Settings;
use App\Domains\Settings\Repositories\Contracts\SettingsRepositoryInterface;

class CreateSettingsAction
{
    public function __construct(
        protected SettingsRepositoryInterface $repository
    ) {}

    public function execute(CreateSettingsDTO $dto): Settings
    {
        /** @var Settings */
        return $this->repository->create($dto->toArray());
    }
}