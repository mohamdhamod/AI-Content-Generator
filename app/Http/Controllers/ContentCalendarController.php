<?php

namespace App\Http\Controllers;

use App\Models\GeneratedContent;
use App\Services\ContentCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContentCalendarController extends Controller
{
    protected $calendarService;
    
    public function __construct(ContentCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }
    
    /**
     * Get calendar view
     */
    public function getCalendar(Request $request)
    {
        try {
            $filters = $request->validate([
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'nullable|in:draft,scheduled,published,archived',
                'platform' => 'nullable|string'
            ]);
            
            $result = $this->calendarService->getCalendarView(
                auth()->id(),
                $filters
            );
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Calendar view failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load calendar'
            ], 500);
        }
    }
    
    /**
     * Schedule content
     */
    public function scheduleContent(Request $request,$lang , $id)
    {
        $id = (int) $id;
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
                'scheduled_at' => 'required|date|after:now',
                'notes' => 'nullable|string|max:1000',
                'platforms' => 'nullable|array',
                'platforms.*' => 'string|in:facebook,twitter,linkedin,instagram,blog,website'
            ]);
            
            $result = $this->calendarService->scheduleContent($content, $validated);
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Content scheduling failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule content'
            ], 500);
        }
    }
    
    /**
     * Reschedule content
     */
    public function rescheduleContent(Request $request, $lang, $id)
    {
        $id = (int) $id;
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
                'scheduled_at' => 'required|date|after:now'
            ]);
            
            $result = $this->calendarService->rescheduleContent(
                $content,
                $validated['scheduled_at']
            );
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Content rescheduling failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reschedule content'
            ], 500);
        }
    }
    
    /**
     * Publish content
     */
    public function publishContent($id)
    {
        $id = (int) $id;
        try {
            $content = GeneratedContent::findOrFail($id);
            
            // Verify ownership
            if ($content->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            $result = $this->calendarService->publishContent($content);
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Content publishing failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish content'
            ], 500);
        }
    }
    
    /**
     * Archive content
     */
    public function archiveContent($id)
    {
        $id = (int) $id;
        try {
            $content = GeneratedContent::findOrFail($id);
            
            // Verify ownership
            if ($content->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            $result = $this->calendarService->archiveContent($content);
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Content archiving failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive content'
            ], 500);
        }
    }
    
    /**
     * Get upcoming content
     */
    public function getUpcoming(Request $request)
    {
        try {
            $days = $request->input('days', 7);
            
            $result = $this->calendarService->getUpcomingContent(
                auth()->id(),
                $days
            );
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Upcoming content retrieval failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load upcoming content'
            ], 500);
        }
    }
    
    /**
     * Get overdue content
     */
    public function getOverdue()
    {
        try {
            $result = $this->calendarService->getOverdueContent(auth()->id());
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Overdue content retrieval failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load overdue content'
            ], 500);
        }
    }
    
    /**
     * Batch schedule multiple contents
     */
    public function batchSchedule(Request $request, $lang)
    {
        try {
            $validated = $request->validate([
                'content_ids' => 'required|array|min:1',
                'content_ids.*' => 'required|integer|exists:generated_contents,id',
                'start_date' => 'required|date|after:now',
                'interval_hours' => 'nullable|integer|min:1|max:168',
                'platforms' => 'nullable|array',
                'platforms.*' => 'string|in:facebook,twitter,linkedin,instagram,blog,website',
                'notes' => 'nullable|string|max:1000'
            ]);
            
            // Verify all contents are owned by user
            $contents = GeneratedContent::whereIn('id', $validated['content_ids'])
                ->where('user_id', auth()->id())
                ->get();
            
            if ($contents->count() !== count($validated['content_ids'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some contents not found or unauthorized'
                ], 403);
            }
            
            $result = $this->calendarService->batchSchedule(
                $validated['content_ids'],
                $validated
            );
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Batch scheduling failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Batch scheduling failed'
            ], 500);
        }
    }
    
    /**
     * Update content publishing notes
     */
    public function updateNotes(Request $request , $lang, $id)
    {
        $id = (int) $id;
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
                'notes' => 'nullable|string|max:1000'
            ]);
            
            $content->update([
                'publishing_notes' => $validated['notes']
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notes updated successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Notes update failed', [
                'content_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update notes'
            ], 500);
        }
    }
}
