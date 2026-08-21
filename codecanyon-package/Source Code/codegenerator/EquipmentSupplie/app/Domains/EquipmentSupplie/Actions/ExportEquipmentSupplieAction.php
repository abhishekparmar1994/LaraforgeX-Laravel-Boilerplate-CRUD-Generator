<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Actions;

use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;

class ExportEquipmentSupplieAction
{
    public function execute(string $format = 'csv'): array
    {
        $records = EquipmentSupplie::latest()->get();
        return $records->toArray();
    }
}