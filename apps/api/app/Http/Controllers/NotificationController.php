<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NotificationController extends Controller
{
    private string $storageFile;

    public function __construct()
    {
        $this->storageFile = storage_path('app/notifications.json');
        if (!File::exists($this->storageFile)) {
            File::put($this->storageFile, json_encode([
                [
                    'id' => 'notif_101',
                    'title' => 'System Backup Completed',
                    'message' => 'Database SQL backup was generated successfully.',
                    'type' => 'success',
                    'icon' => 'fa-database text-emerald-500',
                    'read' => false,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-5 minutes')),
                    'time_ago' => '5 mins ago',
                ],
                [
                    'id' => 'notif_102',
                    'title' => 'New User Registration',
                    'message' => 'User admin@laraforgex.com authenticated via Sanctum.',
                    'type' => 'info',
                    'icon' => 'fa-user-check text-brand-500',
                    'read' => false,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-25 minutes')),
                    'time_ago' => '25 mins ago',
                ],
                [
                    'id' => 'notif_103',
                    'title' => 'Security Audit Alert',
                    'message' => 'reCAPTCHA v2 protection enabled for admin login.',
                    'type' => 'warning',
                    'icon' => 'fa-shield-halved text-amber-500',
                    'read' => false,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                    'time_ago' => '2 hours ago',
                ]
            ], JSON_PRETTY_PRINT));
        }
    }

    /**
     * Get system notifications.
     */
    public function index(): JsonResponse
    {
        $notifications = json_decode(File::get($this->storageFile), true) ?? [];
        $unreadCount = count(array_filter($notifications, fn($n) => !$n['read']));

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'data' => $notifications,
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markRead(): JsonResponse
    {
        $notifications = json_decode(File::get($this->storageFile), true) ?? [];
        foreach ($notifications as &$n) {
            $n['read'] = true;
        }

        File::put($this->storageFile, json_encode($notifications, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }
}
