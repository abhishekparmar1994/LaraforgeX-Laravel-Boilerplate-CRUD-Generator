<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Seeders;

use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;
use Illuminate\Database\Seeder;

class EquipmentSupplieSeeder extends Seeder
{
    public function run(): void
    {
        EquipmentSupplie::factory()->count(20)->create();
    }
}