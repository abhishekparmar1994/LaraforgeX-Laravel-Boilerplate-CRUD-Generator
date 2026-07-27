<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Repositories\Eloquent;

use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;
use App\Domains\EquipmentSupplie\Repositories\Contracts\EquipmentSupplieRepositoryInterface;
use App\Shared\Services\BaseRepository;

class EquipmentSupplieRepository extends BaseRepository implements EquipmentSupplieRepositoryInterface
{
    protected function model(): string
    {
        return EquipmentSupplie::class;
    }
}