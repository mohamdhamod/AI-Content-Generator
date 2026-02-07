<?php

namespace App\Services;

use InvalidArgumentException;

class AIServiceFactory
{
    /**
     * Create an AI service instance based on configuration.
     */
    public static function create(?string $provider = null): OpenAIService|GeminiService
    {
        $provider = $provider ?? config('services.ai_provider', 'openai');

        return match ($provider) {
            'openai' => new OpenAIService(),
            'gemini' => new GeminiService(),
            default => throw new InvalidArgumentException("Unsupported AI provider: {$provider}"),
        };
    }

    /**
     * Get the current AI provider name.
     */
    public static function getCurrentProvider(): string
    {
        return config('services.ai_provider', 'openai');
    }

    /**
     * Check if a provider is available (has API key configured).
     */
    public static function isProviderAvailable(string $provider): bool
    {
        return match ($provider) {
            'openai' => !empty(config('services.openai.api_key')),
            'gemini' => !empty(config('services.gemini.api_key')),
            default => false,
        };
    }
}
