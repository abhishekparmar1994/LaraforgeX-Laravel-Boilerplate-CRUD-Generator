<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Factories;

use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentSupplieFactory extends Factory
{
    protected $model = EquipmentSupplie::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'image' => $this->faker->words(3, true)
        ];
    }
}