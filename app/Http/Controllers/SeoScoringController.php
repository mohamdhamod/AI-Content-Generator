<?php

namespace App\Http\Controllers;

use App\Models\GeneratedContent;
use App\Services\SeoScoringService;
use App\Models\ContentAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SeoScoringController extends Controller
{
    protected $seoService;
    
    public function __construct(SeoScoringService $seoService)
    {
        $this->seoService = $seoService;
    }
    
    /**
     * Analyze SEO for content
     */
    public function analyzeSeo(Request $request, $lang , int $id)
    {
        try {
            $content = GeneratedContent::findOrFail($id);
            
            // Verify ownership
            if ($content->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            $validated = $request->validate([
                'focus_keyword' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500'
            ]);
            
            // Prepare SEO analysis options
            $options = [
                'target_keyword' => $validated['focus_keyword'] ?? $content->seo_focus_keyword,
                'meta_description' => $validated['meta_description'] ?? $content->seo_meta_description,
            ];
            
            // Calculate SEO score - pass the content object, not the text
            $result = $this->seoService->calculateScore($content, $options);
            
            // Service returns data directly, not wrapped in success/data
            // Save SEO data to content
            $content->update([
                'seo_focus_keyword' => $options['target_keyword'],
                'seo_meta_description' => $options['meta_description'],
                'seo_score_data' => $result,
                'seo_overall_score' => $result['overall_score'],
                'last_seo_check' => now()
            ]);
            
            // Track analytics
            ContentAnalytics::track(
                $content->id,
                'seo_check',
                null,
                [
                    'score' => $result['overall_score'],
                    'grade' => $result['grade'],
                    'keyword' => $options['target_keyword']
                ]
            );
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('SEO analysis failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'SEO analysis failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get SEO report
     */
    public function getSeoReport($lang, int $id)
    {
        try {
            $content = GeneratedContent::findOrFail($id);
            
            // Verify ownership
            if ($content->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            // Check if SEO data exists
            if (!$content->seo_score_data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No SEO data available. Please run SEO analysis first.'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'content_id' => $content->id,
                    'title' => $content->title,
                    'specialty' => $content->specialty->name,
                    'focus_keyword' => $content->seo_focus_keyword,
                    'meta_description' => $content->seo_meta_description,
                    'seo_score' => $content->seo_score_data,
                    'overall_score' => $content->seo_overall_score,
                    'last_checked' => $content->last_seo_check,
                    'created_at' => $content->created_at,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('SEO report retrieval failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve SEO report'
            ], 500);
        }
    }
    
    /**
     * Get SEO recommendations
     */
    public function getRecommendations($lang, int $id)
    {
        try {
            $content = GeneratedContent::findOrFail($id);
            
            // Verify ownership
            if ($content->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            if (!$content->seo_score_data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No SEO data available'
                ], 404);
            }
            
            $seoData = $content->seo_score_data;
            
            // Prioritize recommendations
            $recommendations = collect($seoData['recommendations'] ?? [])
                ->map(function ($rec) {
                    // Assign priority based on impact
                    $priority = 'medium';
                    if (str_contains(strtolower($rec), 'critical') || str_contains(strtolower($rec), 'required')) {
                        $priority = 'high';
                    } elseif (str_contains(strtolower($rec), 'consider') || str_contains(strtolower($rec), 'optional')) {
                        $priority = 'low';
                    }
                    
                    return [
                        'text' => $rec,
                        'priority' => $priority
                    ];
                })
                ->sortBy(function ($rec) {
                    $order = ['high' => 1, 'medium' => 2, 'low' => 3];
                    return $order[$rec['priority']];
                })
                ->values();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'recommendations' => $recommendations,
                    'overall_score' => $content->seo_overall_score,
                    'grade' => $seoData['grade'] ?? 'N/A'
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('SEO recommendations retrieval failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recommendations'
            ], 500);
        }
    }
    
    /**
     * Batch analyze SEO for multiple contents
     */
    public function batchAnalyze(Request $request, $lang)
    {
        try {
            $validated = $request->validate([
                'content_ids' => 'required|array|min:1',
                'content_ids.*' => 'required|integer|exists:generated_contents,id'
            ]);
            
            $results = [];
            
            foreach ($validated['content_ids'] as $contentId) {
                $content = GeneratedContent::find($contentId);
                
                // Skip if not owned by user
                if ($content->user_id !== auth()->id()) {
                    continue;
                }
                
                $options = [
                    'keyword' => $content->seo_focus_keyword,
                    'meta_description' => $content->seo_meta_description,
                    'title' => $content->seo_title ?? $content->title,
                    'language' => $content->language ?? 'en'
                ];
                
                $result = $this->seoService->calculateScore(
                    $content->generated_text,
                    $options
                );
                
                if ($result['success']) {
                    $content->update([
                        'seo_score_data' => $result['data'],
                        'seo_overall_score' => $result['data']['overall_score'],
                        'last_seo_check' => now()
                    ]);
                    
                    $results[] = [
                        'id' => $content->id,
                        'score' => $result['data']['overall_score'],
                        'grade' => $result['data']['grade']
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => sprintf('Analyzed %d contents', count($results))
            ]);
            
        } catch (\Exception $e) {
            Log::error('Batch SEO analysis failed', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Batch analysis failed'
            ], 500);
        }
    }
    
    /**
     * Compare SEO scores over time
     */
    public function compareScores($lang, int $id)
    {
        try {
            $content = GeneratedContent::findOrFail($id);
            
            // Verify ownership
            if ($content->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            // Get SEO check history from analytics
            $history = ContentAnalytics::where('content_id', $id)
                ->where('action_type', 'seo_check')
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($analytics) {
                    $metadata = $analytics->metadata ?? [];
                    return [
                        'date' => $analytics->created_at->format('Y-m-d H:i'),
                        'score' => $metadata['score'] ?? 0,
                        'grade' => $metadata['grade'] ?? 'N/A',
                        'keyword' => $metadata['keyword'] ?? null
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'current_score' => $content->seo_overall_score,
                    'current_grade' => $content->seo_score_data['grade'] ?? 'N/A',
                    'history' => $history,
                    'improvement' => $history->count() > 1 
                        ? $history->last()['score'] - $history->first()['score']
                        : 0
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('SEO score comparison failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to compare scores'
            ], 500);
        }
    }
}
