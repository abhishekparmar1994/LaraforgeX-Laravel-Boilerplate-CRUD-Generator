<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Actions;

use App\Domains\ProductTest\Models\ProductTest;

class BulkDeleteProductTestAction
{
    public function execute(array $ids): int
    {
        return ProductTest::whereIn('id', $ids)->delete();
    }
}