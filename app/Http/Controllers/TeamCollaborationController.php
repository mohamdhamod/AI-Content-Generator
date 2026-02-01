<?php

namespace App\Http\Controllers;

use App\Services\TeamCollaborationService;
use App\Models\TeamWorkspace;
use App\Models\TeamMember;
use App\Models\ContentAssignment;
use App\Models\ContentComment;
use App\Models\GeneratedContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class TeamCollaborationController extends Controller
{
    private $teamService;

    public function __construct(TeamCollaborationService $teamService)
    {
        $this->teamService = $teamService;
        // Don't apply auth middleware to acceptInvitationByToken - it handles auth itself
        $this->middleware('auth')->except(['acceptInvitationByToken']);
    }

    /**
     * List user's teams
     * GET /teams
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $teams = TeamWorkspace::whereHas('members', function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('status', 'active');
        })->with('owner')->get();

        return response()->json([
            'success' => true,
            'data' => $teams->map(function($team) use ($user) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'slug' => $team->slug,
                    'plan' => $team->plan,
                    'role' => $team->getMemberRole($user),
                    'member_count' => $team->member_count,
                    'content_count' => $team->content_count,
                    'created_at' => $team->created_at->format('Y-m-d'),
                ];
            })
        ]);
    }

    /**
     * Create team workspace
     * POST /teams
     */
    public function store(Request $request)
    {
        // Rate limiting
        $key = 'create-team:' . $request->user()->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many teams created. Please wait.'
            ], 429);
        }
        RateLimiter::hit($key, 3600); // 1 hour

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'plan' => 'nullable|in:free,team,enterprise',
        ]);

        try {
            $workspace = $this->teamService->createWorkspace(
                $request->user(),
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Team workspace created successfully',
                'data' => [
                    'id' => $workspace->id,
                    'name' => $workspace->name,
                    'slug' => $workspace->slug,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create workspace: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View team workspace
     * GET /teams/{id}
     */
    public function show(Request $request, $lang , $id)
    {
        $workspace = TeamWorkspace::with(['owner', 'members.user'])->findOrFail($id);

        // Check access
        if (!$this->teamService->canAccessWorkspace($request->user(), $workspace)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $statistics = $this->teamService->getTeamStatistics($workspace);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $workspace->id,
                'name' => $workspace->name,
                'slug' => $workspace->slug,
                'description' => $workspace->description,
                'plan' => $workspace->plan,
                'owner' => [
                    'id' => $workspace->owner->id,
                    'name' => $workspace->owner->name,
                ],
                'members' => $workspace->members->map(function($member) {
                    return [
                        'id' => $member->id,
                        'user' => [
                            'id' => $member->user->id,
                            'name' => $member->user->name,
                            'email' => $member->user->email,
                        ],
                        'role' => $member->role,
                        'status' => $member->status,
                        'joined_at' => $member->joined_at?->format('Y-m-d'),
                    ];
                }),
                'statistics' => $statistics,
                'user_role' => $workspace->getMemberRole($request->user()),
            ]
        ]);
    }

    /**
     * Invite member to team
     * POST /teams/{id}/invite
     */
    public function inviteMember(Request $request, $lang , $id)
    {
        $workspace = TeamWorkspace::findOrFail($id);

        // Check permission
        if (!$this->teamService->hasPermission($request->user(), $workspace, 'invite')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - you do not have permission to invite members'
            ], 403);
        }

        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,editor,reviewer,viewer',
        ]);

        try {
            $member = $this->teamService->inviteMember(
                $workspace,
                $request->email,
                $request->role,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Invitation sent successfully',
                'data' => [
                    'member_id' => $member->id,
                    'status' => $member->status,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Accept team invitation
     * POST /invitations/{id}/accept
     */
    public function acceptInvitation($lang , $id)
    {
        $member = TeamMember::findOrFail($id);

        // Check if it's for current user
        if ($member->user_id !== request()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $this->teamService->acceptInvitation($member);

            return response()->json([
                'success' => true,
                'message' => 'Invitation accepted successfully',
                'data' => [
                    'workspace_id' => $member->team_workspace_id,
                    'role' => $member->role,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove member from team
     * DELETE /teams/{id}/members/{userId}
     */
    public function removeMember(Request $request, $lang , $id, $userId)
    {
        $workspace = TeamWorkspace::findOrFail($id);
        $user = \App\Models\User::findOrFail($userId);

        // Check permission
        if (!$this->teamService->hasPermission($request->user(), $workspace, 'manage_members')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $this->teamService->removeMember($workspace, $user);

            return response()->json([
                'success' => true,
                'message' => 'Member removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Assign content to member
     * POST /content/{id}/assign
     */
    public function assignContent(Request $request, $lang , $id)
    {
        $content = GeneratedContent::findOrFail($id);

        // Check if content is in team workspace
        if (!$content->team_workspace_id) {
            return response()->json([
                'success' => false,
                'message' => 'Content is not in a team workspace'
            ], 400);
        }

        // Check permission
        if (!$this->teamService->hasPermission($request->user(), $content->teamWorkspace, 'assign')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'nullable|date|after:now',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $assignee = \App\Models\User::findOrFail($request->assigned_to);

            $assignment = $this->teamService->assignContent(
                $content,
                $assignee,
                $request->user(),
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Content assigned successfully',
                'data' => [
                    'assignment_id' => $assignment->id,
                    'assigned_to' => $assignee->name,
                    'due_date' => $assignment->due_date,
                    'priority' => $assignment->priority,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update assignment status
     * PUT /assignments/{id}/status
     */
    public function updateAssignmentStatus(Request $request, $lang , $id)
    {
        $assignment = ContentAssignment::findOrFail($id);

        // Check if user is assigned or has permission
        if ($assignment->assigned_to !== $request->user()->id &&
            !$this->teamService->hasPermission($request->user(), $assignment->workspace, 'manage_assignments')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        try {
            $this->teamService->updateAssignmentStatus($assignment, $request->status);

            return response()->json([
                'success' => true,
                'message' => 'Assignment status updated successfully',
                'data' => [
                    'status' => $assignment->fresh()->status,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get comments for content
     * GET /content/{id}/comments
     */
    public function getComments(Request $request, $lang, $id)
    {
        $content = GeneratedContent::findOrFail($id);

        // Authorization - check if user can view this content's comments
        if ($content->user_id !== $request->user()->id && !$content->is_team_content) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $comments = ContentComment::where('generated_content_id', $content->id)
            ->whereNull('parent_comment_id') // Only top-level comments
            ->with(['user:id,name', 'replies.user:id,name'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user' => $comment->user,
                    'is_resolved' => $comment->is_resolved,
                    'annotated_text' => $comment->annotated_text,
                    'created_at_human' => $comment->created_at->diffForHumans(),
                    'replies' => $comment->replies->map(function($reply) {
                        return [
                            'id' => $reply->id,
                            'comment' => $reply->comment,
                            'user' => $reply->user,
                            'created_at_human' => $reply->created_at->diffForHumans(),
                        ];
                    })
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Add comment to content
     * POST /content/{id}/comments
     */
    public function addComment(Request $request, $lang , $id)
    {
        $content = GeneratedContent::findOrFail($id);

        // Authorization
        if ($content->user_id !== $request->user()->id && !$content->is_team_content) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:2000',
            'annotation' => 'nullable|array',
            'annotation.start' => 'required_with:annotation|integer',
            'annotation.end' => 'required_with:annotation|integer',
            'annotation.text' => 'required_with:annotation|string',
            'mentions' => 'nullable|array',
            'mentions.*' => 'exists:users,id',
        ]);

        try {
            $comment = $this->teamService->addComment(
                $content,
                $request->user(),
                $request->comment,
                $request->input('annotation'),
                null,
                $request->input('mentions')
            );

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => [
                    'comment_id' => $comment->id,
                    'user' => $request->user()->name,
                    'created_at' => $comment->created_at->diffForHumans(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reply to comment
     * POST /comments/{id}/reply
     */
    public function replyToComment(Request $request, $lang , $id)
    {
        $parentComment = ContentComment::findOrFail($id);

        $request->validate([
            'comment' => 'required|string|max:2000',
            'mentions' => 'nullable|array',
        ]);

        try {
            $reply = $this->teamService->replyToComment(
                $parentComment,
                $request->user(),
                $request->comment,
                $request->input('mentions')
            );

            return response()->json([
                'success' => true,
                'message' => 'Reply added successfully',
                'data' => [
                    'comment_id' => $reply->id,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Resolve comment
     * PUT /comments/{id}/resolve
     */
    public function resolveComment($lang , $id)
    {
        $comment = ContentComment::findOrFail($id);

        // Check permission
        $content = $comment->content;
        if ($content->user_id !== request()->user()->id &&
            !$this->teamService->hasPermission(request()->user(), $content->teamWorkspace, 'manage_comments')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $this->teamService->resolveComment($comment, request()->user());

            return response()->json([
                'success' => true,
                'message' => 'Comment resolved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get team activity feed
     * GET /teams/{id}/activity
     */
    public function getActivity(Request $request, $lang , $id)
    {
        $workspace = TeamWorkspace::findOrFail($id);

        // Check access
        if (!$this->teamService->canAccessWorkspace($request->user(), $workspace)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $filters = $request->only(['type', 'limit']);
        $activities = $this->teamService->getTeamActivity($workspace, $filters);

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Get user's tasks
     * GET /my-tasks
     */
    public function getMyTasks(Request $request)
    {
        $status = $request->input('status');
        $tasks = $this->teamService->getUserTasks($request->user(), $status);

        $tasksData = $tasks->map(function($task) {
            return [
                'id' => $task->id,
                'content' => [
                    'id' => $task->content->id,
                    'title' => $task->content->title ?? $task->content->input_data['topic'] ?? 'Untitled',
                ],
                'workspace' => $task->workspace->name ?? 'Unknown',
                'assigned_by' => $task->assigner->name ?? 'Unknown',
                'due_date' => $task->due_date?->format('M d, Y H:i'),
                'priority' => $task->priority,
                'status' => $task->status,
                'is_overdue' => $task->due_date ? $task->due_date->isPast() && $task->status !== 'completed' : false,
                'notes' => $task->notes,
                'created_at' => $task->created_at->format('M d, Y'),
            ];
        });

        // Return view for browser, JSON for AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tasksData
            ]);
        }

        return view('teams.my-tasks', [
            'tasks' => $tasksData,
            'statusFilter' => $status
        ]);
    }

    /**
     * Get team statistics
     * GET /teams/{id}/statistics
     */
    public function getStatistics(Request $request , $lang , $id)
    {
        $workspace = TeamWorkspace::findOrFail($id);

        // Check access
        if (!$this->teamService->canAccessWorkspace($request->user(), $workspace)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $statistics = $this->teamService->getTeamStatistics($workspace);

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Accept invitation by token
     * GET /teams/invitation/accept?token=xxx
     */
    public function acceptInvitationByToken(Request $request)
    {
        $token = $request->query('token');
        $locale = app()->getLocale() ?: 'en';

        if (!$token) {
            return redirect()->route('login', ['lang' => $locale])
                ->with('error', __('Invalid invitation link'));
        }

        // Find the invitation first
        $member = TeamMember::findByToken($token);

        if (!$member) {
            return redirect()->route('login', ['lang' => $locale])
                ->with('error', __('Invalid or expired invitation'));
        }

        if ($member->isInvitationExpired()) {
            return redirect()->route('login', ['lang' => $locale])
                ->with('error', __('This invitation has expired'));
        }

        // Check if user is logged in
        $user = $request->user();

        if (!$user) {
            // Store token in session and redirect to login/register
            session(['pending_team_invitation' => $token]);
            
            // If this is a pending email invitation (new user), redirect to register
            if ($member->pending_email) {
                return redirect()->route('register', ['lang' => $locale])
                    ->with('info', __('Please create an account to join the team'))
                    ->with('prefill_email', $member->pending_email);
            }
            
            // Existing user invitation - redirect to login
            return redirect()->route('login', ['lang' => $locale])
                ->with('info', __('Please login to accept the team invitation'));
        }

        try {
            $member = $this->teamService->acceptInvitationByToken($token, $user);

            return redirect()->route('teams.show', ['lang' => $locale, 'id' => $member->team_workspace_id])
                ->with('success', __('You have successfully joined the team!'));

        } catch (\Exception $e) {
            return redirect()->route('dashboard', ['lang' => $locale])
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Process pending invitation after login
     * Called after user logs in if they had a pending invitation
     */
    public function processPendingInvitation(Request $request)
    {
        $token = session('pending_team_invitation');

        if (!$token || !$request->user()) {
            return null;
        }

        // Clear the session
        session()->forget('pending_team_invitation');

        try {
            $member = $this->teamService->acceptInvitationByToken($token, $request->user());
            return $member;
        } catch (\Exception $e) {
            \Log::warning('Failed to process pending invitation', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get user's pending invitations
     * GET /teams/invitations/pending
     */
    public function getPendingInvitations(Request $request)
    {
        $user = $request->user();

        $invitations = TeamMember::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['workspace', 'inviter'])
            ->get()
            ->map(function($member) {
                return [
                    'id' => $member->id,
                    'workspace' => [
                        'id' => $member->workspace->id,
                        'name' => $member->workspace->name,
                    ],
                    'inviter' => [
                        'name' => $member->inviter?->name,
                    ],
                    'role' => $member->role,
                    'invited_at' => $member->invited_at?->diffForHumans(),
                    'expires_at' => $member->invitation_expires_at?->format('M d, Y'),
                    'is_expired' => $member->isInvitationExpired(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $invitations
        ]);
    }

    /**
     * Decline invitation
     * POST /teams/invitations/{id}/decline
     */
    public function declineInvitation(Request $request, $lang, $id)
    {
        $member = TeamMember::findOrFail($id);

        // Check if it's for current user
        if ($member->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized')
            ], 403);
        }

        if ($member->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('This invitation is not pending')
            ], 400);
        }

        $member->delete();

        return response()->json([
            'success' => true,
            'message' => __('Invitation declined')
        ]);
    }

    /**
     * Resend invitation
     * POST /teams/{teamId}/members/{memberId}/resend-invitation
     */
    public function resendInvitation(Request $request, $lang, $teamId, $memberId)
    {
        $workspace = TeamWorkspace::findOrFail($teamId);
        $member = TeamMember::where('id', $memberId)
            ->where('team_workspace_id', $workspace->id)
            ->firstOrFail();

        // Check permission
        if (!$this->teamService->hasPermission($request->user(), $workspace, 'invite')) {
            return response()->json([
                'success' => false,
                'message' => __('Unauthorized')
            ], 403);
        }

        if ($member->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('Member is not pending')
            ], 400);
        }

        // Get email
        $email = $member->user ? $member->user->email : $member->pending_email;

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => __('No email found for this invitation')
            ], 400);
        }

        // Re-invite (this will update token and send email)
        try {
            $this->teamService->inviteMember(
                $workspace,
                $email,
                $member->role,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'message' => __('Invitation resent successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
