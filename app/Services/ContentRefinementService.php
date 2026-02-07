<?php

namespace App\Services;

use App\Models\GeneratedContent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContentRefinementService
{
    protected OpenAIService|GeminiService $aiService;

    public function __construct()
    {
        $this->aiService = AIServiceFactory::create();
    }

    /**
     * Available refinement actions
     */
    const REFINEMENT_ACTIONS = [
        'improve_clarity' => 'Improve clarity and readability',
        'enhance_medical_accuracy' => 'Enhance medical accuracy and terminology',
        'simplify_language' => 'Simplify language for general audience',
        'add_examples' => 'Add practical examples and scenarios',
        'expand_details' => 'Expand with more detailed information',
        'make_concise' => 'Make more concise and focused',
        'improve_structure' => 'Improve structure and organization',
        'add_citations' => 'Add medical citations and references',
        'patient_friendly' => 'Make more patient-friendly',
        'professional_tone' => 'Enhance professional medical tone',
    ];

    /**
     * Available tone styles
     */
    const TONE_STYLES = [
        'formal' => 'Formal and academic',
        'casual' => 'Casual and conversational',
        'empathetic' => 'Empathetic and caring',
        'authoritative' => 'Authoritative and confident',
        'educational' => 'Educational and informative',
        'encouraging' => 'Encouraging and supportive',
        'professional' => 'Professional medical standard',
        'simple' => 'Simple and easy to understand',
    ];

    /**
     * Refine content using AI
     *
     * @param GeneratedContent $content
     * @param string $action Refinement action from REFINEMENT_ACTIONS
     * @param array $options Additional options (tone, language, etc.)
     * @return GeneratedContent New refined version
     */
    public function refineContent(GeneratedContent $content, string $action, array $options = []): GeneratedContent
    {
        // Validate action
        if (!isset(self::REFINEMENT_ACTIONS[$action])) {
            throw new \InvalidArgumentException("Invalid refinement action: {$action}");
        }

        // Build refinement prompt
        $prompt = $this->buildRefinementPrompt($content, $action, $options);

        // Call OpenAI API
        $refinedText = $this->callOpenAI($prompt, $options);

        // Create new version
        $newVersion = $this->createNewVersion($content, $refinedText, $action, $options);

        // Track refinement analytics
        \App\Models\ContentAnalytics::track(
            $newVersion->id,
            'ai_refine',
            null,
            [
                'refinement_action' => $action,
                'tone' => $options['tone'] ?? null,
                'parent_id' => $content->id,
                'parent_version' => $content->version,
            ]
        );

        return $newVersion;
    }

    /**
     * Adjust content tone using AI
     *
     * @param GeneratedContent $content
     * @param string $tone Tone style from TONE_STYLES
     * @return GeneratedContent New version with adjusted tone
     */
    public function adjustTone(GeneratedContent $content, string $tone): GeneratedContent
    {
        // Validate tone
        if (!isset(self::TONE_STYLES[$tone])) {
            throw new \InvalidArgumentException("Invalid tone style: {$tone}");
        }

        $options = ['tone' => $tone];
        $prompt = $this->buildToneAdjustmentPrompt($content, $tone);

        // Call OpenAI API
        $refinedText = $this->callOpenAI($prompt, $options);

        // Create new version
        $newVersion = $this->createNewVersion($content, $refinedText, 'tone_adjustment', $options);

        // Track tone adjustment
        \App\Models\ContentAnalytics::track(
            $newVersion->id,
            'tone_adjust',
            null,
            [
                'tone' => $tone,
                'parent_id' => $content->id,
                'parent_version' => $content->version,
            ]
        );

        return $newVersion;
    }

    /**
     * Build refinement prompt for AI
     */
    protected function buildRefinementPrompt(GeneratedContent $content, string $action, array $options): string
    {
        $actionDescription = self::REFINEMENT_ACTIONS[$action];
        $language = $options['language'] ?? $content->language ?? 'English';
        $tone = $options['tone'] ?? 'professional';

        $specialty = $content->specialty ? $content->specialty->name : 'General Medicine';
        $contentType = $content->contentType ? $content->contentType->name : 'Medical Content';

        $prompt = <<<PROMPT
You are a medical content refinement expert specializing in {$specialty}.

**Task:** {$actionDescription}

**Original Content:**
{$content->output_text}

**Content Details:**
- Specialty: {$specialty}
- Content Type: {$contentType}
- Language: {$language}
- Target Tone: {$tone}

**Refinement Instructions:**
1. {$actionDescription}
2. Maintain medical accuracy and terminology
3. Keep the same language ({$language})
4. Use {$tone} tone throughout
5. Preserve important medical information
6. DO NOT add watermarks or signatures
7. Output ONLY the refined content, no explanations

**Refined Content:**
PROMPT;

        return $prompt;
    }

    /**
     * Build tone adjustment prompt
     */
    protected function buildToneAdjustmentPrompt(GeneratedContent $content, string $tone): string
    {
        $toneDescription = self::TONE_STYLES[$tone];
        $language = $content->language ?? 'English';
        $specialty = $content->specialty ? $content->specialty->name : 'General Medicine';

        $prompt = <<<PROMPT
You are a medical content tone adjustment expert.

**Task:** Adjust the tone of the following medical content to be: {$toneDescription}

**Original Content:**
{$content->output_text}

**Adjustment Requirements:**
1. Change tone to: {$toneDescription}
2. Maintain all medical facts and accuracy
3. Keep the same language: {$language}
4. Preserve medical specialty context: {$specialty}
5. Keep the same length (±10%)
6. DO NOT change medical terminology accuracy
7. Output ONLY the adjusted content, no explanations

**Tone-Adjusted Content:**
PROMPT;

        return $prompt;
    }

    /**
     * Call AI Service for refinement
     */
    protected function callOpenAI(string $prompt, array $options): string
    {
        if (!$this->aiService->isConfigured()) {
            throw new \Exception('AI service is not configured');
        }

        try {
            $systemPrompt = 'You are a professional medical content refinement assistant. Always maintain medical accuracy while improving content quality.';
            
            $response = $this->aiService->chat($systemPrompt, $prompt, [
                'temperature' => 0.7,
                'max_tokens' => 4000,
            ]);

            if (!$response['success']) {
                Log::error('AI API Error', [
                    'error' => $response['error'],
                ]);
                throw new \Exception('Failed to refine content with AI: ' . $response['error']);
            }

            $refinedText = $response['content'] ?? '';

            if (empty($refinedText)) {
                throw new \Exception('Empty response from AI refinement');
            }

            return trim($refinedText);

        } catch (\Exception $e) {
            Log::error('Content Refinement Error', [
                'message' => $e->getMessage(),
                'options' => $options,
            ]);
            throw $e;
        }
    }

    /**
     * Create new version of content
     */
    protected function createNewVersion(
        GeneratedContent $original,
        string $refinedText,
        string $action,
        array $options
    ): GeneratedContent {
        // Create new version
        $newVersion = $original->replicate();
        $newVersion->output_text = $refinedText;
        $newVersion->version = $original->version + 1;
        $newVersion->parent_content_id = $original->id;
        $newVersion->review_status = 'draft'; // New version needs review
        $newVersion->is_published = false;
        $newVersion->reviewed_by = null;
        $newVersion->reviewed_at = null;
        $newVersion->review_notes = "AI Refinement: {$action}" . (isset($options['tone']) ? " (Tone: {$options['tone']})" : '');
        
        // Reset counters for new version
        $newVersion->view_count = 0;
        $newVersion->share_count = 0;
        $newVersion->pdf_download_count = 0;

        // Update word count
        $newVersion->word_count = str_word_count(strip_tags($refinedText));

        $newVersion->save();

        return $newVersion;
    }

    /**
     * Get version history for content
     */
    public function getVersionHistory(GeneratedContent $content): array
    {
        // Get root content (original)
        $rootId = $content->parent_content_id ?? $content->id;
        
        // Get all versions
        $versions = GeneratedContent::where(function ($query) use ($rootId) {
            $query->where('id', $rootId)
                  ->orWhere('parent_content_id', $rootId);
        })
        ->with(['user', 'reviewer'])
        ->orderBy('version', 'asc')
        ->get();

        return $versions->map(function ($version) {
            return [
                'id' => $version->id,
                'version' => $version->version,
                'created_at' => $version->created_at,
                'review_status' => $version->review_status,
                'review_notes' => $version->review_notes,
                'word_count' => $version->word_count,
                'is_current' => $version->id === $this->id ?? false,
            ];
        })->toArray();
    }

    /**
     * Compare two versions of content
     */
    public function compareVersions(GeneratedContent $version1, GeneratedContent $version2): array
    {
        return [
            'version_1' => [
                'id' => $version1->id,
                'version' => $version1->version,
                'text' => $version1->output_text,
                'word_count' => $version1->word_count,
                'created_at' => $version1->created_at,
            ],
            'version_2' => [
                'id' => $version2->id,
                'version' => $version2->version,
                'text' => $version2->output_text,
                'word_count' => $version2->word_count,
                'created_at' => $version2->created_at,
            ],
            'differences' => [
                'word_count_change' => $version2->word_count - $version1->word_count,
                'length_change_percent' => round(
                    (($version2->word_count - $version1->word_count) / $version1->word_count) * 100,
                    2
                ),
            ],
        ];
    }

    /**
     * Get available refinement actions
     */
    public static function getAvailableActions(): array
    {
        return self::REFINEMENT_ACTIONS;
    }

    /**
     * Get available tone styles
     */
    public static function getAvailableTones(): array
    {
        return self::TONE_STYLES;
    }
}
