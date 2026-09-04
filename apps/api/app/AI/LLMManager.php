<?php

declare(strict_types=1);

namespace App\AI;

use App\AI\Contracts\LLMProviderInterface;
use App\AI\Providers\GeminiProvider;
use App\AI\Providers\MockProvider;
use App\AI\Providers\OpenAIProvider;
use InvalidArgumentException;

/**
 * Factory and manager for creating LLM provider instances.
 */
class LLMManager
{
    /**
     * Resolve the configured or requested LLM provider driver.
     */
    public static function create(?string $driver = null): LLMProviderInterface
    {
        $driver = $driver ?? config('ai.default', 'mock');

        return match ($driver) {
            'gemini' => new GeminiProvider(
                apiKey: config('ai.providers.gemini.api_key', ''),
                model: config('ai.providers.gemini.model', 'gemini-1.5-pro'),
                baseUrl: config('ai.providers.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'),
                temperature: (float) config('ai.providers.gemini.temperature', 0.2),
                maxTokens: (int) config('ai.providers.gemini.max_tokens', 2048),
                timeout: (int) config('ai.providers.gemini.timeout', 30)
            ),
            'openai' => new OpenAIProvider(
                apiKey: config('ai.providers.openai.api_key', ''),
                model: config('ai.providers.openai.model', 'gpt-4o-mini'),
                baseUrl: config('ai.providers.openai.base_url', 'https://api.openai.com/v1'),
                temperature: (float) config('ai.providers.openai.temperature', 0.2),
                maxTokens: (int) config('ai.providers.openai.max_tokens', 2048),
                timeout: (int) config('ai.providers.openai.timeout', 30)
            ),
            'mock' => new MockProvider(),
            default => throw new InvalidArgumentException("Unsupported AI provider driver [{$driver}]."),
        };
    }
}
