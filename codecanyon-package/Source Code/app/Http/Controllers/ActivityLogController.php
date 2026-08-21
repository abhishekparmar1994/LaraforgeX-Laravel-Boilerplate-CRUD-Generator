<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ActivityLogController extends Controller
{
    private string $storageFile;

    public function __construct()
    {
        $this->storageFile = storage_path('app/activity_logs.json');
        if (!File::exists($this->storageFile)) {
            File::put($this->storageFile, json_encode([
                [
                    'id' => 'act_101',
                    'user' => 'Administrator (admin@laraforgex.com)',
                    'action' => 'LOGIN',
                    'description' => 'User logged into Admin Panel',
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 'act_102',
                    'user' => 'Administrator (admin@laraforgex.com)',
                    'action' => 'CRUD_GENERATE',
                    'description' => 'Generated module Products via CRUD Generator',
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-15 minutes')),
                ]
            ], JSON_PRETTY_PRINT));
        }
    }

    /**
     * List system activity audit logs with optional server-side filters.
     * Supports: ?action= (partial match), ?from=YYYY-MM-DD, ?to=YYYY-MM-DD
     *
     * @param  Request $request  Optional filter params: action, from, to
     * @return JsonResponse      Filtered and sorted activity log entries
     */
    public function index(Request $request): JsonResponse
    {
        $logs = json_decode(File::get($this->storageFile), true) ?? [];

        // Filter by action type (partial match, case-insensitive)
        if ($request->filled('action')) {
            $action = strtolower($request->input('action'));
            $logs = array_filter($logs, fn($l) =>
                str_contains(strtolower($l['action'] ?? ''), $action)
            );
        }

        // Filter by date range
        if ($request->filled('from')) {
            $from = strtotime($request->input('from') . ' 00:00:00');
            $logs = array_filter($logs, fn($l) =>
                isset($l['created_at']) && strtotime($l['created_at']) >= $from
            );
        }

        if ($request->filled('to')) {
            $to = strtotime($request->input('to') . ' 23:59:59');
            $logs = array_filter($logs, fn($l) =>
                isset($l['created_at']) && strtotime($l['created_at']) <= $to
            );
        }

        // Sort latest first
        usort($logs, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));

        return response()->json([
            'success' => true,
            'data' => array_values($logs),
        ]);
    }

    /**
     * Clear all activity logs.
     */
    public function clear(): JsonResponse
    {
        File::put($this->storageFile, json_encode([], JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Activity audit logs cleared successfully.',
        ]);
    }
}
