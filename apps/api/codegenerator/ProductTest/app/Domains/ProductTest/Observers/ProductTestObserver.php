<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Observers;

use App\Domains\ProductTest\Models\ProductTest;

class ProductTestObserver
{
    public function created(ProductTest $record): void {}
    public function updated(ProductTest $record): void {}
    public function deleted(ProductTest $record): void {}
}