<?php

declare(strict_types=1);

namespace App\AI\Providers;

use App\AI\Contracts\LLMProviderInterface;
use App\AI\DTOs\LLMResponse;
use App\AI\DTOs\LLMToolCall;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * OpenAI Chat Completions Provider Driver.
 */
class OpenAIProvider implements LLMProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model = 'gpt-4o-mini',
        private readonly string $baseUrl = 'https://api.openai.com/v1',
        private readonly float $temperature = 0.2,
        private readonly int $maxTokens = 2048,
        private readonly int $timeout = 30
    ) {}

    public function chat(array $messages, array $tools = [], array $options = []): LLMResponse
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('OpenAI API key is not configured.');
        }

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $this->temperature,
            'max_tokens' => $this->maxTokens,
        ];

        if (!empty($tools)) {
            $payload['tools'] = array_map(fn ($tool) => [
                'type' => 'function',
                'function' => [
                    'name' => $tool['name'],
                    'description' => $tool['description'],
                    'parameters' => $tool['parameters'] ?? ['type' => 'object', 'properties' => []],
                ],
            ], $tools);
        }

        $response = Http::withToken($this->apiKey)
            ->timeout($this->timeout)
            ->post("{$this->baseUrl}/chat/completions", $payload);

        if (!$response->successful()) {
            throw new RuntimeException('OpenAI request failed: ' . $response->body());
        }

        return $this->parseResponse($response->json());
    }

    private function parseResponse(array $json): LLMResponse
    {
        $choice = $json['choices'][0]['message'] ?? [];
        $content = $choice['content'] ?? null;
        $toolCalls = [];

        if (!empty($choice['tool_calls'])) {
            foreach ($choice['tool_calls'] as $tc) {
                $args = json_decode($tc['function']['arguments'] ?? '{}', true) ?: [];
                $toolCalls[] = new LLMToolCall(
                    id: $tc['id'] ?? 'openai_' . uniqid(),
                    name: $tc['function']['name'] ?? '',
                    arguments: $args
                );
            }
        }

        $usage = $json['usage'] ?? [];
        return new LLMResponse($content, $toolCalls, $usage, $json['choices'][0]['finish_reason'] ?? null);
    }
}
