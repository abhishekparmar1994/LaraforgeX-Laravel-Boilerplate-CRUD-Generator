<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | Supported: "gemini", "openai", "mock"
    | "mock" runs an intelligent local fallback driver without external API keys.
    |
    */
    'default' => env('AI_PROVIDER', 'gemini'),

    /*
    |--------------------------------------------------------------------------
    | Provider Configurations
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY', ''),
            'model' => env('GEMINI_MODEL', 'gemini-3.1-flash-lite'),
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
            'temperature' => (float) env('GEMINI_TEMPERATURE', env('AI_TEMPERATURE', 0.2)),
            'max_tokens' => (int) env('GEMINI_MAX_TOKENS', env('AI_MAX_TOKENS', 2048)),
            'timeout' => (int) env('GEMINI_TIMEOUT', env('AI_TIMEOUT', 30)),
        ],

        'openai' => [
            'api_key' => env('OPENAI_API_KEY', ''),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'temperature' => (float) env('OPENAI_TEMPERATURE', env('AI_TEMPERATURE', 0.2)),
            'max_tokens' => (int) env('OPENAI_MAX_TOKENS', env('AI_MAX_TOKENS', 2048)),
            'timeout' => (int) env('OPENAI_TIMEOUT', env('AI_TIMEOUT', 30)),
        ],

        'mock' => [
            'name' => 'LaraforgeX Deterministic Intelligent Provider',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Agent Orchestrator Settings
    |--------------------------------------------------------------------------
    */
    'agent' => [
        'max_tool_iterations' => 5,
        'rate_limit_per_minute' => 30,
    ],
];
