<?php

declare(strict_types=1);

namespace App\Domains\Media\Actions;

use App\Domains\Media\DTOs\CreateMediaDTO;
use App\Domains\Media\Models\Media;
use App\Domains\Media\Repositories\Contracts\MediaRepositoryInterface;

class CreateMediaAction
{
    public function __construct(
        protected MediaRepositoryInterface $repository
    ) {}

    public function execute(CreateMediaDTO $dto): Media
    {
        /** @var Media */
        return $this->repository->create($dto->toArray());
    }
}