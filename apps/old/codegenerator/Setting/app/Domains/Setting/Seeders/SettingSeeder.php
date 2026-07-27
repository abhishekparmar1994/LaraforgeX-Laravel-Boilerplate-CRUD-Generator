<?php

declare(strict_types=1);

namespace App\Domains\Setting\Seeders;

use App\Domains\Setting\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::factory()->count(20)->create();
    }
}