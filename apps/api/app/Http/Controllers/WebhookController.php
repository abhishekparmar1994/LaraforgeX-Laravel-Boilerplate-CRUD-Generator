<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    private string $storageFile;

    public function __construct()
    {
        $this->storageFile = storage_path('app/webhooks.json');
        if (!File::exists($this->storageFile)) {
            File::put($this->storageFile, json_encode([
                [
                    'id' => 'wh_101',
                    'name' => 'Slack Order Alerts',
                    'url' => 'https://hooks.slack.com/services/sample/webhook',
                    'event' => 'user.registered',
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                ]
            ], JSON_PRETTY_PRINT));
        }
    }

    /**
     * List all registered webhooks with optional server-side filters.
     * Supports: ?name= (partial match), ?event= (exact trigger event)
     *
     * @param  Request $request  Optional filter params: name, event
     * @return JsonResponse      Filtered webhook list
     */
    public function index(Request $request): JsonResponse
    {
        $webhooks = json_decode(File::get($this->storageFile), true) ?? [];

        // Filter by webhook name (partial match)
        if ($request->filled('name')) {
            $name = strtolower($request->input('name'));
            $webhooks = array_filter($webhooks, fn($w) =>
                str_contains(strtolower($w['name'] ?? ''), $name)
            );
        }

        // Filter by trigger event (exact match)
        if ($request->filled('event')) {
            $event = $request->input('event');
            $webhooks = array_filter($webhooks, fn($w) => ($w['event'] ?? '') === $event);
        }

        return response()->json([
            'success' => true,
            'data' => array_values($webhooks),
        ]);
    }

    /**
     * Create a new webhook.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string'],
            'url' => ['required', 'url'],
            'event' => ['required', 'string'],
        ]);

        $webhooks = json_decode(File::get($this->storageFile), true) ?? [];

        $newWebhook = [
            'id' => 'wh_' . Str::random(8),
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'event' => $request->input('event'),
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $webhooks[] = $newWebhook;
        File::put($this->storageFile, json_encode($webhooks, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Webhook created successfully!',
            'data' => $newWebhook,
        ]);
    }

    /**
     * Send a ping/test payload to a registered webhook URL.
     */
    public function test(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $url = $request->input('url');

        try {
            $response = Http::timeout(5)->post($url, [
                'event' => 'webhook.ping',
                'timestamp' => time(),
                'payload' => [
                    'message' => 'LaraforgeX Webhook Integration Test Payload',
                    'status' => 'online',
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => "Webhook ping dispatched to {$url}. Status Code: " . $response->status(),
                'http_status' => $response->status(),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Webhook ping failed: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Delete a webhook.
     */
    public function destroy(string $id): JsonResponse
    {
        $webhooks = json_decode(File::get($this->storageFile), true) ?? [];
        $webhooks = array_values(array_filter($webhooks, fn($w) => $w['id'] !== $id));

        File::put($this->storageFile, json_encode($webhooks, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Webhook deleted successfully.',
        ]);
    }
}
