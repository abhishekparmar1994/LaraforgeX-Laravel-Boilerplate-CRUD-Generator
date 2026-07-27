<?php

declare(strict_types=1);

namespace App\Domains\Setting\Listeners;

use App\Domains\Setting\Events\SettingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogSettingActivity implements ShouldQueue
{
    public function handle(SettingCreated $event): void
    {
        Log::info("Setting created with ID: " . $event->record->id);
    }
}