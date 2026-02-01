<?php

namespace App\Services;

use App\Models\GeneratedContent;
use App\Models\ContentAnalytics;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContentCalendarService
{
    /**
     * Schedule content for publishing
     */
    public function scheduleContent(GeneratedContent $content, array $data): array
    {
        try {
            $scheduledAt = Carbon::parse($data['scheduled_at']);
            
            // Validate scheduling time
            if ($scheduledAt->isPast()) {
                throw new \Exception('Cannot schedule content in the past');
            }
            
            // Update content with scheduling info
            $content->update([
                'publishing_status' => 'scheduled',
                'scheduled_at' => $scheduledAt,
                'publishing_notes' => $data['notes'] ?? null,
                'published_platforms' => $data['platforms'] ?? []
            ]);
            
            // Track analytics
            ContentAnalytics::track(
                $content->id,
                'schedule_publish',
                null,
                [
                    'scheduled_at' => $scheduledAt->toDateTimeString(),
                    'platforms' => $data['platforms'] ?? []
                ]
            );
            
            return [
                'success' => true,
                'message' => 'Content scheduled successfully',
                'data' => [
                    'id' => $content->id,
                    'scheduled_at' => $scheduledAt->toDateTimeString(),
                    'status' => 'scheduled'
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Content scheduling failed', [
                'content_id' => $content->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to schedule content: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get calendar view data
     */
    public function getCalendarView(int $userId, array $filters = []): array
    {
        try {
            $startDate = isset($filters['start_date']) 
                ? Carbon::parse($filters['start_date'])->startOfDay()
                : Carbon::now()->startOfMonth();
                
            $endDate = isset($filters['end_date'])
                ? Carbon::parse($filters['end_date'])->endOfDay()
                : Carbon::now()->endOfMonth();
            
            $query = GeneratedContent::where('user_id', $userId)
                ->whereBetween('scheduled_at', [$startDate, $endDate]);
            
            // Apply status filter
            if (isset($filters['status'])) {
                $query->where('publishing_status', $filters['status']);
            }
            
            // Apply platform filter
            if (isset($filters['platform'])) {
                $query->whereJsonContains('published_platforms', $filters['platform']);
            }
            
            $contents = $query->orderBy('scheduled_at', 'asc')
                ->with(['user', 'specialty', 'analytics'])
                ->get();
            
            // Group by date
            $calendarData = $contents->groupBy(function ($content) {
                return $content->scheduled_at 
                    ? Carbon::parse($content->scheduled_at)->format('Y-m-d')
                    : null;
            })->map(function ($items, $date) {
                return [
                    'date' => $date,
                    'contents' => $items->map(function ($content) {
                        return [
                            'id' => $content->id,
                            'title' => $content->title ?? $content->specialty->name,
                            'specialty' => $content->specialty->name,
                            'status' => $content->publishing_status,
                            'scheduled_at' => $content->scheduled_at,
                            'platforms' => $content->published_platforms ?? [],
                            'view_count' => $content->view_count,
                            'share_count' => $content->share_count,
                        ];
                    })->values()->toArray()
                ];
            })->values()->toArray();
            
            // Calculate statistics
            $stats = $this->calculateCalendarStats($contents);
            
            return [
                'success' => true,
                'data' => [
                    'calendar' => $calendarData,
                    'statistics' => $stats,
                    'period' => [
                        'start' => $startDate->toDateString(),
                        'end' => $endDate->toDateString()
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Calendar view generation failed', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to load calendar: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Publish scheduled content
     */
    public function publishContent(GeneratedContent $content): array
    {
        try {
            if ($content->publishing_status !== 'scheduled') {
                throw new \Exception('Content is not in scheduled status');
            }
            
            $content->update([
                'publishing_status' => 'published',
                'is_published' => true,
                'published_at' => now()
            ]);
            
            // Track analytics
            ContentAnalytics::track(
                $content->id,
                'publish',
                null,
                [
                    'scheduled_at' => $content->scheduled_at,
                    'published_at' => now()->toDateTimeString(),
                    'platforms' => $content->published_platforms ?? []
                ]
            );
            
            return [
                'success' => true,
                'message' => 'Content published successfully',
                'data' => [
                    'id' => $content->id,
                    'published_at' => now()->toDateTimeString(),
                    'status' => 'published'
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Content publishing failed', [
                'content_id' => $content->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to publish content: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Reschedule content
     */
    public function rescheduleContent(GeneratedContent $content, string $newDateTime): array
    {
        try {
            $newScheduledAt = Carbon::parse($newDateTime);
            
            if ($newScheduledAt->isPast()) {
                throw new \Exception('Cannot reschedule content in the past');
            }
            
            $oldScheduledAt = $content->scheduled_at;
            
            $content->update([
                'scheduled_at' => $newScheduledAt,
                'publishing_status' => 'scheduled'
            ]);
            
            // Track analytics
            ContentAnalytics::track(
                $content->id,
                'reschedule',
                null,
                [
                    'old_scheduled_at' => $oldScheduledAt,
                    'new_scheduled_at' => $newScheduledAt->toDateTimeString()
                ]
            );
            
            return [
                'success' => true,
                'message' => 'Content rescheduled successfully',
                'data' => [
                    'id' => $content->id,
                    'scheduled_at' => $newScheduledAt->toDateTimeString(),
                    'status' => 'scheduled'
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Content rescheduling failed', [
                'content_id' => $content->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to reschedule content: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Archive content
     */
    public function archiveContent(GeneratedContent $content): array
    {
        try {
            $content->update([
                'publishing_status' => 'archived'
            ]);
            
            // Track analytics
            ContentAnalytics::track($content->id, 'archive');
            
            return [
                'success' => true,
                'message' => 'Content archived successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Content archiving failed', [
                'content_id' => $content->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to archive content: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get upcoming scheduled content
     */
    public function getUpcomingContent(int $userId, int $days = 7): array
    {
        try {
            $endDate = Carbon::now()->addDays($days);
            
            $contents = GeneratedContent::where('user_id', $userId)
                ->where('publishing_status', 'scheduled')
                ->whereBetween('scheduled_at', [now(), $endDate])
                ->orderBy('scheduled_at', 'asc')
                ->with(['specialty'])
                ->get();
            
            $grouped = $contents->groupBy(function ($content) {
                return Carbon::parse($content->scheduled_at)->format('Y-m-d');
            })->map(function ($items, $date) {
                return [
                    'date' => $date,
                    'day_name' => Carbon::parse($date)->format('l'),
                    'count' => $items->count(),
                    'contents' => $items->map(function ($content) {
                        return [
                            'id' => $content->id,
                            'title' => $content->title ?? $content->specialty->name,
                            'specialty' => $content->specialty->name,
                            'scheduled_at' => Carbon::parse($content->scheduled_at)->format('H:i'),
                            'platforms' => $content->published_platforms ?? []
                        ];
                    })->values()
                ];
            })->values();
            
            return [
                'success' => true,
                'data' => $grouped
            ];
        } catch (\Exception $e) {
            Log::error('Upcoming content retrieval failed', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to load upcoming content: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get overdue scheduled content
     */
    public function getOverdueContent(int $userId): array
    {
        try {
            $contents = GeneratedContent::where('user_id', $userId)
                ->where('publishing_status', 'scheduled')
                ->where('scheduled_at', '<', now())
                ->orderBy('scheduled_at', 'asc')
                ->with(['specialty'])
                ->get();
            
            return [
                'success' => true,
                'data' => [
                    'count' => $contents->count(),
                    'contents' => $contents->map(function ($content) {
                        return [
                            'id' => $content->id,
                            'title' => $content->title ?? $content->specialty->name,
                            'specialty' => $content->specialty->name,
                            'scheduled_at' => $content->scheduled_at,
                            'overdue_hours' => Carbon::parse($content->scheduled_at)->diffInHours(now()),
                            'platforms' => $content->published_platforms ?? []
                        ];
                    })->values()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Overdue content retrieval failed', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to load overdue content: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Calculate calendar statistics
     */
    private function calculateCalendarStats($contents): array
    {
        return [
            'total' => $contents->count(),
            'by_status' => [
                'draft' => $contents->where('publishing_status', 'draft')->count(),
                'scheduled' => $contents->where('publishing_status', 'scheduled')->count(),
                'published' => $contents->where('publishing_status', 'published')->count(),
                'archived' => $contents->where('publishing_status', 'archived')->count(),
            ],
            'total_views' => $contents->sum('view_count'),
            'total_shares' => $contents->sum('share_count'),
            'avg_engagement' => $contents->count() > 0 
                ? round(($contents->sum('view_count') + $contents->sum('share_count')) / $contents->count(), 2)
                : 0
        ];
    }
    
    /**
     * Batch schedule multiple contents
     */
    public function batchSchedule(array $contentIds, array $scheduleData): array
    {
        DB::beginTransaction();
        
        try {
            $results = [
                'success' => [],
                'failed' => []
            ];
            
            foreach ($contentIds as $index => $contentId) {
                $content = GeneratedContent::find($contentId);
                
                if (!$content) {
                    $results['failed'][] = [
                        'id' => $contentId,
                        'reason' => 'Content not found'
                    ];
                    continue;
                }
                
                // Calculate scheduled time based on interval
                $scheduledAt = Carbon::parse($scheduleData['start_date'])
                    ->addHours($index * ($scheduleData['interval_hours'] ?? 24));
                
                $result = $this->scheduleContent($content, [
                    'scheduled_at' => $scheduledAt,
                    'notes' => $scheduleData['notes'] ?? null,
                    'platforms' => $scheduleData['platforms'] ?? []
                ]);
                
                if ($result['success']) {
                    $results['success'][] = $result['data'];
                } else {
                    $results['failed'][] = [
                        'id' => $contentId,
                        'reason' => $result['message']
                    ];
                }
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'data' => $results,
                'message' => sprintf(
                    'Scheduled %d contents, %d failed',
                    count($results['success']),
                    count($results['failed'])
                )
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Batch scheduling failed', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Batch scheduling failed: ' . $e->getMessage()
            ];
        }
    }
}
