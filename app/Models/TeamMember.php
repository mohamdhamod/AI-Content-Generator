<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    protected $fillable = [
        'team_workspace_id',
        'user_id',
        'invited_by',
        'role',
        'permissions',
        'status',
        'invitation_token',
        'pending_email',
        'invitation_expires_at',
        'invited_at',
        'joined_at',
        'last_active_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'invitation_expires_at' => 'datetime',
        'invited_at' => 'datetime',
        'joined_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    /**
     * Get the workspace.
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(TeamWorkspace::class, 'team_workspace_id');
    }

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get who invited this member.
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Accept invitation.
     */
    public function accept(): void
    {
        $this->update([
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $this->workspace->updateMemberCount();
    }

    /**
     * Suspend member.
     */
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    /**
     * Activate member.
     */
    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Update last active timestamp.
     */
    public function updateLastActive(): void
    {
        $this->update(['last_active_at' => now()]);
    }

    /**
     * Check if member is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if member is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if invitation is expired.
     */
    public function isInvitationExpired(): bool
    {
        if (!$this->invitation_expires_at) {
            return false;
        }
        return $this->invitation_expires_at->isPast();
    }

    /**
     * Generate invitation token.
     */
    public static function generateInvitationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Find by invitation token.
     */
    public static function findByToken(string $token): ?self
    {
        return static::where('invitation_token', $token)
            ->where('status', 'pending')
            ->first();
    }

    /**
     * Check if member has permission.
     */
    public function hasPermission(string $permission): bool
    {
        // Role-based permissions
        $rolePermissions = [
            'owner' => ['*'], // All permissions
            'admin' => ['create', 'edit', 'delete', 'publish', 'invite', 'manage_templates'],
            'editor' => ['create', 'edit', 'publish'],
            'reviewer' => ['view', 'comment', 'approve'],
            'viewer' => ['view'],
        ];

        $permissions = $rolePermissions[$this->role] ?? [];

        // Check if has all permissions
        if (in_array('*', $permissions)) {
            return true;
        }

        // Check specific permission
        if (in_array($permission, $permissions)) {
            return true;
        }

        // Check custom permissions
        if ($this->permissions && in_array($permission, $this->permissions)) {
            return true;
        }

        return false;
    }

    /**
     * Scope to filter active members.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter pending invitations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter by role.
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
