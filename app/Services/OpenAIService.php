<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl;
    protected int $maxTokens;
    protected float $temperature;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? '';
        $this->model = config('services.openai.model', 'gpt-4o-mini');
        $this->baseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');
        $this->maxTokens = (int) config('services.openai.max_tokens', 2000);
        $this->temperature = (float) config('services.openai.temperature', 0.7);
    }

    /**
     * Check if API is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Send a chat completion request to OpenAI.
     */
    public function chat(string $systemPrompt, string $userPrompt, array $options = []): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'OpenAI API key is not configured.',
                'content' => null,
                'tokens_used' => 0,
            ];
        }

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        return $this->sendRequest($messages, $options);
    }

    /**
     * Send request to OpenAI API.
     */
    protected function sendRequest(array $messages, array $options = []): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($this->baseUrl . '/chat/completions', [
                'model' => $options['model'] ?? $this->model,
                'messages' => $messages,
                'max_tokens' => $options['max_tokens'] ?? $this->maxTokens,
                'temperature' => $options['temperature'] ?? $this->temperature,
            ]);

            if ($response->failed()) {
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'error' => 'API request failed: ' . $response->status(),
                    'content' => null,
                    'tokens_used' => 0,
                ];
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';
            $tokensUsed = $data['usage']['total_tokens'] ?? 0;

            return [
                'success' => true,
                'content' => $content,
                'tokens_used' => $tokensUsed,
                'error' => null,
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'content' => null,
                'tokens_used' => 0,
            ];
        }
    }
}
