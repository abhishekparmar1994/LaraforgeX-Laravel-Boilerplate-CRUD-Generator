<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Observers;

use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;

class EquipmentSupplieObserver
{
    public function created(EquipmentSupplie $record): void {}
    public function updated(EquipmentSupplie $record): void {}
    public function deleted(EquipmentSupplie $record): void {}
}