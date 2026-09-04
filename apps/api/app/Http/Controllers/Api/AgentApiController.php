<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Agents\Crm\CrmAgent;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgentChatRequest;
use App\Models\AgentConversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * REST API Controller for AI CRM Agent operations.
 */
class AgentApiController extends Controller
{
    public function __construct(
        private readonly CrmAgent $agent
    ) {}

    /**
     * Send a prompt to the AI Agent and receive real-time CRM intelligence.
     */
    public function chat(AgentChatRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $result = $this->agent->ask(
                user: $user,
                prompt: (string) $request->input('message'),
                conversationId: $request->input('conversation_id'),
                currentRoute: $request->input('current_route')
            );

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'The AI Agent encountered an issue processing your request: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List all chat conversations belonging to the authenticated user.
     */
    public function conversations(Request $request): JsonResponse
    {
        $conversations = AgentConversation::where('user_id', $request->user()->id)
            ->latest('updated_at')
            ->limit(30)
            ->get(['id', 'title', 'status', 'created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'data'    => $conversations,
        ]);
    }

    /**
     * Retrieve full message history for a specific conversation.
     */
    public function conversation(string $id, Request $request): JsonResponse
    {
        $conversation = AgentConversation::with('messages')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $conversation,
        ]);
    }

    /**
     * Delete an existing conversation.
     */
    public function deleteConversation(string $id, Request $request): JsonResponse
    {
        $deleted = AgentConversation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json([
            'success' => (bool) $deleted,
            'message' => $deleted ? 'Conversation deleted.' : 'Conversation not found.',
        ]);
    }
}
