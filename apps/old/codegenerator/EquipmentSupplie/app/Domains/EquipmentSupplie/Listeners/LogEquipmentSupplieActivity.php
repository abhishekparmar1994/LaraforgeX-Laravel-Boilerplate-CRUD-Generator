<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Listeners;

use App\Domains\EquipmentSupplie\Events\EquipmentSupplieCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogEquipmentSupplieActivity implements ShouldQueue
{
    public function handle(EquipmentSupplieCreated $event): void
    {
        Log::info("EquipmentSupplie created with ID: " . $event->record->id);
    }
}