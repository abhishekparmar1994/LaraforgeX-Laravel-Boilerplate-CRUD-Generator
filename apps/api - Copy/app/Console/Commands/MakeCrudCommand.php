<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\CrudGenerator\Services\CrudCodeGenerator;
use App\Domains\CrudGenerator\Services\DatabaseSchemaReader;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud
                            {table? : The database table name}
                            {--connection= : The database connection name}
                            {--module= : Custom module name}
                            {--seeder : Include seeder class}
                            {--factory : Include factory class}
                            {--observer : Include observer class}
                            {--notification : Include notification class}
                            {--tests : Include unit & feature tests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a complete DDD Lite CRUD module in codegenerator/ directory based on database table schema';

    public function __construct(
        protected DatabaseSchemaReader $schemaReader,
        protected CrudCodeGenerator $codeGenerator
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection') ?: config('database.default', 'mysql');
        $tableName = $this->argument('table');

        if (empty($tableName)) {
            $tables = array_column($this->schemaReader->getTables($connection), 'name');
            if (empty($tables)) {
                $this->error("No tables found on connection '{$connection}'.");
                return self::FAILURE;
            }
            $tableName = $this->choice('Select database table for CRUD generation:', $tables);
        }

        $this->info("Inspecting schema for table '{$tableName}'...");

        try {
            $schema = $this->schemaReader->inspectTable($tableName, $connection);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $moduleName = $this->option('module') ?: $schema['module_name'];
        $modelName = Str::studly(Str::singular($tableName));

        $this->info("Module Name: {$moduleName}");
        $this->info("Model Name:  {$modelName}");
        $this->info("Columns:     " . count($schema['columns']));

        $options = [
            'include_seeder' => $this->option('seeder') || $this->confirm('Include Seeder?', true),
            'include_factory' => $this->option('factory') || $this->confirm('Include Factory?', true),
            'include_observer' => $this->option('observer') || $this->confirm('Include Observer?', true),
            'include_notification' => $this->option('notification') || $this->confirm('Include Notification?', false),
            'include_tests' => $this->option('tests') || $this->confirm('Include Unit/Feature Tests?', true),
        ];

        $payload = [
            'module_name' => $moduleName,
            'model_name' => $modelName,
            'table_name' => $tableName,
            'columns' => $schema['columns'],
            'relationships' => $schema['suggested_relations'],
            'options' => $options,
        ];

        $this->info("Generating CRUD files...");
        $result = $this->codeGenerator->generate($payload);

        $this->newLine();
        $this->info("SUCCESS! CRUD Module generated inside 'codegenerator/{$moduleName}'!");
        $this->line("Output Directory: " . $result['output_directory']);
        $this->newLine();

        $this->table(['#', 'Relative File Path'], array_map(function ($idx, $file) {
            return [$idx + 1, $file];
        }, array_keys($result['generated_files']), $result['generated_files']));

        return self::SUCCESS;
    }
}
