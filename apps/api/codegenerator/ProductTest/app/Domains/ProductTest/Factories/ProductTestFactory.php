<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Factories;

use App\Domains\ProductTest\Models\ProductTest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductTestFactory extends Factory
{
    protected $model = ProductTest::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 10, 500)
        ];
    }
}