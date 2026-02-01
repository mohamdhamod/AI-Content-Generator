<?php

namespace App\Http\Controllers;

use App\Models\ContentAnalytics;
use App\Models\GeneratedContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Show analytics dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Date range filter (default: last 30 days)
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());
        
        // User's content performance
        $contentStats = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_contents,
                SUM(view_count) as total_views,
                SUM(share_count) as total_shares,
                SUM(pdf_download_count) as total_pdf_downloads,
                AVG(view_count) as avg_views_per_content
            ')
            ->first();
        
        // Most viewed content
        $topContent = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();
        
        // Content by type
        $contentByType = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('content_types', 'generated_contents.content_type_id', '=', 'content_types.id')
            ->selectRaw('content_types.name, COUNT(*) as count')
            ->groupBy('content_types.name')
            ->get();
        
        // Content by specialty
        $contentBySpecialty = GeneratedContent::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('specialties', 'generated_contents.specialty_id', '=', 'specialties.id')
            ->selectRaw('specialties.name, COUNT(*) as count')
            ->groupBy('specialties.name')
            ->get();
        
        // Action analytics
        $actionStats = ContentAnalytics::whereHas('generatedContent', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('action_type, COUNT(*) as count')
            ->groupBy('action_type')
            ->get()
            ->pluck('count', 'action_type');
        
        // Social platform preferences
        $platformStats = ContentAnalytics::whereHas('generatedContent', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('action_type', 'social_preview')
            ->whereNotNull('platform')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('platform, COUNT(*) as count')
            ->groupBy('platform')
            ->get()
            ->pluck('count', 'platform');
        
        // Daily activity (last 30 days)
        $dailyActivity = ContentAnalytics::whereHas('generatedContent', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        return view('analytics.index', compact(
            'contentStats',
            'topContent',
            'contentByType',
            'contentBySpecialty',
            'actionStats',
            'platformStats',
            'dailyActivity',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Get analytics data as JSON (for AJAX/API)
     */
    public function getData(Request $request)
    {
        $user = Auth::user();
        $metric = $request->get('metric', 'views');
        $period = $request->get('period', 30); // days
        
        $startDate = now()->subDays($period);
        
        $data = ContentAnalytics::whereHas('generatedContent', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
