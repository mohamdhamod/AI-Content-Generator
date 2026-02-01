<?php

namespace App\Services;

use App\Models\TeamWorkspace;
use App\Models\TeamMember;
use App\Models\ContentAssignment;
use App\Models\ContentComment;
use App\Models\GeneratedContent;
use App\Models\User;
use App\Mail\TeamInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class TeamCollaborationService
{
    /**
     * Create new team workspace
     */
    public function createWorkspace(User $owner, array $data): TeamWorkspace
    {
        $workspace = TeamWorkspace::create([
            'owner_id' => $owner->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'plan' => $data['plan'] ?? 'free',
            'settings' => [
                'allow_public_templates' => $data['allow_public_templates'] ?? false,
                'require_approval' => $data['require_approval'] ?? true,
                'enable_comments' => $data['enable_comments'] ?? true,
            ],
        ]);

        // Add owner as member
        $this->addMember($workspace, $owner, 'owner', $owner);

        return $workspace;
    }

    /**
     * Invite member to team (supports both registered and non-registered users)
     */
    public function inviteMember(
        TeamWorkspace $workspace,
        string $email,
        string $role,
        User $inviter
    ): TeamMember {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(__('Invalid email address'));
        }

        // Check if user exists in the system
        $user = User::where('email', $email)->first();
        $isNewUser = !$user;

        // Check if already member (only for existing users)
        if ($user) {
            $existing = TeamMember::where('team_workspace_id', $workspace->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                if ($existing->status === 'active') {
                    throw new \Exception(__('User is already an active member'));
                }
                
                // Resend invitation with new token
                $token = TeamMember::generateInvitationToken();
                $existing->update([
                    'status' => 'pending',
                    'role' => $role,
                    'invited_by' => $inviter->id,
                    'invited_at' => now(),
                    'invitation_token' => $token,
                    'invitation_expires_at' => now()->addDays(7),
                ]);

                // Send invitation email
                $this->sendInvitationEmail($workspace, $inviter, $email, $role, false, $token);

                return $existing;
            }
        }

        // Check if pending invitation exists for this email (non-registered user)
        $existingPending = TeamMember::where('team_workspace_id', $workspace->id)
            ->where('pending_email', $email)
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            // Resend invitation with new token
            $token = TeamMember::generateInvitationToken();
            $existingPending->update([
                'role' => $role,
                'invited_by' => $inviter->id,
                'invited_at' => now(),
                'invitation_token' => $token,
                'invitation_expires_at' => now()->addDays(7),
            ]);

            // Send invitation email
            $this->sendInvitationEmail($workspace, $inviter, $email, $role, true, $token);

            return $existingPending;
        }

        // Generate invitation token
        $token = TeamMember::generateInvitationToken();

        // Create invitation record
        $memberData = [
            'team_workspace_id' => $workspace->id,
            'role' => $role,
            'status' => 'pending',
            'invited_by' => $inviter->id,
            'invited_at' => now(),
            'invitation_token' => $token,
            'invitation_expires_at' => now()->addDays(7),
        ];

        if ($user) {
            // Existing user - link directly
            $memberData['user_id'] = $user->id;
        } else {
            // New user - store pending email
            $memberData['pending_email'] = $email;
        }

        $member = TeamMember::create($memberData);

        // Send invitation email
        $this->sendInvitationEmail($workspace, $inviter, $email, $role, $isNewUser, $token);

        return $member;
    }

    /**
     * Send invitation email
     */
    protected function sendInvitationEmail(
        TeamWorkspace $workspace,
        User $inviter,
        string $email,
        string $role,
        bool $isNewUser,
        string $token
    ): void {
        try {
            Mail::to($email)->send(new TeamInvitationMail(
                $workspace,
                $inviter,
                $email,
                $role,
                $isNewUser,
                $token
            ));

            Log::info('Team invitation email sent', [
                'workspace_id' => $workspace->id,
                'email' => $email,
                'is_new_user' => $isNewUser,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send team invitation email', [
                'workspace_id' => $workspace->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            // Don't throw - invitation is created, email failure shouldn't block
        }
    }

    /**
     * Accept invitation by token
     */
    public function acceptInvitationByToken(string $token, ?User $user = null): TeamMember
    {
        $member = TeamMember::findByToken($token);

        if (!$member) {
            throw new \Exception(__('Invalid or expired invitation'));
        }

        if ($member->isInvitationExpired()) {
            throw new \Exception(__('This invitation has expired'));
        }

        // If this is a pending email invitation, we need a user
        if (!$member->user_id && !$user) {
            throw new \Exception(__('User must be logged in to accept invitation'));
        }

        // If user is provided and member has pending_email, link them
        if ($user && $member->pending_email) {
            // Verify email matches
            if (strtolower($user->email) !== strtolower($member->pending_email)) {
                throw new \Exception(__('Email does not match invitation'));
            }
            $member->user_id = $user->id;
        }

        // If member already has user_id, verify it matches
        if ($member->user_id && $user && $member->user_id !== $user->id) {
            throw new \Exception(__('This invitation is for another user'));
        }

        // Accept the invitation
        $member->status = 'active';
        $member->joined_at = now();
        $member->invitation_token = null;
        $member->pending_email = null;
        $member->invitation_expires_at = null;
        $member->save();

        // Update workspace member count
        $member->workspace->updateMemberCount();

        return $member;
    }

    /**
     * Get pending invitation by email (for registration flow)
     */
    public function getPendingInvitationByEmail(string $email): ?TeamMember
    {
        return TeamMember::where('pending_email', $email)
            ->where('status', 'pending')
            ->whereDate('invitation_expires_at', '>=', now())
            ->first();
    }

    /**
     * Link pending invitations to newly registered user
     */
    public function linkPendingInvitations(User $user): int
    {
        $pendingInvitations = TeamMember::where('pending_email', $user->email)
            ->where('status', 'pending')
            ->get();

        $count = 0;
        foreach ($pendingInvitations as $member) {
            $member->user_id = $user->id;
            $member->pending_email = null;
            $member->save();
            $count++;
        }

        return $count;
    }

    /**
     * Add member to team (direct add without invitation)
     */
    public function addMember(
        TeamWorkspace $workspace,
        User $user,
        string $role,
        User $addedBy
    ): TeamMember {
        return TeamMember::create([
            'team_workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'role' => $role,
            'status' => 'active',
            'invited_by' => $addedBy->id,
            'invited_at' => now(),
            'joined_at' => now(),
        ]);
    }

    /**
     * Accept team invitation
     */
    public function acceptInvitation(TeamMember $member): void
    {
        if ($member->status !== 'pending') {
            throw new \Exception('Invitation is not pending');
        }

        $member->accept();
    }

    /**
     * Remove member from team
     */
    public function removeMember(TeamWorkspace $workspace, User $user): bool
    {
        $member = TeamMember::where('team_workspace_id', $workspace->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$member) {
            throw new \Exception('Member not found');
        }

        if ($member->role === 'owner') {
            throw new \Exception('Cannot remove workspace owner');
        }

        $member->delete();
        $workspace->updateMemberCount();

        return true;
    }

    /**
     * Update member role
     */
    public function updateMemberRole(TeamMember $member, string $newRole): void
    {
        if ($member->role === 'owner') {
            throw new \Exception('Cannot change owner role');
        }

        $member->update(['role' => $newRole]);
    }

    /**
     * Assign content to member
     */
    public function assignContent(
        GeneratedContent $content,
        User $assignee,
        User $assigner,
        array $data
    ): ContentAssignment {
        // Verify team membership
        if (!$content->teamWorkspace) {
            throw new \Exception('Content is not in a team workspace');
        }

        $workspace = $content->teamWorkspace;

        if (!$workspace->hasMember($assignee)) {
            throw new \Exception('Assignee is not a team member');
        }

        // Create assignment
        $assignment = ContentAssignment::create([
            'generated_content_id' => $content->id,
            'team_workspace_id' => $workspace->id,
            'assigned_to' => $assignee->id,
            'assigned_by' => $assigner->id,
            'due_date' => $data['due_date'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        // Send notification (would implement)
        // Notification::send($assignee, new ContentAssignedNotification($assignment));

        return $assignment;
    }

    /**
     * Update assignment status
     */
    public function updateAssignmentStatus(ContentAssignment $assignment, string $status): void
    {
        $validStatuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Invalid status');
        }

        switch ($status) {
            case 'in_progress':
                $assignment->markInProgress();
                break;
            case 'completed':
                $assignment->markCompleted();
                break;
            case 'cancelled':
                $assignment->markCancelled();
                break;
            default:
                $assignment->update(['status' => $status]);
        }
    }

    /**
     * Add comment to content
     */
    public function addComment(
        GeneratedContent $content,
        User $user,
        string $commentText,
        ?array $annotation = null,
        ?int $parentCommentId = null,
        ?array $mentions = null
    ): ContentComment {
        $comment = ContentComment::create([
            'generated_content_id' => $content->id,
            'user_id' => $user->id,
            'comment' => $commentText,
            'parent_comment_id' => $parentCommentId,
            'annotation_start' => $annotation['start'] ?? null,
            'annotation_end' => $annotation['end'] ?? null,
            'annotated_text' => $annotation['text'] ?? null,
            'mentions' => $mentions,
        ]);

        // Send notifications to mentioned users
        if ($mentions && count($mentions) > 0) {
            $mentionedUsers = User::whereIn('id', $mentions)->get();
            // Notification::send($mentionedUsers, new MentionedInCommentNotification($comment));
        }

        return $comment;
    }

    /**
     * Reply to comment
     */
    public function replyToComment(
        ContentComment $parentComment,
        User $user,
        string $commentText,
        ?array $mentions = null
    ): ContentComment {
        return $this->addComment(
            $parentComment->content,
            $user,
            $commentText,
            null,
            $parentComment->id,
            $mentions
        );
    }

    /**
     * Resolve comment
     */
    public function resolveComment(ContentComment $comment, User $user): void
    {
        $comment->resolve($user);

        // Notify comment author
        // Notification::send($comment->user, new CommentResolvedNotification($comment));
    }

    /**
     * Unresolve comment
     */
    public function unresolveComment(ContentComment $comment): void
    {
        $comment->unresolve();
    }

    /**
     * Get team activity feed
     */
    public function getTeamActivity(TeamWorkspace $workspace, array $filters = []): Collection
    {
        $limit = $filters['limit'] ?? 50;
        $type = $filters['type'] ?? 'all'; // all, content, comment, assignment

        $activities = collect();

        // Get recent contents
        if (in_array($type, ['all', 'content'])) {
            $contents = $workspace->contents()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($content) {
                    return [
                        'type' => 'content_created',
                        'user' => $content->user->name,
                        'content' => $content->title,
                        'timestamp' => $content->created_at,
                    ];
                });
            $activities = $activities->merge($contents);
        }

        // Get recent comments
        if (in_array($type, ['all', 'comment'])) {
            $comments = ContentComment::whereHas('content', function($q) use ($workspace) {
                $q->where('team_workspace_id', $workspace->id);
            })
            ->with(['user', 'content'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($comment) {
                return [
                    'type' => 'comment_added',
                    'user' => $comment->user->name,
                    'content' => $comment->content->title,
                    'comment' => Str::limit($comment->comment, 100),
                    'timestamp' => $comment->created_at,
                ];
            });
            $activities = $activities->merge($comments);
        }

        // Get recent assignments
        if (in_array($type, ['all', 'assignment'])) {
            $assignments = $workspace->assignments()
                ->with(['assignedUser', 'content', 'assigner'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($assignment) {
                    return [
                        'type' => 'content_assigned',
                        'user' => $assignment->assigner->name,
                        'assigned_to' => $assignment->assignedUser->name,
                        'content' => $assignment->content->title,
                        'priority' => $assignment->priority,
                        'timestamp' => $assignment->created_at,
                    ];
                });
            $activities = $activities->merge($assignments);
        }

        // Sort by timestamp and limit
        return $activities->sortByDesc('timestamp')->take($limit);
    }

    /**
     * Get user's tasks
     */
    public function getUserTasks(User $user, ?string $status = null): Collection
    {
        $query = ContentAssignment::where('assigned_to', $user->id)
            ->with(['content', 'workspace', 'assigner']);

        if ($status) {
            $query->status($status);
        }

        return $query->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->get();
    }

    /**
     * Get overdue tasks for team
     */
    public function getOverdueTasks(TeamWorkspace $workspace): Collection
    {
        return ContentAssignment::where('team_workspace_id', $workspace->id)
            ->overdue()
            ->with(['assignedUser', 'content'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
     * Get unresolved comments for content
     */
    public function getUnresolvedComments(GeneratedContent $content): Collection
    {
        return ContentComment::where('generated_content_id', $content->id)
            ->unresolved()
            ->with(['user', 'replies'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Transfer workspace ownership
     */
    public function transferOwnership(TeamWorkspace $workspace, User $newOwner): void
    {
        // Check if new owner is a member
        $newOwnerMember = TeamMember::where('team_workspace_id', $workspace->id)
            ->where('user_id', $newOwner->id)
            ->where('status', 'active')
            ->first();

        if (!$newOwnerMember) {
            throw new \Exception('New owner must be an active team member');
        }

        // Get current owner
        $currentOwnerMember = TeamMember::where('team_workspace_id', $workspace->id)
            ->where('role', 'owner')
            ->first();

        // Update roles
        $newOwnerMember->update(['role' => 'owner']);
        $currentOwnerMember->update(['role' => 'admin']);

        // Update workspace owner
        $workspace->update(['owner_id' => $newOwner->id]);
    }

    /**
     * Get team statistics
     */
    public function getTeamStatistics(TeamWorkspace $workspace): array
    {
        return [
            'members' => [
                'total' => $workspace->members()->count(),
                'active' => $workspace->members()->where('status', 'active')->count(),
                'pending' => $workspace->members()->where('status', 'pending')->count(),
            ],
            'content' => [
                'total' => $workspace->contents()->count(),
                'published' => $workspace->contents()->where('publishing_status', 'published')->count(),
                'drafts' => $workspace->contents()->where('publishing_status', 'draft')->count(),
            ],
            'assignments' => [
                'total' => $workspace->assignments()->count(),
                'pending' => $workspace->assignments()->where('status', 'pending')->count(),
                'in_progress' => $workspace->assignments()->where('status', 'in_progress')->count(),
                'completed' => $workspace->assignments()->where('status', 'completed')->count(),
                'overdue' => $workspace->assignments()->overdue()->count(),
            ],
            'comments' => [
                'total' => ContentComment::whereHas('content', function($q) use ($workspace) {
                    $q->where('team_workspace_id', $workspace->id);
                })->count(),
                'unresolved' => ContentComment::whereHas('content', function($q) use ($workspace) {
                    $q->where('team_workspace_id', $workspace->id);
                })->unresolved()->count(),
            ],
            'templates' => [
                'total' => $workspace->templates()->count(),
                'usage_count' => $workspace->templates()->sum('usage_count'),
            ],
        ];
    }

    /**
     * Check if user can access workspace
     */
    public function canAccessWorkspace(User $user, TeamWorkspace $workspace): bool
    {
        return $workspace->hasMember($user);
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(User $user, TeamWorkspace $workspace, string $permission): bool
    {
        $member = TeamMember::where('team_workspace_id', $workspace->id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$member) {
            return false;
        }

        return $member->hasPermission($permission);
    }
}
