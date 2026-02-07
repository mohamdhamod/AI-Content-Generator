<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl;
    protected int $maxTokens;
    protected float $temperature;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? '';
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        $this->baseUrl = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta');
        $this->maxTokens = (int) config('services.gemini.max_tokens', 2000);
        $this->temperature = (float) config('services.gemini.temperature', 0.7);
    }

    /**
     * Check if API is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Send a chat completion request to Gemini.
     */
    public function chat(string $systemPrompt, string $userPrompt, array $options = []): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'Gemini API key is not configured.',
                'content' => null,
                'tokens_used' => 0,
            ];
        }

        return $this->sendRequest($systemPrompt, $userPrompt, $options);
    }

    /**
     * Send request to Gemini API.
     */
    protected function sendRequest(string $systemPrompt, string $userPrompt, array $options = []): array
    {
        try {
            $model = $options['model'] ?? $this->model;
            $url = "{$this->baseUrl}/models/{$model}:generateContent?key={$this->apiKey}";

            $payload = [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $userPrompt]
                        ]
                    ]
                ],
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $systemPrompt]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? $this->temperature,
                    'maxOutputTokens' => $options['max_tokens'] ?? $this->maxTokens,
                    'topP' => 0.95,
                    'topK' => 40,
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($url, $payload);

            if ($response->failed()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                $errorMessage = 'API request failed: ' . $response->status();
                $errorBody = $response->json();
                if (isset($errorBody['error']['message'])) {
                    $errorMessage = $errorBody['error']['message'];
                }

                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'content' => null,
                    'tokens_used' => 0,
                ];
            }

            $data = $response->json();
            
            // Extract content from Gemini response
            $content = '';
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $content = $data['candidates'][0]['content']['parts'][0]['text'];
            }

            // Get token usage
            $tokensUsed = 0;
            if (isset($data['usageMetadata'])) {
                $tokensUsed = ($data['usageMetadata']['promptTokenCount'] ?? 0) 
                            + ($data['usageMetadata']['candidatesTokenCount'] ?? 0);
            }

            return [
                'success' => true,
                'content' => $content,
                'tokens_used' => $tokensUsed,
                'error' => null,
            ];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception', [
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
