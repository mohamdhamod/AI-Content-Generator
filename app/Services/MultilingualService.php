<?php

namespace App\Services;

use App\Models\GeneratedContent;
use App\Models\ContentAnalytics;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MultilingualService
{
    private $openaiApiKey;
    private $timeout = 120;

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    /**
     * Generate content in multiple languages simultaneously
     */
    public function generateMultilingual(array $data, array $targetLanguages): array
    {
        try {
            $sourceLanguage = $data['language'] ?? 'en';
            $results = [
                'success' => true,
                'source_language' => $sourceLanguage,
                'translations' => [],
                'quality_scores' => [],
            ];

            // Generate in source language first
            $sourceContent = $this->generateInLanguage($data, $sourceLanguage);
            
            if (!$sourceContent) {
                throw new \Exception('Failed to generate source content');
            }

            $results['translations'][$sourceLanguage] = $sourceContent;
            $results['quality_scores'][$sourceLanguage] = 100; // Source is always 100%

            // Translate to target languages
            foreach ($targetLanguages as $targetLang) {
                if ($targetLang === $sourceLanguage) {
                    continue;
                }

                $translated = $this->translateContent(
                    $sourceContent,
                    $sourceLanguage,
                    $targetLang,
                    $data['specialty_name'] ?? '',
                    $data['content_type'] ?? ''
                );

                if ($translated['success']) {
                    $results['translations'][$targetLang] = $translated['content'];
                    $results['quality_scores'][$targetLang] = $translated['quality_score'];
                } else {
                    $results['translations'][$targetLang] = null;
                    $results['quality_scores'][$targetLang] = 0;
                }
            }

            return $results;

        } catch (\Exception $e) {
            Log::error('Multilingual generation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => 'Multilingual generation failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate content in specific language
     */
    private function generateInLanguage(array $data, string $language): ?string
    {
        $prompt = $this->buildPrompt($data, $language);

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->openaiApiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4-turbo-preview',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a professional medical content writer specialized in creating high-quality, accurate medical content in multiple languages.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4000,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['choices'][0]['message']['content'] ?? null;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('OpenAI generation failed', [
                'language' => $language,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Public method to translate text
     * @param string $text Text to translate
     * @param string $sourceLang Source language code
     * @param string $targetLang Target language code
     * @param bool $preserveMedicalTerms Whether to preserve medical terminology
     * @return array
     */
    public function translateText(
        string $text,
        string $sourceLang,
        string $targetLang,
        bool $preserveMedicalTerms = true
    ): array {
        $result = $this->translateContent(
            $text,
            $sourceLang,
            $targetLang,
            $preserveMedicalTerms ? 'Medical' : '',
            ''
        );
        
        // Map 'content' to 'translated_text' for controller compatibility
        return [
            'success' => $result['success'],
            'translated_text' => $result['content'] ?? null,
            'quality_score' => $result['quality_score'] ?? 0,
        ];
    }

    /**
     * Translate content with quality assessment
     */
    private function translateContent(
        string $content,
        string $sourceLang,
        string $targetLang,
        string $specialty = '',
        string $contentType = ''
    ): array {
        try {
            $prompt = "Translate the following medical content from {$sourceLang} to {$targetLang}.

IMPORTANT REQUIREMENTS:
1. Maintain medical accuracy and terminology
2. Preserve formatting (headings, lists, paragraphs)
3. Use culturally appropriate language for {$targetLang}
4. Keep medical terms accurate but accessible
5. Maintain the same tone and professionalism
6. " . ($specialty ? "Specialty context: {$specialty}" : '') . "
7. " . ($contentType ? "Content type: {$contentType}" : '') . "

Original content:
{$content}

Translate to {$targetLang}:";

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->openaiApiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4-turbo-preview',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a professional medical translator who maintains accuracy while ensuring readability in the target language.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.3, // Lower for more accurate translation
                    'max_tokens' => 4000,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $translatedContent = $result['choices'][0]['message']['content'] ?? null;

                if ($translatedContent) {
                    // Assess translation quality
                    $qualityScore = $this->assessTranslationQuality(
                        $content,
                        $translatedContent,
                        $sourceLang,
                        $targetLang
                    );

                    return [
                        'success' => true,
                        'content' => $translatedContent,
                        'quality_score' => $qualityScore
                    ];
                }
            }

            return [
                'success' => false,
                'content' => null,
                'quality_score' => 0
            ];

        } catch (\Exception $e) {
            Log::error('Translation failed', [
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'content' => null,
                'quality_score' => 0
            ];
        }
    }

    /**
     * Assess translation quality
     */
    private function assessTranslationQuality(
        string $sourceContent,
        string $translatedContent,
        string $sourceLang,
        string $targetLang
    ): int {
        $score = 100;

        // Length similarity check (±30% is acceptable)
        $sourceLength = mb_strlen($sourceContent);
        $translatedLength = mb_strlen($translatedContent);
        $lengthDiff = abs($sourceLength - $translatedLength) / $sourceLength;

        if ($lengthDiff > 0.5) {
            $score -= 20; // Significant length difference
        } elseif ($lengthDiff > 0.3) {
            $score -= 10; // Moderate length difference
        }

        // Paragraph count check
        $sourceParagraphs = substr_count($sourceContent, "\n\n") + 1;
        $translatedParagraphs = substr_count($translatedContent, "\n\n") + 1;
        
        if (abs($sourceParagraphs - $translatedParagraphs) > 2) {
            $score -= 10; // Structure changed significantly
        }

        // Check for untranslated content (still in source language)
        // This is a simple check - could be enhanced
        if ($sourceLang === 'en' && preg_match('/\b(the|and|or|but|is|are|was|were)\b/i', $translatedContent)) {
            if ($targetLang !== 'en') {
                $score -= 15; // Possible untranslated English words
            }
        }

        // Ensure score is between 0 and 100
        return max(0, min(100, $score));
    }

    /**
     * Build prompt for content generation
     */
    private function buildPrompt(array $data, string $language): string
    {
        $languageNames = [
            'en' => 'English',
            'ar' => 'Arabic',
            'fr' => 'French',
            'es' => 'Spanish',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'zh' => 'Chinese',
            'ja' => 'Japanese',
        ];

        $langName = $languageNames[$language] ?? $language;

        $targetAudience = $data['target_audience'] ?? 'General medical audience';
        $tone = $data['tone'] ?? 'Professional';
        $wordCount = $data['word_count'] ?? 500;

        $prompt = "Generate a {$data['content_type']} in {$langName} about: {$data['topic']}

Specialty: {$data['specialty_name']}
Target audience: {$targetAudience}
Tone: {$tone}
Length: Approximately {$wordCount} words

Requirements:
- Use medical terminology appropriate for the specialty
- Include relevant statistics and research findings
- Structure with clear headings and subheadings
- Make it engaging and easy to understand in {$langName}
- Use culturally appropriate examples for {$langName} speakers
- No disclaimers or watermarks at the end
- Ensure medical accuracy";

        return $prompt;
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages(): array
    {
        return [
            'en' => ['name' => 'English', 'native' => 'English', 'rtl' => false],
            'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'rtl' => true],
            'fr' => ['name' => 'French', 'native' => 'Français', 'rtl' => false],
            'es' => ['name' => 'Spanish', 'native' => 'Español', 'rtl' => false],
            'de' => ['name' => 'German', 'native' => 'Deutsch', 'rtl' => false],
            'it' => ['name' => 'Italian', 'native' => 'Italiano', 'rtl' => false],
            'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'rtl' => false],
            'ru' => ['name' => 'Russian', 'native' => 'Русский', 'rtl' => false],
            'zh' => ['name' => 'Chinese', 'native' => '中文', 'rtl' => false],
            'ja' => ['name' => 'Japanese', 'native' => '日本語', 'rtl' => false],
            'tr' => ['name' => 'Turkish', 'native' => 'Türkçe', 'rtl' => false],
            'nl' => ['name' => 'Dutch', 'native' => 'Nederlands', 'rtl' => false],
            'pl' => ['name' => 'Polish', 'native' => 'Polski', 'rtl' => false],
            'ko' => ['name' => 'Korean', 'native' => '한국어', 'rtl' => false],
            'hi' => ['name' => 'Hindi', 'native' => 'हिन्दी', 'rtl' => false],
        ];
    }

    /**
     * Save multilingual content
     */
    public function saveMultilingualContent(GeneratedContent $content, array $translations, array $qualityScores): void
    {
        $content->update([
            'translations' => $translations,
            'source_language' => $content->language,
            'translation_languages' => array_keys($translations),
            'translation_quality_scores' => $qualityScores,
        ]);

        // Track analytics
        ContentAnalytics::track(
            $content->id,
            'multilingual_generate',
            null,
            [
                'languages' => array_keys($translations),
                'quality_scores' => $qualityScores,
                'average_quality' => array_sum($qualityScores) / count($qualityScores)
            ]
        );
    }
}
