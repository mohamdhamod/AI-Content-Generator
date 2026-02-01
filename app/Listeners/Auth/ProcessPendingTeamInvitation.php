<?php

namespace App\Listeners\Auth;

use App\Services\TeamCollaborationService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class ProcessPendingTeamInvitation
{
    protected TeamCollaborationService $teamService;

    public function __construct(TeamCollaborationService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Handle the event (works for both Login and Registered events)
     */
    public function handle(Login|Registered $event): void
    {
        $request = request();

        if (!$request->hasSession()) {
            return;
        }

        // Check for pending invitation token in session
        $token = $request->session()->pull('pending_team_invitation');

        if (!$token) {
            return;
        }

        try {
            $member = $this->teamService->acceptInvitationByToken($token, $event->user);
            
            Log::info('Processed pending team invitation after login', [
                'user_id' => $event->user->id,
                'workspace_id' => $member->team_workspace_id,
            ]);

            // Store a flash message to notify user
            $request->session()->flash('success', __('You have automatically joined the team you were invited to!'));
            
        } catch (\Exception $e) {
            Log::warning('Failed to process pending team invitation', [
                'user_id' => $event->user->id,
                'token' => substr($token, 0, 10) . '...',
                'error' => $e->getMessage(),
            ]);
        }

        // Also link any pending invitations by email for newly registered users
        if ($event instanceof Registered) {
            $linkedCount = $this->teamService->linkPendingInvitations($event->user);
            
            if ($linkedCount > 0) {
                Log::info('Linked pending invitations by email for new user', [
                    'user_id' => $event->user->id,
                    'count' => $linkedCount,
                ]);
            }
        }
    }
}
