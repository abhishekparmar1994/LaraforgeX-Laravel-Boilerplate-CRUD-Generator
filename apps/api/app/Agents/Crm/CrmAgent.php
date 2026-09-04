<?php

declare(strict_types=1);

namespace App\Agents\Crm;

use App\Actions\LogActivityAction;
use App\AI\Contracts\LLMProviderInterface;
use App\AI\DTOs\LLMToolCall;
use App\AI\LLMManager;
use App\Domains\User\Models\User;
use App\Models\AgentConversation;
use App\Models\AgentMessage;
use App\Models\AgentToolCall;
use Illuminate\Support\Str;
use Throwable;

/**
 * CrmAgent — Central Orchestrator for CRM natural language queries,
 * tool invocation, audit tracking, and conversation persistence.
 */
class CrmAgent
{
    private LLMProviderInterface $provider;
    private AgentToolRegistry $registry;
    private LogActivityAction $activityLogger;
    private int $maxIterations;

    public function __construct(
        ?LLMProviderInterface $provider = null,
        ?AgentToolRegistry $registry = null,
        ?LogActivityAction $activityLogger = null
    ) {
        $this->provider = $provider ?? LLMManager::create();
        $this->registry = $registry ?? new AgentToolRegistry();
        $this->activityLogger = $activityLogger ?? new LogActivityAction();
        $this->maxIterations = (int) config('ai.agent.max_tool_iterations', 5);
    }

    /**
     * Process a prompt from an authenticated user and return structured intelligence.
     *
     * @return array{conversation_id: string, message: string, tool_activity: array<int, string>, actions: array<int, mixed>}
     */
    public function ask(User $user, string $prompt, ?string $conversationId = null, ?string $currentRoute = null): array
    {
        $conversation = $this->resolveConversation($user, $conversationId, $currentRoute, $prompt);
        $context = AgentContext::fromUser($user, $currentRoute);

        $messages = $this->buildMessageHistory($conversation, $prompt);

        // Record User Message
        AgentMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $prompt,
        ]);

        $tools = $this->registry->getDefinitionsForContext($context);

        $toolActivity = [];
        $actions = [];
        $collectedToolResults = [];
        $finalContent = null;
        $iteration = 0;

        while ($iteration < $this->maxIterations) {
            $iteration++;
            try {
                $response = $this->provider->chat($messages, $tools);
            } catch (Throwable $e) {
                if (!empty($collectedToolResults)) {
                    $finalContent = $this->formatFallbackToolSummary($collectedToolResults);
                    break;
                }
                $finalContent = "I encountered an issue connecting to the AI service: " . $e->getMessage();
                break;
            }

            if (!$response->hasToolCalls()) {
                $finalContent = $response->content ?? "I couldn't process your request.";
                break;
            }

            $messages[] = [
                'role' => 'assistant',
                'content' => $response->content ?? '',
                'tool_calls' => $response->toolCalls,
            ];

            foreach ($response->toolCalls as $toolCall) {
                $execResult = $this->executeToolCall($toolCall, $conversation, $context);
                $toolActivity[] = $this->formatToolActivityStatus($toolCall->name);
                $collectedToolResults[$toolCall->name] = $execResult['result'];

                if (!empty($execResult['actions'])) {
                    $actions = array_merge($actions, $execResult['actions']);
                }

                $messages[] = [
                    'role' => 'tool',
                    'name' => $toolCall->name,
                    'tool_call_id' => $toolCall->id,
                    'content' => json_encode($execResult['result']),
                ];
            }
        }

        if ($finalContent === null) {
            $finalContent = "Operation completed with " . count($toolActivity) . " action(s).";
        }

        // Record Assistant Message
        AgentMessage::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $finalContent,
        ]);

        $conversation->touch();

        return [
            'conversation_id' => (string) $conversation->id,
            'message' => $finalContent,
            'tool_activity' => $toolActivity,
            'actions' => $actions,
        ];
    }

    private function resolveConversation(User $user, ?string $id, ?string $route, string $prompt): AgentConversation
    {
        if ($id) {
            $existing = AgentConversation::where('id', $id)->where('user_id', $user->id)->first();
            if ($existing) {
                return $existing;
            }
        }

        $title = Str::limit($prompt, 40, '...');
        return AgentConversation::create([
            'user_id' => $user->id,
            'title' => $title,
            'current_route' => $route,
        ]);
    }

    /**
     * @return array<int, array{role: string, content: string}>
     */
    private function buildMessageHistory(AgentConversation $conversation, string $currentPrompt): array
    {
        $systemPrompt = $this->getSystemPrompt();
        $formatted = [['role' => 'system', 'content' => $systemPrompt]];

        // Fetch past messages in chronological order
        $pastMessages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        $history = [];
        $lastRole = null;
        foreach ($pastMessages as $msg) {
            $role = $msg->role === 'assistant' ? 'assistant' : 'user';
            if ($role === $lastRole) {
                continue; // Enforce strict alternating turns
            }
            $content = (string) $msg->content;
            if ($role === 'assistant' && strlen($content) > 300) {
                $content = Str::limit($content, 250, '...');
            }
            $history[] = ['role' => $role, 'content' => $content];
            $lastRole = $role;
        }

        // Keep last 4 historical turns (2 user, 2 assistant)
        foreach (array_slice($history, -4) as $h) {
            $formatted[] = $h;
        }

        // Append current prompt as the active user turn
        $formatted[] = ['role' => 'user', 'content' => $currentPrompt];

        return $formatted;
    }

    private function getSystemPrompt(): string
    {
        return "You are the LaraforgeX AI CRM Agent, an intelligent assistant embedded in LaraforgeX CRM.\n\n"
            . "CRITICAL RULES:\n"
            . "1. STRICT RESPONSE ISOLATION: Answer ONLY the user's immediate question. NEVER re-append, re-output, or merge tables/data from previous answers into your new response!\n"
            . "2. INDEPENDENT QUERIES: Each user prompt is a fresh question. Do NOT carry over filters (like locality, tags, or lead status) from previous turns unless explicitly asked (e.g. 'of those', 'from the same area').\n"
            . "3. EXACT ENTITY MAPPING:\n"
            . "   - 'contacts' -> query `crm_contacts` table or `search_contacts` (e.g. 'total contacts' means SELECT COUNT(*) FROM crm_contacts, NEVER crm_leads!).\n"
            . "   - 'leads' -> query `crm_leads` table or `search_leads`.\n"
            . "   - 'clients' -> query `crm_clients` table or `search_clients`.\n"
            . "   - 'websites' -> query `crm_websites` table.\n"
            . "   - 'tasks' -> query `crm_tasks` table or `search_tasks`.\n"
            . "   - 'projects' -> query `crm_projects` table.\n"
            . "4. For instructional/general questions (e.g. 'how to add tasks', advice, code, general knowledge), answer directly and cleanly using your general intelligence without attaching CRM tables.\n"
            . "5. PRESENTATION: NEVER output raw JSON. Output clean markdown with bullet points or clean tables with each row strictly on a new line.";
    }

    /**
     * @return array{result: array<string, mixed>, actions: array<int, mixed>}
     */
    private function executeToolCall(LLMToolCall $toolCall, AgentConversation $conversation, AgentContext $context): array
    {
        $startTime = microtime(true);
        $status = 'success';
        $result = [];
        $actions = [];

        try {
            $result = $this->registry->execute($toolCall->name, $toolCall->arguments, $context);
            if (!empty($result['view_url'])) {
                $actions[] = ['type' => 'navigation', 'label' => 'View Lead Details', 'url' => $result['view_url']];
            }
            if (!empty($result['view_all'])) {
                $actions[] = ['type' => 'navigation', 'label' => 'View All in CRM', 'url' => $result['view_all']];
            }
        } catch (Throwable $e) {
            $status = 'failed';
            $result = ['error' => true, 'message' => $e->getMessage()];
        }

        $durationMs = (int) round((microtime(true) - $startTime) * 1000);

        // Record persistent tool call log
        AgentToolCall::create([
            'conversation_id' => $conversation->id,
            'user_id' => $context->userId,
            'tool_name' => $toolCall->name,
            'arguments' => $toolCall->arguments,
            'result' => $result,
            'status' => $status,
            'execution_time_ms' => $durationMs,
        ]);

        // Record CRM Unified Activity
        $this->activityLogger->execute(
            $conversation,
            'ai_tool_executed',
            "AI CRM Agent executed tool [{$toolCall->name}] in {$durationMs}ms ({$status}).",
            ['tool' => $toolCall->name, 'duration_ms' => $durationMs, 'status' => $status],
            $context->userId
        );

        return ['result' => $result, 'actions' => $actions];
    }

    private function formatToolActivityStatus(string $toolName): string
    {
        return match ($toolName) {
            'query_crm_database' => '✓ Executed CRM database query',
            'lead_statistics' => '✓ Analyzed lead metrics & conversion data',
            'search_leads' => '✓ Filtered CRM lead pipeline',
            'get_lead' => '✓ Inspected lead record details',
            'pipeline_statistics' => '✓ Computed pipeline stage analytics',
            'search_clients' => '✓ Queried CRM client directory',
            'top_clients' => '✓ Ranked top revenue clients',
            'payment_statistics' => '✓ Calculated financial payment metrics',
            'search_tasks' => '✓ Searched operational tasks',
            'search_followups' => '✓ Checked upcoming follow-up schedules',
            'project_statistics' => '✓ Summarized project delivery status',
            default => "✓ Executed {$toolName}",
        };
    }

    /**
     * @param array<string, mixed> $collected
     */
    private function formatFallbackToolSummary(array $collected): string
    {
        $summary = "### 📊 Live CRM Database Results\n\n";
        foreach ($collected as $toolName => $res) {
            $readableTitle = ucwords(str_replace('_', ' ', $toolName));
            if ($toolName === 'query_crm_database') {
                $count = (int) ($res['count'] ?? 0);
                $rows = $res['rows'] ?? [];
                $summary .= "**Found {$count} record(s) in CRM database:**\n\n";
                if (!empty($rows)) {
                    $cols = array_keys((array) $rows[0]);
                    $summary .= "| " . implode(' | ', $cols) . " |\n";
                    $summary .= "| " . implode(' | ', array_fill(0, count($cols), '---')) . " |\n";
                    foreach (array_slice($rows, 0, 15) as $row) {
                        $rowVals = array_map(fn($v) => is_scalar($v) ? (string) $v : '-', (array) $row);
                        $summary .= "| " . implode(' | ', $rowVals) . " |\n";
                    }
                    $summary .= "\n";
                }
            } elseif (is_array($res)) {
                $summary .= "**{$readableTitle}**:\n";
                foreach ($res as $k => $v) {
                    $label = ucwords(str_replace('_', ' ', (string) $k));
                    $val = is_array($v) ? implode(', ', array_map(fn($sk, $sv) => ucwords((string) $sk) . ": " . (is_scalar($sv) ? $sv : json_encode($sv)), array_keys($v), $v)) : (string) $v;
                    $summary .= "• **{$label}**: {$val}\n";
                }
                $summary .= "\n";
            }
        }
        return $summary;
    }
}
