<?php

namespace App\Http\Controllers;

use App\Services\MultilingualService;
use App\Models\GeneratedContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class MultilingualController extends Controller
{
    private $multilingualService;

    public function __construct(MultilingualService $multilingualService)
    {
        $this->multilingualService = $multilingualService;
        $this->middleware('auth');
    }

    /**
     * Generate content in multiple languages
     * POST /content/multilingual
     */
    public function generateMultilingual(Request $request)
    {
        // Rate limiting
        $key = 'multilingual-generate:' . $request->user()->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please wait before generating more multilingual content.'
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'topic' => 'required|string|max:500',
            'specialty_id' => 'required|exists:specialties,id',
            'content_type_id' => 'required|exists:content_types,id',
            'target_languages' => 'required|array|min:1',
            'target_languages.*' => 'required|string|in:en,ar,fr,es,de,it,pt,ru,zh,ja,tr,nl,pl,ko,hi',
            'source_language' => 'nullable|string|in:en,ar,fr,es,de,it,pt,ru,zh,ja,tr,nl,pl,ko,hi',
            'tone' => 'nullable|string',
            'word_count' => 'nullable|integer|min:100|max:5000',
        ]);

        try {
            $data = $request->only([
                'topic', 'specialty_id', 'content_type_id', 
                'tone', 'word_count', 'target_audience'
            ]);

            // Get specialty and content type names
            $specialty = \App\Models\Specialty::find($request->specialty_id);
            $contentType = \App\Models\ContentType::find($request->content_type_id);

            $data['specialty_name'] = $specialty->name;
            $data['content_type'] = $contentType->name;
            $data['language'] = $request->source_language ?? 'en';

            // Generate multilingual content
            $result = $this->multilingualService->generateMultilingual(
                $data,
                $request->target_languages
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to generate multilingual content'
                ], 500);
            }

            // Save primary content
            $sourceLanguage = $result['source_language'];
            $content = GeneratedContent::create([
                'user_id' => $request->user()->id,
                'specialty_id' => $request->specialty_id,
                'content_type_id' => $request->content_type_id,
                'topic' => $request->topic,
                'language' => $sourceLanguage,
                'tone' => $request->tone ?? 'professional',
                'target_audience' => $request->target_audience ?? 'general',
                'word_count' => str_word_count($result['translations'][$sourceLanguage]),
                'generated_content' => $result['translations'][$sourceLanguage],
                'translations' => $result['translations'],
                'source_language' => $sourceLanguage,
                'translation_languages' => array_keys($result['translations']),
                'translation_quality_scores' => $result['quality_scores'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Multilingual content generated successfully',
                'data' => [
                    'content_id' => $content->id,
                    'source_language' => $sourceLanguage,
                    'translations' => $result['translations'],
                    'quality_scores' => $result['quality_scores'],
                    'languages' => array_keys($result['translations']),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Translate existing content
     * POST /content/{id}/translate
     */
    public function translateContent(Request $request, $lang , $id)
    {
        // Rate limiting
        $key = 'translate:' . $request->user()->id;
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many translation requests. Please wait.'
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'target_languages' => 'required|array|min:1',
            'target_languages.*' => 'required|string',
            'preserve_medical_terms' => 'nullable|boolean',
        ]);

        $content = GeneratedContent::findOrFail($id);

        // Authorization
        if ($content->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $targetLanguages = $request->target_languages;
            $preserveMedicalTerms = $request->preserve_medical_terms ?? true;
            
            // Check if content has output_text
            if (empty($content->output_text)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Content has no text to translate'
                ], 400);
            }
            
            // Get existing translations or empty array
            $translations = $content->translations ?? [];
            $qualityScores = $content->translation_quality_scores ?? [];
            
            // Language names for display
            $languageNames = [
                'en' => 'English', 'ar' => 'Arabic', 'de' => 'German',
                'fr' => 'French', 'es' => 'Spanish', 'tr' => 'Turkish',
                'it' => 'Italian', 'pt' => 'Portuguese', 'ru' => 'Russian',
                'zh' => 'Chinese', 'ja' => 'Japanese', 'ko' => 'Korean',
            ];
            
            $translatedLanguages = [];
            $errors = [];
            
            foreach ($targetLanguages as $targetLang) {
                // Skip if same as source language
                if ($targetLang === ($content->language ?? 'en')) {
                    continue;
                }
                
                // Call translation service
                $result = $this->multilingualService->translateText(
                    $content->output_text,
                    $content->language ?? 'en',
                    $targetLang,
                    $preserveMedicalTerms
                );
                
                \Log::info('Translation result for ' . $targetLang, $result);
                
                if ($result['success'] && !empty($result['translated_text'])) {
                    $translations[$targetLang] = $result['translated_text'];
                    $qualityScores[$targetLang] = $result['quality_score'] ?? 85;
                    $translatedLanguages[] = $languageNames[$targetLang] ?? $targetLang;
                } else {
                    $errors[] = $targetLang;
                }
            }
            
            // Check if any translations were successful
            if (empty($translatedLanguages)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation failed for all languages. Please try again.'
                ], 500);
            }
            
            // Save translations to content
            $content->update([
                'translations' => $translations,
                'translation_quality_scores' => $qualityScores,
                'source_language' => $content->language ?? 'en',
            ]);
            
            // Refresh model to get updated data
            $content->refresh();
            
            \Log::info('Saved translations', [
                'content_id' => $content->id,
                'translations_count' => count($content->translations ?? []),
                'languages' => array_keys($content->translations ?? [])
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Translation completed successfully',
                'data' => [
                    'content_id' => $content->id,
                    'translations' => array_map(function($lang) use ($languageNames, $translations) {
                        return [
                            'language' => $lang,
                            'language_name' => $languageNames[$lang] ?? $lang,
                            'preview' => \Illuminate\Support\Str::limit($translations[$lang] ?? '', 100),
                            'url' => route('content.translation.show', ['id' => request()->route('id'), 'language' => $lang]),
                        ];
                    }, array_keys($translations)),
                    'translated_languages' => $translatedLanguages,
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Translation failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Translation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all translations for content
     * GET /content/{id}/translations
     */
    public function getTranslations($lang, $id)
    {
        $content = GeneratedContent::findOrFail($id);

        // Authorization
        if ($content->user_id !== request()->user()->id && !$content->is_team_content) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $translations = $content->translations ?? [];
        $qualityScores = $content->translation_quality_scores ?? [];
        
        // Language names for display
        $languageNames = [
            'en' => 'English', 'ar' => 'Arabic', 'de' => 'German',
            'fr' => 'French', 'es' => 'Spanish', 'tr' => 'Turkish',
            'it' => 'Italian', 'pt' => 'Portuguese', 'ru' => 'Russian',
            'zh' => 'Chinese', 'ja' => 'Japanese', 'ko' => 'Korean',
        ];
        
        // Format translations for display
        $formattedTranslations = [];
        foreach ($translations as $langCode => $text) {
            $formattedTranslations[] = [
                'language' => $langCode,
                'language_name' => $languageNames[$langCode] ?? $langCode,
                'preview' => \Illuminate\Support\Str::limit($text, 100),
                'quality_score' => $qualityScores[$langCode] ?? null,
                'url' => route('content.translation.show', ['id' => $id, 'language' => $langCode]),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $formattedTranslations
        ]);
    }

    /**
     * Get specific translation
     * GET /content/{id}/translation/{language}
     */
    public function getTranslation($lang ,$id, $language)
    {
        $content = GeneratedContent::findOrFail($id);

        // Authorization
        if ($content->user_id !== request()->user()->id && !$content->is_team_content) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $translations = $content->translations ?? [];
        $qualityScores = $content->translation_quality_scores ?? [];

        if (!isset($translations[$language])) {
            return response()->json([
                'success' => false,
                'message' => 'Translation not found for language: ' . $language
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'content_id' => $content->id,
                'language' => $language,
                'translation' => $translations[$language],
                'quality_score' => $qualityScores[$language] ?? null,
                'source_language' => $content->source_language,
            ]
        ]);
    }

    /**
     * Delete translation
     * DELETE /content/{id}/translation/{language}
     */
    public function deleteTranslation($lang, $id, $language)
    {
        $content = GeneratedContent::findOrFail($id);

        // Authorization
        if ($content->user_id !== request()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $translations = $content->translations ?? [];
        $qualityScores = $content->translation_quality_scores ?? [];

        if (!isset($translations[$language])) {
            return response()->json([
                'success' => false,
                'message' => 'Translation not found'
            ], 404);
        }

        // Remove translation
        unset($translations[$language]);
        unset($qualityScores[$language]);

        $content->update([
            'translations' => $translations,
            'translation_quality_scores' => $qualityScores,
            'translation_languages' => array_keys($translations),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Translation deleted successfully'
        ]);
    }

    /**
     * Get supported languages
     * GET /languages
     */
    public function getSupportedLanguages()
    {
        $languages = $this->multilingualService->getSupportedLanguages();

        return response()->json([
            'success' => true,
            'data' => $languages
        ]);
    }
}
