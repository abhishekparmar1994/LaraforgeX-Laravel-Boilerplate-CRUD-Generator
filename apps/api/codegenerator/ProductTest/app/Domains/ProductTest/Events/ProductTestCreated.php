<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Events;

use App\Domains\ProductTest\Models\ProductTest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductTestCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ProductTest $record
    ) {}
}