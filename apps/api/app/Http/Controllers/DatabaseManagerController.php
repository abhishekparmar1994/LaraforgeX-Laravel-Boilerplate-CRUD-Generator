<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Agents\DatabaseManagerAgent;
use App\Http\Requests\CreateTableRequest;
use App\Http\Requests\ManageIndexRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * DatabaseManagerController — API Controller for managing MySQL Database Tables,
 * Schema Inspection, Index Operations, and Record Browsing.
 */
class DatabaseManagerController extends Controller
{
    public function __construct(
        private readonly DatabaseManagerAgent $agent
    ) {}

    /**
     * List all database tables and metrics.
     */
    public function index(): JsonResponse
    {
        try {
            $summary = $this->agent->getTablesSummary();
            return response()->json([
                'success' => true,
                'data'    => $summary,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Inspect schema, indexes, and foreign keys for a single table.
     */
    public function show(string $table): JsonResponse
    {
        try {
            $details = $this->agent->getTableDetails($table);
            return response()->json([
                'success' => true,
                'data'    => $details,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Fetch paginated records from a specific table.
     */
    public function data(string $table, Request $request): JsonResponse
    {
        try {
            $page = (int) $request->query('page', 1);
            $perPage = (int) $request->query('per_page', 15);
            $search = $request->query('search');

            $data = $this->agent->getTableData($table, $page, $perPage, $search);

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new table via DDL.
     */
    public function store(CreateTableRequest $request): JsonResponse
    {
        try {
            $message = $this->agent->createTable($request->validated());
            return response()->json([
                'success' => true,
                'message' => $message,
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Add an index to an existing table.
     */
    public function addIndex(string $table, ManageIndexRequest $request): JsonResponse
    {
        try {
            $val = $request->validated();
            $message = $this->agent->addIndex($table, $val['index_name'], $val['index_type'], $val['columns']);
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Drop an index from an existing table.
     */
    public function dropIndex(string $table, string $indexName): JsonResponse
    {
        try {
            $message = $this->agent->dropIndex($table, $indexName);
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Truncate a table.
     */
    public function truncate(string $table): JsonResponse
    {
        try {
            $message = $this->agent->truncateTable($table);
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Drop a table completely.
     */
    public function destroy(string $table): JsonResponse
    {
        try {
            $message = $this->agent->dropTable($table);
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Perform bulk action (truncate / drop) on selected tables.
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => ['required', 'string', 'in:truncate,drop'],
            'tables' => ['required', 'array', 'min:1'],
            'tables.*' => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/'],
        ]);

        try {
            $action = $request->input('action');
            $tables = $request->input('tables');

            if ($action === 'truncate') {
                $msg = $this->agent->bulkTruncate($tables);
            } else {
                $msg = $this->agent->bulkDrop($tables);
            }

            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Drop selected columns from a table.
     */
    public function dropColumns(string $table, Request $request): JsonResponse
    {
        $request->validate([
            'columns' => ['required', 'array', 'min:1'],
            'columns.*' => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/'],
        ]);

        try {
            $msg = $this->agent->dropColumns($table, $request->input('columns'));
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Execute custom raw SQL query.
     */
    public function executeSql(\App\Http\Requests\ExecuteQueryRequest $request): JsonResponse
    {
        try {
            $res = $this->agent->executeQuery($request->input('sql'));
            return response()->json([
                'success' => true,
                'data'    => $res,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
