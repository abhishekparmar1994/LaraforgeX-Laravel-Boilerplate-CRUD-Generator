<?php

declare(strict_types=1);

namespace App\Domains\Setting\Actions;

use App\Domains\Setting\DTOs\UpdateSettingDTO;
use App\Domains\Setting\Models\Setting;
use App\Domains\Setting\Repositories\Contracts\SettingRepositoryInterface;

class UpdateSettingAction
{
    public function __construct(
        protected SettingRepositoryInterface $repository
    ) {}

    public function execute(string $id, UpdateSettingDTO $dto): Setting
    {
        $data = array_filter($dto->toArray(), fn($v) => $v !== null);
        $this->repository->update($id, $data);
        return $this->repository->findOrFail($id);
    }
}