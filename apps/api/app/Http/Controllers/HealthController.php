<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class HealthController extends Controller
{
    /**
     * Get system health and server diagnostic metrics.
     */
    public function metrics(): JsonResponse
    {
        $dbStatus = 'Online';
        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            $dbStatus = 'Offline: ' . $e->getMessage();
        }

        $freeSpace = disk_free_space(base_path());
        $totalSpace = disk_total_space(base_path());

        return response()->json([
            'success' => true,
            'data' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'memory_limit' => ini_get('memory_limit'),
                'memory_usage_human' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
                'disk_free_human' => round($freeSpace / 1024 / 1024 / 1024, 2) . ' GB',
                'disk_total_human' => round($totalSpace / 1024 / 1024 / 1024, 2) . ' GB',
                'database_status' => $dbStatus,
                'extensions' => [
                    'pdo' => extension_loaded('pdo'),
                    'openssl' => extension_loaded('openssl'),
                    'mbstring' => extension_loaded('mbstring'),
                    'bcmath' => extension_loaded('bcmath'),
                    'curl' => extension_loaded('curl'),
                ]
            ]
        ]);
    }

    /**
     * Test SMTP Email sending.
     */
    public function testMail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $recipient = $request->input('email');

        try {
            // Send raw test email
            Mail::raw("LaraforgeX System SMTP Mail Test Payload. Received cleanly at " . date('Y-m-d H:i:s'), function ($message) use ($recipient) {
                $message->to($recipient)->subject('LaraforgeX SMTP Test Mail');
            });

            return response()->json([
                'success' => true,
                'message' => "Test email dispatched successfully to '{$recipient}'!",
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP Mail test failed: ' . $e->getMessage(),
            ], 422);
        }
    }
}
