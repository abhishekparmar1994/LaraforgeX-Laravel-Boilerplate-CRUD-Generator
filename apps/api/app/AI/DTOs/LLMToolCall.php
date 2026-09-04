<?php

declare(strict_types=1);

namespace App\AI\DTOs;

/**
 * Encapsulates a tool/function call requested by the LLM.
 */
class LLMToolCall
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly array $arguments = [],
        public readonly ?string $thoughtSignature = null
    ) {}

    /**
     * @return array{id: string, name: string, arguments: array<string, mixed>, thought_signature: ?string}
     */
    public function toArray(): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'arguments'         => $this->arguments,
            'thought_signature' => $this->thoughtSignature,
        ];
    }
}
