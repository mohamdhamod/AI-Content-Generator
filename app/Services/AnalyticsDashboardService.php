<?php

namespace App\Services;

use App\Models\GeneratedContent;
use App\Models\ContentAnalytics;
use App\Models\User;
use App\Models\TeamWorkspace;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsDashboardService
{
    /**
     * Get overview metrics for user or team
     */
    public function getOverviewMetrics(User $user, ?int $teamId = null, array $dateRange = []): array
    {
        $startDate = $dateRange['start'] ?? Carbon::now()->subDays(30);
        $endDate = $dateRange['end'] ?? Carbon::now();

        $query = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($teamId) {
            $query->where('team_workspace_id', $teamId);
        }

        // Total contents
        $totalContents = $query->count();
        $previousPeriodContents = $this->getPreviousPeriodCount($user, $teamId, $startDate, $endDate);
        $contentsGrowth = $this->calculateGrowth($totalContents, $previousPeriodContents);

        // Total words
        $totalWords = $query->sum('word_count');
        $previousPeriodWords = $this->getPreviousPeriodWords($user, $teamId, $startDate, $endDate);
        $wordsGrowth = $this->calculateGrowth($totalWords, $previousPeriodWords);

        // Average SEO score
        $avgSeoScore = $query->whereNotNull('seo_score')->avg('seo_score') ?? 0;

        // Publishing statistics
        $publishedCount = $query->where('publishing_status', 'published')->count();
        $scheduledCount = $query->where('publishing_status', 'scheduled')->count();
        $draftCount = $query->where('publishing_status', 'draft')->count();

        // Engagement metrics
        $avgEngagementScore = $query->whereNotNull('engagement_score')->avg('engagement_score') ?? 0;
        $avgConversionRate = $query->whereNotNull('conversion_rate')->avg('conversion_rate') ?? 0;
        $totalClicks = $query->sum('click_count') ?? 0;

        // Content by type
        $contentsByType = $query->select('content_type_id', DB::raw('count(*) as count'))
            ->groupBy('content_type_id')
            ->with('contentType')
            ->get()
            ->map(function($item) {
                return [
                    'type' => $item->contentType->name ?? 'Unknown',
                    'count' => $item->count
                ];
            });

        // Content by specialty
        $contentsBySpecialty = $query->select('specialty_id', DB::raw('count(*) as count'))
            ->groupBy('specialty_id')
            ->with('specialty')
            ->get()
            ->map(function($item) {
                return [
                    'specialty' => $item->specialty->name ?? 'Unknown',
                    'count' => $item->count
                ];
            });

        // Activity timeline (last 30 days)
        $activityTimeline = $this->getActivityTimeline($user, $teamId, $startDate, $endDate);

        return [
            'overview' => [
                'total_contents' => $totalContents,
                'contents_growth' => $contentsGrowth,
                'total_words' => $totalWords,
                'words_growth' => $wordsGrowth,
                'avg_seo_score' => round($avgSeoScore, 1),
                'avg_engagement_score' => round($avgEngagementScore, 1),
                'avg_conversion_rate' => round($avgConversionRate, 2),
                'total_clicks' => $totalClicks,
            ],
            'publishing' => [
                'published' => $publishedCount,
                'scheduled' => $scheduledCount,
                'draft' => $draftCount,
            ],
            'distribution' => [
                'by_type' => $contentsByType,
                'by_specialty' => $contentsBySpecialty,
            ],
            'timeline' => $activityTimeline,
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'days' => $startDate->diffInDays($endDate),
            ],
        ];
    }

    /**
     * Get content performance metrics
     */
    public function getContentPerformance(int $contentId): array
    {
        $content = GeneratedContent::findOrFail($contentId);

        // Get analytics events
        $analytics = ContentAnalytics::where('generated_content_id', $contentId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Count by action type
        $actionCounts = $analytics->groupBy('action')
            ->map(function($group) {
                return $group->count();
            });

        // Calculate performance score
        $performanceScore = $this->calculatePerformanceScore($content, $actionCounts);

        // SEO breakdown
        $seoBreakdown = $content->seo_breakdown ?? [];

        // Engagement timeline
        $engagementTimeline = $analytics
            ->whereIn('action', ['view', 'share', 'download'])
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function($group) {
                return $group->count();
            });

        return [
            'content' => [
                'id' => $content->id,
                'title' => $content->title,
                'created_at' => $content->created_at->format('Y-m-d H:i'),
                'word_count' => $content->word_count ?? str_word_count(strip_tags($content->output_text ?? '')),
                'status' => $content->publishing_status ?? $content->status ?? 'draft',
            ],
            'seo' => [
                'score' => $content->seo_overall_score ?? 0,
                'grade' => $content->seo_grade ?? 'N/A',
                'breakdown' => $content->seo_score_data ?? $seoBreakdown,
                'last_checked' => $content->last_seo_check?->diffForHumans() ?? null,
            ],
            'engagement' => [
                'score' => $content->engagement_score ?? 0,
                'conversion_rate' => $content->conversion_rate ?? 0,
                'clicks' => $content->click_count ?? 0,
                'views' => $actionCounts->get('view', 0),
                'shares' => $actionCounts->get('share', 0),
                'downloads' => $actionCounts->get('download', 0),
            ],
            'actions' => $actionCounts->toArray(),
            'performance_score' => $performanceScore,
            'engagement_timeline' => $engagementTimeline->toArray(),
        ];
    }

    /**
     * Get trend analysis
     */
    public function getTrendAnalysis(User $user, string $metric = 'contents', int $days = 30): array
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($days);

        $query = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $data = [];

        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            $dayQuery = clone $query;
            $dayQuery->whereDate('created_at', $date);

            switch ($metric) {
                case 'contents':
                    $value = $dayQuery->count();
                    break;
                case 'words':
                    $value = $dayQuery->sum('word_count');
                    break;
                case 'seo_score':
                    $value = $dayQuery->avg('seo_score') ?? 0;
                    break;
                case 'engagement':
                    $value = $dayQuery->avg('engagement_score') ?? 0;
                    break;
                default:
                    $value = 0;
            }

            $data[] = [
                'date' => $date->format('Y-m-d'),
                'value' => round($value, 2),
            ];
        }

        // Calculate trend direction
        $firstHalf = array_slice($data, 0, (int)($days / 2));
        $secondHalf = array_slice($data, (int)($days / 2));

        $firstHalfAvg = collect($firstHalf)->avg('value');
        $secondHalfAvg = collect($secondHalf)->avg('value');

        $trend = 'stable';
        if ($secondHalfAvg > $firstHalfAvg * 1.1) {
            $trend = 'increasing';
        } elseif ($secondHalfAvg < $firstHalfAvg * 0.9) {
            $trend = 'decreasing';
        }

        return [
            'metric' => $metric,
            'period' => "{$days} days",
            'data' => $data,
            'trend' => $trend,
            'first_half_avg' => round($firstHalfAvg, 2),
            'second_half_avg' => round($secondHalfAvg, 2),
            'change_percent' => $firstHalfAvg > 0 
                ? round((($secondHalfAvg - $firstHalfAvg) / $firstHalfAvg) * 100, 1)
                : 0,
        ];
    }

    /**
     * Get team analytics
     */
    public function getTeamAnalytics(TeamWorkspace $team, array $dateRange = []): array
    {
        $startDate = $dateRange['start'] ?? Carbon::now()->subDays(30);
        $endDate = $dateRange['end'] ?? Carbon::now();

        // Team contents
        $teamContents = GeneratedContent::where('team_workspace_id', $team->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Member activity
        $memberActivity = DB::table('team_members')
            ->join('generated_contents', 'team_members.user_id', '=', 'generated_contents.user_id')
            ->where('team_members.team_workspace_id', $team->id)
            ->where('generated_contents.team_workspace_id', $team->id)
            ->whereBetween('generated_contents.created_at', [$startDate, $endDate])
            ->select('team_members.user_id', DB::raw('count(*) as content_count'))
            ->groupBy('team_members.user_id')
            ->get();

        // Active assignments
        $activeAssignments = DB::table('content_assignments')
            ->where('team_workspace_id', $team->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        // Comments activity
        $commentsCount = DB::table('content_comments')
            ->join('generated_contents', 'content_comments.generated_content_id', '=', 'generated_contents.id')
            ->where('generated_contents.team_workspace_id', $team->id)
            ->whereBetween('content_comments.created_at', [$startDate, $endDate])
            ->count();

        // Template usage
        $templateUsage = DB::table('templates')
            ->where('team_workspace_id', $team->id)
            ->sum('usage_count');

        return [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
                'plan' => $team->plan,
                'member_count' => $team->members()->where('status', 'active')->count(),
            ],
            'metrics' => [
                'total_contents' => $teamContents,
                'active_assignments' => $activeAssignments,
                'comments' => $commentsCount,
                'template_usage' => $templateUsage,
            ],
            'member_activity' => $memberActivity->map(function($item) {
                return [
                    'user_id' => $item->user_id,
                    'content_count' => $item->content_count,
                ];
            }),
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
        ];
    }

    /**
     * Calculate engagement score for content
     */
    public function calculateEngagementScore(GeneratedContent $content): float
    {
        $analytics = ContentAnalytics::where('generated_content_id', $content->id)->get();

        $views = $analytics->where('action', 'view')->count();
        $shares = $analytics->where('action', 'share')->count();
        $downloads = $analytics->where('action', 'download')->count();
        $clicks = $content->click_count ?? 0;

        // Weighted score
        $score = ($views * 1) + ($shares * 5) + ($downloads * 3) + ($clicks * 2);

        // Normalize to 0-100
        $maxScore = 500; // Theoretical max
        $normalized = min(100, ($score / $maxScore) * 100);

        // Update content
        $content->update(['engagement_score' => $normalized]);

        return round($normalized, 2);
    }

    /**
     * Helper: Get previous period count
     */
    private function getPreviousPeriodCount(User $user, ?int $teamId, Carbon $startDate, Carbon $endDate): int
    {
        $duration = $startDate->diffInDays($endDate);
        $previousStart = (clone $startDate)->subDays($duration);
        $previousEnd = clone $startDate;

        $query = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$previousStart, $previousEnd]);

        if ($teamId) {
            $query->where('team_workspace_id', $teamId);
        }

        return $query->count();
    }

    /**
     * Helper: Get previous period words
     */
    private function getPreviousPeriodWords(User $user, ?int $teamId, Carbon $startDate, Carbon $endDate): int
    {
        $duration = $startDate->diffInDays($endDate);
        $previousStart = (clone $startDate)->subDays($duration);
        $previousEnd = clone $startDate;

        $query = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$previousStart, $previousEnd]);

        if ($teamId) {
            $query->where('team_workspace_id', $teamId);
        }

        return $query->sum('word_count');
    }

    /**
     * Helper: Calculate growth percentage
     */
    private function calculateGrowth(float $current, float $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Helper: Get activity timeline
     */
    private function getActivityTimeline(User $user, ?int $teamId, Carbon $startDate, Carbon $endDate): array
    {
        $query = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($teamId) {
            $query->where('team_workspace_id', $teamId);
        }

        return $query->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    /**
     * Helper: Calculate performance score
     */
    private function calculatePerformanceScore(GeneratedContent $content, $actionCounts): float
    {
        $score = 0;

        // SEO score (40%)
        $score += ($content->seo_score ?? 0) * 0.4;

        // Engagement (30%)
        $engagementScore = ($content->engagement_score ?? 0) * 0.3;
        $score += $engagementScore;

        // Actions (30%)
        $actionScore = 0;
        $actionScore += $actionCounts->get('view', 0) * 0.5;
        $actionScore += $actionCounts->get('share', 0) * 5;
        $actionScore += $actionCounts->get('download', 0) * 3;
        $actionScore = min(30, $actionScore); // Cap at 30
        $score += $actionScore;

        return round(min(100, $score), 1);
    }
}
