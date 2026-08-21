<?php

declare(strict_types=1);

namespace App\Domains\Setting\Actions;

use App\Domains\Setting\DTOs\CreateSettingDTO;
use App\Domains\Setting\Models\Setting;
use App\Domains\Setting\Repositories\Contracts\SettingRepositoryInterface;

class CreateSettingAction
{
    public function __construct(
        protected SettingRepositoryInterface $repository
    ) {}

    public function execute(CreateSettingDTO $dto): Setting
    {
        /** @var Setting */
        return $this->repository->create(array_filter($dto->toArray(), fn($v) => $v !== null));
    }
}