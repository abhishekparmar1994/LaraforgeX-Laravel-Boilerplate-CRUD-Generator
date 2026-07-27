<?php

declare(strict_types=1);

namespace App\Domains\AuditLog\Jobs;

use App\Domains\AuditLog\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WriteAuditLogJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $auditData
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AuditLog::create($this->auditData);
    }
}
