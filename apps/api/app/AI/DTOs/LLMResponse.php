<?php

declare(strict_types=1);

namespace App\AI\DTOs;

/**
 * Standardized response returned by any LLM Provider driver.
 */
class LLMResponse
{
    /**
     * @param LLMToolCall[] $toolCalls
     * @param array<string, mixed> $usage
     */
    public function __construct(
        public readonly ?string $content,
        public readonly array $toolCalls = [],
        public readonly array $usage = [],
        public readonly ?string $finishReason = null
    ) {}

    public function hasToolCalls(): bool
    {
        return !empty($this->toolCalls);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'content'       => $this->content,
            'tool_calls'    => array_map(fn (LLMToolCall $tc) => $tc->toArray(), $this->toolCalls),
            'usage'         => $this->usage,
            'finish_reason' => $this->finishReason,
        ];
    }
}
