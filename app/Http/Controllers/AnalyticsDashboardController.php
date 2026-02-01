<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsDashboardService;
use App\Models\GeneratedContent;
use Illuminate\Http\Request;

class AnalyticsDashboardController extends Controller
{
    private $analyticsService;

    public function __construct(AnalyticsDashboardService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
        $this->middleware('auth');
    }

    /**
     * Get dashboard overview
     * GET /analytics/overview
     */
    public function overview(Request $request)
    {
        $request->validate([
            'team_id' => 'nullable|exists:team_workspaces,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $dateRange = [];
        if ($request->has('start_date')) {
            $dateRange['start'] = \Carbon\Carbon::parse($request->start_date);
        }
        if ($request->has('end_date')) {
            $dateRange['end'] = \Carbon\Carbon::parse($request->end_date);
        }

        $metrics = $this->analyticsService->getOverviewMetrics(
            $request->user(),
            $request->input('team_id'),
            $dateRange
        );

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }

    /**
     * Get content performance
     * GET /analytics/content/{id}
     */
    public function contentPerformance(Request $request, $lang, $id)
    {
        $content = GeneratedContent::findOrFail($id);

        // Authorization
        if ($content->user_id !== $request->user()->id && !$content->is_team_content) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            abort(403, 'Unauthorized');
        }

        $performance = $this->analyticsService->getContentPerformance($id);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
        }

        // Otherwise return view
        return view('analytics.content', [
            'content' => $content,
            'performance' => $performance
        ]);
    }

    /**
     * Get trend analysis
     * GET /analytics/trends
     */
    public function trends(Request $request)
    {
        $request->validate([
            'metric' => 'required|in:contents,words,seo_score,engagement',
            'days' => 'nullable|integer|min:7|max:90',
        ]);

        $metric = $request->input('metric');
        $days = $request->input('days', 30);

        $trends = $this->analyticsService->getTrendAnalysis(
            $request->user(),
            $metric,
            $days
        );

        return response()->json([
            'success' => true,
            'data' => $trends
        ]);
    }

    /**
     * Get team analytics
     * GET /analytics/team/{id}
     */
    public function teamAnalytics(Request $request, $lang, $id)
    {
        $team = \App\Models\TeamWorkspace::findOrFail($id);

        // Check if user is team member
        if (!$team->hasMember($request->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $dateRange = [];
        if ($request->has('start_date')) {
            $dateRange['start'] = \Carbon\Carbon::parse($request->start_date);
        }
        if ($request->has('end_date')) {
            $dateRange['end'] = \Carbon\Carbon::parse($request->end_date);
        }

        $analytics = $this->analyticsService->getTeamAnalytics($team, $dateRange);

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Calculate engagement score for content
     * POST /analytics/content/{id}/engagement
     */
    public function calculateEngagement($lang, $id)
    {
        $content = GeneratedContent::findOrFail($id);

        // Authorization
        if ($content->user_id !== request()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $score = $this->analyticsService->calculateEngagementScore($content);

        return response()->json([
            'success' => true,
            'data' => [
                'content_id' => $content->id,
                'engagement_score' => $score,
            ]
        ]);
    }

    /**
     * Export analytics report
     * POST /analytics/export
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:pdf,csv,json',
            'type' => 'required|in:overview,content,team',
            'filters' => 'nullable|array',
        ]);

        // This would generate actual PDF/CSV in production
        // For now, return success message

        return response()->json([
            'success' => true,
            'message' => 'Report export in progress',
            'download_url' => '/analytics/download/' . uniqid()
        ]);
    }
}
