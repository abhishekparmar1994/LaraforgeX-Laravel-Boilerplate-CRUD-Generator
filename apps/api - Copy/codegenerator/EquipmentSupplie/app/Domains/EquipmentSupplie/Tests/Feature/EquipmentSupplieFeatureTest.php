<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Tests\Feature;

use Tests\TestCase;

class EquipmentSupplieFeatureTest extends TestCase
{
    public function test_index_returns_success(): void
    {
        $response = $this->getJson("/api/v1/" . strtolower("EquipmentSupplie"));
        $response->assertStatus(200);
    }
}