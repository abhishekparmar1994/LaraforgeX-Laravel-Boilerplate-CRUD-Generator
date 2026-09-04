<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Tests\Feature;

use Tests\TestCase;

class ProductTestFeatureTest extends TestCase
{
    public function test_index_returns_success(): void
    {
        $response = $this->getJson("/api/v1/" . strtolower("ProductTest"));
        $response->assertStatus(200);
    }
}