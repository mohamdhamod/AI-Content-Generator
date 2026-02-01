<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GeneratedContent;
use App\Models\Specialty;
use App\Models\ContentType;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\PermissionEnum;

/**
 * Admin Dashboard Controller
 * 
 * Professional dashboard with advanced analytics
 * Following global platform standards (Stripe, Vercel, Linear)
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::SETTING_VIEW);
    }

    public function index()
    {
        // Basic Statistics
        $statistics = $this->getBasicStatistics();
        
        // Growth Statistics (compared to last month)
        $growthStats = $this->getGrowthStatistics();
        
        // Chart Data - Last 30 days content generation
        $contentChartData = $this->getContentChartData();
        
        // Chart Data - Last 12 months revenue/subscriptions
        $subscriptionChartData = $this->getSubscriptionChartData();
        
        // Top Content Types
        $topContentTypes = $this->getTopContentTypes();
        
        // Top Specialties
        $topSpecialties = $this->getTopSpecialties();
        
        // Recent Contents
        $recentContents = GeneratedContent::with(['user', 'contentType', 'specialty'])
            ->latest()
            ->take(8)
            ->get();
        
        // Recent Users
        $recentUsers = User::latest()
            ->take(5)
            ->get();
        
        // System Health
        $systemHealth = $this->getSystemHealth();
        
        // Activity Timeline
        $activityTimeline = $this->getActivityTimeline();

        return view('dashboard.index', compact(
            'statistics',
            'growthStats',
            'contentChartData',
            'subscriptionChartData',
            'topContentTypes',
            'topSpecialties',
            'recentContents',
            'recentUsers',
            'systemHealth',
            'activityTimeline'
        ));
    }

    /**
     * Get basic statistics
     */
    protected function getBasicStatistics(): array
    {
        return [
            'users' => User::count(),
            'subscriptions' => UserSubscription::where('status', 'active')->count(),
            'contents' => GeneratedContent::count(),
            'specialties' => Specialty::where('active', true)->count(),
            'content_types' => ContentType::where('active', true)->count(),
            'today_contents' => GeneratedContent::whereDate('created_at', today())->count(),
            'week_contents' => GeneratedContent::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month_contents' => GeneratedContent::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];
    }

    /**
     * Get growth statistics compared to last period
     */
    protected function getGrowthStatistics(): array
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        
        // Users growth
        $usersThisMonth = User::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $usersLastMonth = User::whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $usersGrowth = $usersLastMonth > 0 ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1) : 100;
        
        // Contents growth
        $contentsThisMonth = GeneratedContent::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $contentsLastMonth = GeneratedContent::whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $contentsGrowth = $contentsLastMonth > 0 ? round((($contentsThisMonth - $contentsLastMonth) / $contentsLastMonth) * 100, 1) : 100;
        
        // Subscriptions growth
        $subsThisMonth = UserSubscription::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $subsLastMonth = UserSubscription::whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $subsGrowth = $subsLastMonth > 0 ? round((($subsThisMonth - $subsLastMonth) / $subsLastMonth) * 100, 1) : 100;
        
        return [
            'users' => ['current' => $usersThisMonth, 'previous' => $usersLastMonth, 'growth' => $usersGrowth],
            'contents' => ['current' => $contentsThisMonth, 'previous' => $contentsLastMonth, 'growth' => $contentsGrowth],
            'subscriptions' => ['current' => $subsThisMonth, 'previous' => $subsLastMonth, 'growth' => $subsGrowth],
        ];
    }

    /**
     * Get content generation chart data for last 30 days
     */
    protected function getContentChartData(): array
    {
        $data = GeneratedContent::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $values = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $values[] = $data[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /**
     * Get subscription chart data for last 12 months
     */
    protected function getSubscriptionChartData(): array
    {
        $data = UserSubscription::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $values = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $found = $data->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            
            $values[] = $found ? $found->count : 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * Get top content types by usage
     */
    protected function getTopContentTypes(): \Illuminate\Support\Collection
    {
        return ContentType::withCount('generatedContents')
            ->where('active', true)
            ->orderByDesc('generated_contents_count')
            ->take(5)
            ->get();
    }

    /**
     * Get top specialties by usage
     */
    protected function getTopSpecialties(): \Illuminate\Support\Collection
    {
        return Specialty::withCount('generatedContents')
            ->where('active', true)
            ->orderByDesc('generated_contents_count')
            ->take(5)
            ->get();
    }

    /**
     * Get system health metrics
     */
    protected function getSystemHealth(): array
    {
        $avgResponseTime = 0.8; // Placeholder - can be calculated from logs
        $successRate = 98.5; // Placeholder
        
        return [
            'api_status' => 'operational',
            'database_status' => 'operational',
            'queue_status' => 'operational',
            'avg_response_time' => $avgResponseTime,
            'success_rate' => $successRate,
            'last_error' => null,
        ];
    }

    /**
     * Get recent activity timeline
     */
    protected function getActivityTimeline(): \Illuminate\Support\Collection
    {
        $activities = collect();
        
        // Recent contents
        $recentContents = GeneratedContent::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($content) {
                return [
                    'type' => 'content',
                    'icon' => 'bi-file-earmark-text',
                    'color' => 'info',
                    'message' => ($content->user->name ?? 'User') . ' generated new content',
                    'time' => $content->created_at,
                ];
            });
        
        // Recent users
        $recentUsers = User::latest()
            ->take(3)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'icon' => 'bi-person-plus',
                    'color' => 'success',
                    'message' => $user->name . ' joined the platform',
                    'time' => $user->created_at,
                ];
            });
        
        // Recent subscriptions
        $recentSubs = UserSubscription::with(['user', 'subscription'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($sub) {
                return [
                    'type' => 'subscription',
                    'icon' => 'bi-credit-card',
                    'color' => 'warning',
                    'message' => ($sub->user->name ?? 'User') . ' subscribed to ' . ($sub->subscription->name ?? 'a plan'),
                    'time' => $sub->created_at,
                ];
            });
        
        return $activities
            ->merge($recentContents)
            ->merge($recentUsers)
            ->merge($recentSubs)
            ->sortByDesc('time')
            ->take(8)
            ->values();
    }
}
