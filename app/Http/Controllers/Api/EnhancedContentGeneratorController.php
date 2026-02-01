<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Services\MedicalPromptService;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Enhanced Content Generator Controller
 * 
 * Combines database-driven topics with medical_prompts_library.json
 * for comprehensive, legally-safe, multi-language content generation
 */
class EnhancedContentGeneratorController extends Controller
{
    protected MedicalPromptService $promptService;
    protected OpenAIService $openAIService;
    
    public function __construct(
        MedicalPromptService $promptService,
        OpenAIService $openAIService
    ) {
        $this->promptService = $promptService;
        $this->openAIService = $openAIService;
    }
    
    /**
     * Generate medical content using hybrid prompt system
     * 
     * POST /api/content/generate
     */
    public function generate(Request $request): JsonResponse
    {
        // Get available content types from database
        $availableTypes = \App\Models\ContentType::where('active', true)
            ->pluck('key')
            ->toArray();
        
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'content_type' => 'required|string|in:' . implode(',', $availableTypes),
            'language' => 'required|string|in:' . implode(',', array_keys(config('languages', []))),
            'variables' => 'nullable|array',
            'variables.country' => 'nullable|string',
            'variables.audience' => 'nullable|string',
            'variables.platform' => 'nullable|string',
            'variables.sentiment' => 'nullable|string|in:positive,negative,neutral',
        ]);
        
        try {
            // Load topic with specialty
            $topic = Topic::with('specialty')->findOrFail($validated['topic_id']);
            
            // Get content type for credits calculation
            $contentType = \App\Models\ContentType::where('key', $validated['content_type'])->first();
            
            // Build comprehensive prompt
            $promptData = $this->promptService->buildPrompt(
                $topic,
                $validated['content_type'],
                $validated['language'],
                $validated['variables'] ?? []
            );
            
            // Generate content using OpenAI
            $messages = [
                ['role' => 'system', 'content' => $promptData['system_prompt']],
                ['role' => 'user', 'content' => $promptData['user_prompt']]
            ];
            
            $generatedContent = $this->openAIService->chat($messages);
            
            // Validate content against medical rules
            $validation = $this->promptService->validateContent($generatedContent);
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'error' => 'Generated content violates medical content guidelines',
                    'violations' => $validation['violations']
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'content' => $generatedContent,
                'metadata' => [
                    'topic' => $topic->name,
                    'specialty' => $topic->specialty->name,
                    'content_type' => $validated['content_type'],
                    'content_type_name' => $contentType->name ?? $validated['content_type'],
                    'language' => $validated['language'],
                    'credits_used' => $contentType->credits_cost ?? 1,
                ],
                'prompt_used' => config('app.debug') ? $promptData : null, // Only in debug mode
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Content generation failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get available content types for a topic
     * 
     * GET /api/content/types/{topicId}
     */
    public function getContentTypes(int $topicId): JsonResponse
    {
        try {
            $topic = Topic::with('specialty')->findOrFail($topicId);
            
            // Get all active content types from database
            $contentTypes = \App\Models\ContentType::where('active', true)
                ->ordered()
                ->get()
                ->map(function($type) {
                    return [
                        'key' => $type->key,
                        'name' => $type->name,
                        'description' => $type->description,
                        'icon' => $type->icon,
                        'color' => $type->color,
                        'credits_cost' => $type->credits_cost,
                    ];
                });
            
            return response()->json([
                'success' => true,
                'content_types' => $contentTypes,
                'topic' => $topic->name,
                'specialty' => $topic->specialty->name,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    /**
     * Get output guidelines for specific content type
     * 
     * GET /api/content/guidelines/{topicId}/{contentType}
     */
    public function getGuidelines(int $topicId, string $contentType): JsonResponse
    {
        try {
            $topic = Topic::with('specialty')->findOrFail($topicId);
            
            // Get content type from database
            $contentTypeModel = \App\Models\ContentType::where('key', $contentType)
                ->where('active', true)
                ->first();
            
            if (!$contentTypeModel) {
                return response()->json([
                    'success' => false,
                    'error' => 'Content type not found or inactive'
                ], 404);
            }
            
            $guidelines = $this->promptService->getContentTypeRequirements($contentType);
            
            return response()->json([
                'success' => true,
                'content_type' => [
                    'key' => $contentTypeModel->key,
                    'name' => $contentTypeModel->name,
                    'description' => $contentTypeModel->description,
                    'credits_cost' => $contentTypeModel->credits_cost,
                ],
                'guidelines' => $guidelines,
                'topic' => $topic->name,
                'specialty' => $topic->specialty->name,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    /**
     * Preview prompt without generating content
     * 
     * POST /api/content/preview-prompt
     */
    public function previewPrompt(Request $request): JsonResponse
    {
        // Get available content types from database
        $availableTypes = \App\Models\ContentType::where('active', true)
            ->pluck('key')
            ->toArray();
        
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'content_type' => 'required|string|in:' . implode(',', $availableTypes),
            'language' => 'required|string|in:' . implode(',', array_keys(config('languages', []))),
            'variables' => 'nullable|array',
        ]);
        
        try {
            $topic = Topic::with('specialty')->findOrFail($validated['topic_id']);
            
            $promptData = $this->promptService->buildPrompt(
                $topic,
                $validated['content_type'],
                $validated['language'],
                $validated['variables'] ?? []
            );
            
            return response()->json([
                'success' => true,
                'prompt_data' => $promptData,
                'topic' => $topic->name,
                'specialty' => $topic->specialty->name,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
