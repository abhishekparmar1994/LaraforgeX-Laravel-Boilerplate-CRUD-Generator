<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Events;

use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EquipmentSupplieCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public EquipmentSupplie $record
    ) {}
}