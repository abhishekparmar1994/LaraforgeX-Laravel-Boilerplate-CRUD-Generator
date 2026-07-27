<?php

declare(strict_types=1);

namespace App\Domains\Setting\Factories;

use App\Domains\Setting\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->words(3, true),
            'value' => $this->faker->words(3, true),
            'group' => $this->faker->words(3, true),
            'is_encrypted' => $this->faker->boolean()
        ];
    }
}