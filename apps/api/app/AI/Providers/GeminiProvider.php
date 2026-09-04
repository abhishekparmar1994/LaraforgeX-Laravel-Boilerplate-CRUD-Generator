<?php

declare(strict_types=1);

namespace App\AI\Providers;

use App\AI\Contracts\LLMProviderInterface;
use App\AI\DTOs\LLMResponse;
use App\AI\DTOs\LLMToolCall;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Google Gemini Generative Language Provider (v1beta REST API).
 */
class GeminiProvider implements LLMProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model = 'gemini-3.1-flash-lite',
        private readonly string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta',
        private readonly float $temperature = 0.2,
        private readonly int $maxTokens = 2048,
        private readonly int $timeout = 30
    ) {}

    public function chat(array $messages, array $tools = [], array $options = []): LLMResponse
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('Gemini API key is not configured.');
        }

        $payload = $this->buildPayload($messages, $tools);

        // 1. Try primary configured model
        $primaryUrl = "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";
        $response = Http::withoutVerifying()->timeout($this->timeout)->post($primaryUrl, $payload);

        if ($response->successful()) {
            return $this->parseResponse($response->json());
        }

        // 2. If primary hits quota or demand error, attempt available fallback models
        $fallbacks = ['gemini-3.1-flash-lite', 'gemini-3.5-flash-lite', 'gemini-flash-lite-latest'];
        foreach ($fallbacks as $fallbackModel) {
            if ($fallbackModel === $this->model) {
                continue;
            }
            $fallbackUrl = "{$this->baseUrl}/models/{$fallbackModel}:generateContent?key={$this->apiKey}";
            $fallbackRes = Http::withoutVerifying()->timeout($this->timeout)->post($fallbackUrl, $payload);
            if ($fallbackRes->successful()) {
                return $this->parseResponse($fallbackRes->json());
            }
        }

        throw new RuntimeException('Gemini API error: ' . $response->body());
    }

    private function buildPayload(array $messages, array $tools): array
    {
        $contents = [];
        $systemInstruction = null;

        foreach ($messages as $msg) {
            $role = $msg['role'] ?? 'user';
            if ($role === 'system') {
                $systemInstruction = ['parts' => [['text' => $msg['content']]]];
                continue;
            }

            if ($role === 'assistant' && !empty($msg['tool_calls'])) {
                $parts = [];
                foreach ($msg['tool_calls'] as $tc) {
                    $callPart = [
                        'functionCall' => [
                            'name' => $tc->name,
                            'args' => $tc->arguments,
                            'id'   => $tc->id,
                        ]
                    ];
                    if ($tc->thoughtSignature) {
                        $callPart['thoughtSignature'] = $tc->thoughtSignature;
                    }
                    $parts[] = $callPart;
                }
                $contents[] = ['role' => 'model', 'parts' => $parts];
                continue;
            }

            if ($role === 'tool') {
                $resultData = json_decode($msg['content'] ?? '{}', true) ?? $msg['content'];
                $contents[] = [
                    'role' => 'user',
                    'parts' => [[
                        'functionResponse' => [
                            'name' => $msg['name'] ?? 'tool',
                            'response' => ['result' => $resultData]
                        ]
                    ]]
                ];
                continue;
            }

            $contents[] = [
                'role'  => $role === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => (string) ($msg['content'] ?? '')]]
            ];
        }

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $this->temperature,
                'maxOutputTokens' => $this->maxTokens,
            ],
        ];

        if ($systemInstruction) {
            $payload['systemInstruction'] = $systemInstruction;
        }

        if (!empty($tools)) {
            $payload['tools'] = [['functionDeclarations' => $this->formatToolsForGemini($tools)]];
        }

        return $payload;
    }

    private function formatToolsForGemini(array $tools): array
    {
        $formatted = [];
        foreach ($tools as $tool) {
            $formatted[] = [
                'name'        => $tool['name'],
                'description' => $tool['description'],
                'parameters'  => $tool['parameters'] ?? ['type' => 'OBJECT', 'properties' => []],
            ];
        }
        return $formatted;
    }

    private function parseResponse(array $json): LLMResponse
    {
        $candidate = $json['candidates'][0] ?? [];
        $parts = $candidate['content']['parts'] ?? [];

        $text = null;
        $toolCalls = [];

        foreach ($parts as $part) {
            if (isset($part['text'])) {
                $text .= $part['text'];
            }
            if (isset($part['functionCall'])) {
                $toolCalls[] = new LLMToolCall(
                    id: $part['functionCall']['id'] ?? ('call_' . uniqid()),
                    name: $part['functionCall']['name'] ?? '',
                    arguments: $part['functionCall']['args'] ?? [],
                    thoughtSignature: $part['thoughtSignature'] ?? null
                );
            }
        }

        $usage = [
            'prompt_tokens'     => $json['usageMetadata']['promptTokenCount'] ?? 0,
            'completion_tokens' => $json['usageMetadata']['candidatesTokenCount'] ?? 0,
        ];

        return new LLMResponse($text, $toolCalls, $usage, $candidate['finishReason'] ?? null);
    }
}
