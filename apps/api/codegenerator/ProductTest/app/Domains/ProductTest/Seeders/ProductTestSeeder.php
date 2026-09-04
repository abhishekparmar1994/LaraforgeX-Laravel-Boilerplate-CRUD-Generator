<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Seeders;

use App\Domains\ProductTest\Models\ProductTest;
use Illuminate\Database\Seeder;

class ProductTestSeeder extends Seeder
{
    public function run(): void
    {
        ProductTest::factory()->count(20)->create();
    }
}