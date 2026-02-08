<?php

namespace App\Services;

use App\Models\ContentType;
use App\Models\GeneratedContent;
use App\Models\Specialty;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContentGeneratorService
{
    protected OpenAIService|GeminiService $aiService;
    protected MedicalPromptService $promptService;
    protected GuardrailService $guardrail;

    public function __construct(
        MedicalPromptService $promptService,
        GuardrailService $guardrail
    ) {
        $this->aiService = AIServiceFactory::create();
        $this->promptService = $promptService;
        $this->guardrail = $guardrail;
    }

    /**
     * Generate content with full pipeline.
     * 
     * @param User $user The user generating content
     * @param int $contentTypeId The content type ID
     * @param int|null $specialtyId The specialty ID (optional)
     * @param int|null $topicId The topic ID (optional) - for enhanced context
     * @param array $inputData User input data
     * @param string $locale Language locale
     */
    public function generate(
        User $user,
        int $contentTypeId,
        ?int $specialtyId,
        ?int $topicId,
        array $inputData,
        string $locale = 'en'
    ): array {
        // Check if AI service is configured
        if (!$this->aiService->isConfigured()) {
            return [
                'success' => false,
                'error' => 'AI service is not configured. Please contact administrator.',
                'content' => null,
            ];
        }

        // Check user credits
        $contentType = ContentType::find($contentTypeId);
        if (!$contentType) {
            return [
                'success' => false,
                'error' => 'Invalid content type.',
                'content' => null,
            ];
        }

        $creditsNeeded = $contentType->credits_cost ?? 1;
        $availableCredits = $user->monthly_credits - $user->used_credits;

        if ($availableCredits < $creditsNeeded) {
            return [
                'success' => false,
                'error' => 'Insufficient credits. You need ' . $creditsNeeded . ' credits but have ' . $availableCredits . ' available.',
                'content' => null,
            ];
        }

        // Validate input
        $inputValidation = $this->guardrail->validateInput($inputData);
        if (!$inputValidation['valid']) {
            return [
                'success' => false,
                'error' => 'Your request contains content that cannot be processed: ' . implode(', ', $inputValidation['issues']),
                'content' => null,
            ];
        }

        // Load Topic for enhanced prompt building
        $topic = $topicId ? Topic::with('specialty')->find($topicId) : null;
        
        // Build prompt using MedicalPromptService if topic exists, otherwise use legacy
        if ($topic) {
            // NEW: Use MedicalPromptService with database-driven topics
            $contentTypeModel = ContentType::find($contentTypeId);
            
            $prompts = $this->promptService->buildPrompt(
                $topic,
                $contentTypeModel->key,
                $locale,
                $inputData
            );
            
            $systemPrompt = $prompts['system_prompt'];
            $userPrompt = $prompts['user_prompt'];
        } else {
            // FALLBACK: Use simple prompt for non-topic based content
            $contentTypeModel = ContentType::find($contentTypeId);
            $minWords = $contentTypeModel->min_word_count ?? 800;
            $maxWords = $contentTypeModel->max_word_count ?? 1200;
            $targetWords = $inputData['word_count'] ?? $maxWords;
            
            $systemPrompt = "You are a professional medical content writer. Create educational, patient-friendly content. Never diagnose, prescribe, or give medical advice. Always write comprehensive, detailed content.";
            
            $userPrompt = "Create {$contentTypeModel->name} content about: " . ($inputData['topic'] ?? 'general health');
            $userPrompt .= "\nLanguage: " . ($inputData['language'] ?? $locale);
            
            if (!empty($inputData['tone'])) {
                $userPrompt .= "\nTone: " . $inputData['tone'];
            }
            
            if (!empty($inputData['additional_instructions'])) {
                $userPrompt .= "\nAdditional instructions: " . $inputData['additional_instructions'];
            }
            
            // Add word count requirements
            $userPrompt .= "\n\n### IMPORTANT LENGTH REQUIREMENT:";
            $userPrompt .= "\n- Target word count: approximately {$targetWords} words";
            $userPrompt .= "\n- Minimum: {$minWords} words";
            $userPrompt .= "\n- Maximum: {$maxWords} words";
            $userPrompt .= "\n- Write comprehensive, detailed, well-structured content";
            $userPrompt .= "\n- DO NOT write short or summarized content";
            $userPrompt .= "\n- Include all necessary sections and details";
        }
        
        // Log prompt for debugging
        Log::info('Content Generation Request', [
            'content_type' => $contentTypeModel->key ?? 'unknown',
            'word_count_requested' => $inputData['word_count'] ?? 'default',
            'max_tokens' => $this->getMaxTokens($inputData),
            'user_prompt_length' => strlen($userPrompt),
        ]);

        // Generate content with AI
        $aiResponse = $this->aiService->chat(
            $systemPrompt,
            $userPrompt,
            [
                'max_tokens' => $this->getMaxTokens($inputData),
                'temperature' => 0.7,
            ]
        );

        if (!$aiResponse['success']) {
            // Save failed attempt
            $this->saveGeneratedContent(
                $user,
                $contentTypeId,
                $specialtyId,
                $topicId,
                $inputData,
                '',
                $locale,
                0,
                $creditsNeeded,
                'failed',
                $aiResponse['error']
            );

            return [
                'success' => false,
                'error' => 'Failed to generate content: ' . $aiResponse['error'],
                'content' => null,
            ];
        }

        // Filter content through guardrails
        $filtered = $this->guardrail->filter($aiResponse['content'], $locale);

        if ($filtered['needs_regeneration']) {
            // Try regeneration once
            $aiResponse = $this->aiService->chat(
                $systemPrompt . "\n\nIMPORTANT: Do not include any diagnostic language, prescriptions, or specific medical advice.",
                $userPrompt,
                ['max_tokens' => $this->getMaxTokens($inputData)]
            );

            if ($aiResponse['success']) {
                $filtered = $this->guardrail->filter($aiResponse['content'], $locale);
            }
        }

        $finalContent = $filtered['clean_content'];
        $wordCount = str_word_count(strip_tags($finalContent));
        $status = $filtered['passed'] ? 'completed' : 'filtered';

        // Save generated content
        $savedContent = $this->saveGeneratedContent(
            $user,
            $contentTypeId,
            $specialtyId,
            $topicId,
            $inputData,
            $finalContent,
            $locale,
            $aiResponse['tokens_used'],
            $creditsNeeded,
            $status
        );

        // Deduct credits
        $this->deductCredits($user, $creditsNeeded);

        return [
            'success' => true,
            'content' => $finalContent,
            'word_count' => $wordCount,
            'tokens_used' => $aiResponse['tokens_used'],
            'credits_used' => $creditsNeeded,
            'content_id' => $savedContent->id,
            'guardrail_issues' => $filtered['issues'],
        ];
    }

    /**
     * Save generated content to database.
     */
    protected function saveGeneratedContent(
        User $user,
        int $contentTypeId,
        ?int $specialtyId,
        ?int $topicId,
        array $inputData,
        string $outputText,
        string $language,
        int $tokensUsed,
        int $creditsUsed,
        string $status = 'completed',
        ?string $errorMessage = null
    ): GeneratedContent {
        return GeneratedContent::create([
            'user_id' => $user->id,
            'specialty_id' => $specialtyId,
            'topic_id' => $topicId,
            'content_type_id' => $contentTypeId,
            'input_data' => $inputData,
            'output_text' => $outputText,
            'language' => $language,
            'country' => $inputData['country'] ?? null,
            'word_count' => str_word_count(strip_tags($outputText)),
            'credits_used' => $creditsUsed,
            'tokens_used' => $tokensUsed,
            'status' => $status,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Deduct credits from user.
     */
    protected function deductCredits(User $user, int $credits): void
    {
        $user->increment('used_credits', $credits);
    }

    /**
     * Calculate max tokens based on desired word count.
     */
    protected function getMaxTokens(array $inputData): int
    {
        // Use word_count from input or default to 1500
        $wordCount = $inputData['word_count'] ?? 1500;
        
        // Approximate: 1 word ≈ 1.3 tokens for English, more for other languages
        // Add 50% buffer to ensure complete content
        $tokens = (int) ($wordCount * 2);
        
        // Return at least 4000 tokens, max 16000
        return max(4000, min(16000, $tokens));
    }

    /**
     * Get user's content history.
     */
    public function getUserHistory(User $user, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return GeneratedContent::with(['specialty', 'contentType'])
            ->forUser($user->id)
            ->completed()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get content by ID for user.
     */
    public function getContent(User $user, int $contentId): ?GeneratedContent
    {
        return GeneratedContent::with(['specialty', 'contentType', 'topic'])
            ->forUser($user->id)
            ->find($contentId);
    }
}
