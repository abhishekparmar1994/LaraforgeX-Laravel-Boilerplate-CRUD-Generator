<?php

declare(strict_types=1);

namespace App\AI\Providers;

use App\AI\Contracts\LLMProviderInterface;
use App\AI\DTOs\LLMResponse;
use App\AI\DTOs\LLMToolCall;

/**
 * Intelligent deterministic provider for local development, offline mode, and unit tests.
 */
class MockProvider implements LLMProviderInterface
{
    private MockResponseSynthesizer $synthesizer;

    public function __construct(?MockResponseSynthesizer $synthesizer = null)
    {
        $this->synthesizer = $synthesizer ?? new MockResponseSynthesizer();
    }

    public function chat(array $messages, array $tools = [], array $options = []): LLMResponse
    {
        $lastMessage = end($messages);
        $lastRole = $lastMessage['role'] ?? 'user';

        if ($lastRole === 'tool' || $this->hasToolResults($messages)) {
            return $this->generateSummaryFromToolResults($messages);
        }

        $userPrompt = strtolower((string) ($lastMessage['content'] ?? ''));

        // Check intents in prioritized order
        $toolCall = $this->detectIntent($userPrompt);
        if ($toolCall) {
            return new LLMResponse(content: null, toolCalls: [$toolCall], usage: ['prompt_tokens' => 50, 'completion_tokens' => 20]);
        }

        return new LLMResponse(
            content: "Hello! I am your **LaraforgeX AI CRM Agent**. You can ask me questions about your leads, top clients, sales pipeline, tasks, projects, invoices, or follow-ups.",
            toolCalls: [],
            usage: ['prompt_tokens' => 30, 'completion_tokens' => 40]
        );
    }

    private function detectIntent(string $p): ?LLMToolCall
    {
        if (str_contains($p, 'pipeline') || str_contains($p, 'win rate') || str_contains($p, 'deal size')) {
            return new LLMToolCall('call_' . uniqid(), 'pipeline_statistics', []);
        }

        if (str_contains($p, 'top client') || str_contains($p, 'biggest client') || str_contains($p, 'owe') || str_contains($p, 'highest paying')) {
            $by = str_contains($p, 'owe') || str_contains($p, 'pending') ? 'pending' : 'revenue';
            return new LLMToolCall('call_' . uniqid(), 'top_clients', ['by' => $by, 'limit' => 5]);
        }

        if (str_contains($p, 'task stat') || str_contains($p, 'task metric') || str_contains($p, 'how many task')) {
            return new LLMToolCall('call_' . uniqid(), 'task_statistics', []);
        }

        if (str_contains($p, 'task')) {
            $args = [];
            if (str_contains($p, 'overdue')) $args['is_overdue'] = true;
            if (str_contains($p, 'urgent')) $args['priority'] = 'urgent';
            return new LLMToolCall('call_' . uniqid(), 'search_tasks', $args);
        }

        if (str_contains($p, 'followup') || str_contains($p, 'follow up') || str_contains($p, 'call today') || str_contains($p, 'meeting')) {
            $tf = str_contains($p, 'today') ? 'today' : (str_contains($p, 'overdue') ? 'overdue' : 'upcoming');
            return new LLMToolCall('call_' . uniqid(), 'search_followups', ['timeframe' => $tf]);
        }

        if (str_contains($p, 'project stat') || str_contains($p, 'portfolio budget') || str_contains($p, 'portfolio')) {
            return new LLMToolCall('call_' . uniqid(), 'project_statistics', []);
        }

        if (str_contains($p, 'project')) {
            $args = [];
            if (str_contains($p, 'overdue')) $args['is_overdue'] = true;
            return new LLMToolCall('call_' . uniqid(), 'search_projects', $args);
        }

        if (str_contains($p, 'revenue') || str_contains($p, 'invoice') || str_contains($p, 'financial') || str_contains($p, 'collected') || str_contains($p, 'payment')) {
            return new LLMToolCall('call_' . uniqid(), 'payment_statistics', []);
        }

        if (str_contains($p, 'activity') || str_contains($p, 'audit') || str_contains($p, 'timeline')) {
            return new LLMToolCall('call_' . uniqid(), 'recent_activity', ['limit' => 10]);
        }

        return $this->detectLeadOrEntityIntent($p);
    }

    private function detectLeadOrEntityIntent(string $p): ?LLMToolCall
    {
        if (str_contains($p, 'client')) {
            return new LLMToolCall('call_' . uniqid(), 'search_clients', []);
        }
        if (str_contains($p, 'lead stat') || str_contains($p, 'how many lead') || str_contains($p, 'total lead')) {
            return new LLMToolCall('call_' . uniqid(), 'lead_statistics', []);
        }
        if (str_contains($p, 'about lead') || str_contains($p, 'inspect lead')) {
            $query = trim(str_replace(['tell me about lead', 'about lead', 'inspect lead', 'lead'], '', $p));
            return new LLMToolCall('call_' . uniqid(), 'get_lead', ['query' => $query]);
        }
        if (str_contains($p, 'lead')) {
            $filters = [];
            if (str_contains($p, 'qualified')) $filters['status'] = 'qualified';
            if (str_contains($p, 'new')) $filters['status'] = 'new';
            if (str_contains($p, '7 days')) $filters['uncontacted_days'] = 7;
            if (preg_match('/(?:from|in)\s+([a-zA-Z0-9_\-\s]+)/i', $p, $matches)) {
                $rawSearch = trim(str_replace(['area', 'city', 'locality', 'zone'], '', $matches[1]));
                if (!empty($rawSearch)) {
                    $filters['search'] = $rawSearch;
                }
            }
            return new LLMToolCall('call_' . uniqid(), 'search_leads', $filters);
        }
        return null;
    }

    private function hasToolResults(array $messages): bool
    {
        foreach ($messages as $msg) {
            if (($msg['role'] ?? '') === 'tool' || !empty($msg['tool_result'])) {
                return true;
            }
        }
        return false;
    }

    private function generateSummaryFromToolResults(array $messages): LLMResponse
    {
        $toolResult = null;
        $toolName = null;

        for ($i = count($messages) - 1; $i >= 0; $i--) {
            if (($messages[$i]['role'] ?? '') === 'tool') {
                $toolResult = $messages[$i]['content'] ?? null;
                $toolName = $messages[$i]['name'] ?? null;
                break;
            }
        }

        $data = is_string($toolResult) ? json_decode($toolResult, true) : $toolResult;
        $content = is_array($data) && $toolName
            ? $this->synthesizer->format($toolName, $data)
            : "Results retrieved:\n```json\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n```";

        return new LLMResponse($content, [], ['prompt_tokens' => 80, 'completion_tokens' => 60]);
    }
}
