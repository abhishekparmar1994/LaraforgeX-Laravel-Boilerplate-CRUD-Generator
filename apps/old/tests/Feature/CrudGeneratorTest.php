<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CrudGeneratorTest extends TestCase
{
    public function test_connections_endpoint_returns_available_connections(): void
    {
        $response = $this->getJson('/api/v1/crud-generator/connections');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    public function test_databases_endpoint_returns_databases_list_with_table_counts(): void
    {
        $response = $this->getJson('/api/v1/crud-generator/databases');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    public function test_tables_endpoint_returns_tables_list(): void
    {
        $response = $this->getJson('/api/v1/crud-generator/tables');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
            ]);
    }

    public function test_schema_endpoint_inspects_users_table(): void
    {
        $response = $this->getJson('/api/v1/crud-generator/schema?table=users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'connection',
                    'table_name',
                    'module_name',
                    'model_name',
                    'columns',
                ],
            ]);
    }

    public function test_generate_endpoint_creates_files_in_codegenerator_folder(): void
    {
        $payload = [
            'module_name' => 'ProductTest',
            'model_name' => 'ProductTest',
            'table_name' => 'users',
            'columns' => [
                [
                    'name' => 'name',
                    'label' => 'Product Name',
                    'control_type' => 'text',
                    'validation_rules' => 'required|string|max:255',
                    'show_in_list' => true,
                    'show_in_create' => true,
                    'show_in_edit' => true,
                    'searchable' => true,
                    'sortable' => true,
                ],
                [
                    'name' => 'price',
                    'label' => 'Price',
                    'control_type' => 'currency',
                    'validation_rules' => 'required|numeric',
                    'show_in_list' => true,
                    'show_in_create' => true,
                    'show_in_edit' => true,
                    'searchable' => false,
                    'sortable' => true,
                ]
            ],
            'relationships' => [],
            'options' => [
                'include_seeder' => true,
                'include_factory' => true,
                'include_observer' => true,
                'include_notification' => false,
                'include_tests' => true,
            ]
        ];

        $response = $this->postJson('/api/v1/crud-generator/generate', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'output_directory',
                    'generated_files',
                    'download_url',
                ]
            ]);

        $outputDir = base_path('codegenerator/ProductTest');
        $this->assertTrue(File::isDirectory($outputDir));
        $this->assertTrue(File::exists("{$outputDir}/app/Domains/ProductTest/Models/ProductTest.php"));
    }
}
