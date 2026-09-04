<?php

declare(strict_types=1);

namespace App\AI\Contracts;

use App\AI\DTOs\LLMResponse;

/**
 * Interface defining standard provider contract for LLM drivers.
 */
interface LLMProviderInterface
{
    /**
     * Send messages to the LLM and return standardized response with optional tool calls.
     *
     * @param array<int, array{role: string, content: string, name?: string, tool_call_id?: string}> $messages
     * @param array<int, array<string, mixed>> $tools
     * @param array<string, mixed> $options
     * @return LLMResponse
     */
    public function chat(array $messages, array $tools = [], array $options = []): LLMResponse;
}
