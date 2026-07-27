<?php

declare(strict_types=1);

namespace App\Domains\Setting\Tests\Feature;

use Tests\TestCase;

class SettingFeatureTest extends TestCase
{
    public function test_index_returns_success(): void
    {
        $response = $this->getJson("/api/v1/" . strtolower("Setting"));
        $response->assertStatus(200);
    }
}