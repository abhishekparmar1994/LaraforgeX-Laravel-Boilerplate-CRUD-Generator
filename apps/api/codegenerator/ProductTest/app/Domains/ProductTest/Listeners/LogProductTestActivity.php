<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Listeners;

use App\Domains\ProductTest\Events\ProductTestCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogProductTestActivity implements ShouldQueue
{
    public function handle(ProductTestCreated $event): void
    {
        Log::info("ProductTest created with ID: " . $event->record->id);
    }
}