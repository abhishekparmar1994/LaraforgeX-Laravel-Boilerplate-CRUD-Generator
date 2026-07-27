<?php

declare(strict_types=1);

namespace App\Domains\CrudGenerator\Http\Controllers;

use App\Domains\CrudGenerator\Services\CrudCodeGenerator;
use App\Domains\CrudGenerator\Services\DatabaseSchemaReader;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use ZipArchive;

class CrudGeneratorController extends Controller
{
    public function __construct(
        protected DatabaseSchemaReader $schemaReader,
        protected CrudCodeGenerator $codeGenerator
    ) {
    }

    /**
     * Render the admin CRUD Generator wizard view.
     */
    public function index(): View
    {
        return view('admin.crud_generator.index');
    }

    /**
     * Get list of database connections.
     */
    public function connections(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->schemaReader->getConnections(),
        ]);
    }

    /**
     * Get list of database schemas for a connection.
     */
    public function databases(Request $request): JsonResponse
    {
        $connection = $request->input('connection');
        return response()->json([
            'status' => 'success',
            'data' => $this->schemaReader->getDatabases($connection),
        ]);
    }

    /**
     * Get list of tables for a connection and database.
     */
    public function tables(Request $request): JsonResponse
    {
        $connection = $request->input('connection');
        $database = $request->input('database');
        return response()->json([
            'status' => 'success',
            'data' => $this->schemaReader->getTables($connection, $database),
        ]);
    }

    /**
     * Read table schema and suggest form controls, validations, and relations.
     */
    public function schema(Request $request): JsonResponse
    {
        $table = (string) $request->input('table');
        $connection = $request->input('connection');
        $database = $request->input('database');

        if (empty($table)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Table name is required.',
            ], 422);
        }

        try {
            $schema = $this->schemaReader->inspectTable($table, $connection, $database);
            return response()->json([
                'status' => 'success',
                'data' => $schema,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Generate file preview contents.
     */
    public function preview(Request $request): JsonResponse
    {
        $payload = $request->all();
        try {
            $result = $this->codeGenerator->generate($payload);
            return response()->json([
                'status' => 'success',
                'data' => [
                    'generated_files' => $result['generated_files'],
                    'files_content' => $result['files_content'],
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Execute full code generation into `codegenerator/{ModuleName}` directory.
     */
    public function generate(Request $request): JsonResponse
    {
        $payload = $request->all();
        try {
            $result = $this->codeGenerator->generate($payload);
            $moduleName = $payload['module_name'] ?? 'CustomModule';

            return response()->json([
                'status' => 'success',
                'message' => "CRUD Module {$moduleName} generated successfully inside 'codegenerator/{$moduleName}'!",
                'data' => [
                    'output_directory' => $result['output_directory'],
                    'generated_files' => $result['generated_files'],
                    'download_url' => url("/api/v1/crud-generator/download/{$moduleName}"),
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Download generated folder as ZIP file.
     */
    public function download(string $module)
    {
        $module = basename($module);
        $folderPath = base_path("codegenerator/{$module}");

        if (!File::isDirectory($folderPath)) {
            return response()->json([
                'status' => 'error',
                'message' => "Generated folder for module '{$module}' does not exist.",
            ], 404);
        }

        $zipFileName = "{$module}_crud_module.zip";
        $zipPath = storage_path("app/{$zipFileName}");

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($folderPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($folderPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Publish generated CRUD module code directly into app/ directory structure.
     */
    public function publish(Request $request): JsonResponse
    {
        $module = basename((string) $request->input('module'));
        $folderPath = base_path("codegenerator/{$module}");

        if (!File::isDirectory($folderPath)) {
            return response()->json([
                'status' => 'error',
                'message' => "Generated folder for module '{$module}' does not exist.",
            ], 404);
        }

        File::copyDirectory($folderPath, base_path());

        return response()->json([
            'status' => 'success',
            'message' => "Module '{$module}' has been published live into the application codebase!",
        ]);
    }
}
