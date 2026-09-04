<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Actions;

use App\Domains\ProductTest\Models\ProductTest;

class ExportProductTestAction
{
    public function execute(string $format = 'csv'): array
    {
        $records = ProductTest::latest()->get();
        return $records->toArray();
    }
}