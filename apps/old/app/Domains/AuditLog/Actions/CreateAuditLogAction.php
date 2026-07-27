<?php

declare(strict_types=1);

namespace App\Domains\AuditLog\Actions;

use App\Domains\AuditLog\DTOs\CreateAuditLogDTO;
use App\Domains\AuditLog\Models\AuditLog;
use App\Domains\AuditLog\Repositories\Contracts\AuditLogRepositoryInterface;

class CreateAuditLogAction
{
    public function __construct(
        protected AuditLogRepositoryInterface $repository
    ) {}

    public function execute(CreateAuditLogDTO $dto): AuditLog
    {
        /** @var AuditLog */
        return $this->repository->create($dto->toArray());
    }
}