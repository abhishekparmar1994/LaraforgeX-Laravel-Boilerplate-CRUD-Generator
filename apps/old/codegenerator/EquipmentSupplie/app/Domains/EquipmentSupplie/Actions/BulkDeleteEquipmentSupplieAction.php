<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Actions;

use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;

class BulkDeleteEquipmentSupplieAction
{
    public function execute(array $ids): int
    {
        return EquipmentSupplie::whereIn('id', $ids)->delete();
    }
}