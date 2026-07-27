<?php

declare(strict_types=1);

namespace App\Domains\AuditLog\Repositories\Eloquent;

use App\Domains\AuditLog\Models\AuditLog;
use App\Domains\AuditLog\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Shared\Services\BaseRepository;

class AuditLogRepository extends BaseRepository implements AuditLogRepositoryInterface
{
    protected function model(): string
    {
        return AuditLog::class;
    }
}