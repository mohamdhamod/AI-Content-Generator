<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TeamWorkspace extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'slug',
        'settings',
        'is_active',
        'plan',
        'member_limit',
        'storage_limit_mb',
        'member_count',
        'content_count',
        'template_count',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($workspace) {
            if (empty($workspace->slug)) {
                $workspace->slug = Str::slug($workspace->name) . '-' . Str::random(6);
            }
        });
    }

    /**
     * Get the owner.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get team members.
     */
    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Get active members.
     */
    public function activeMembers(): HasMany
    {
        return $this->hasMany(TeamMember::class)->where('status', 'active');
    }

    /**
     * Get workspace contents.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(GeneratedContent::class);
    }

    /**
     * Get workspace templates.
     */
    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }

    /**
     * Get content assignments.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(ContentAssignment::class);
    }

    /**
     * Check if user is member.
     */
    public function hasMember(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if user is owner.
     */
    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Check if user has role.
     */
    public function hasRole(User $user, string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return $this->members()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->whereIn('role', $roles)
            ->exists();
    }

    /**
     * Get member's role.
     */
    public function getMemberRole(User $user): ?string
    {
        $member = $this->members()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        return $member?->role;
    }

    /**
     * Add member to workspace.
     */
    public function addMember(User $user, string $role = 'viewer', ?User $invitedBy = null): TeamMember
    {
        $member = $this->members()->create([
            'user_id' => $user->id,
            'role' => $role,
            'invited_by' => $invitedBy?->id,
            'invited_at' => now(),
            'status' => 'pending',
        ]);

        return $member;
    }

    /**
     * Remove member from workspace.
     */
    public function removeMember(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->delete() > 0;
    }

    /**
     * Check if workspace is at member limit.
     */
    public function isAtMemberLimit(): bool
    {
        return $this->activeMembers()->count() >= $this->member_limit;
    }

    /**
     * Update member count.
     */
    public function updateMemberCount(): void
    {
        $this->update([
            'member_count' => $this->activeMembers()->count()
        ]);
    }

    /**
     * Update content count.
     */
    public function updateContentCount(): void
    {
        $this->update([
            'content_count' => $this->contents()->count()
        ]);
    }

    /**
     * Update template count.
     */
    public function updateTemplateCount(): void
    {
        $this->update([
            'template_count' => $this->templates()->count()
        ]);
    }

    /**
     * Scope to filter active workspaces.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by plan.
     */
    public function scopePlan($query, string $plan)
    {
        return $query->where('plan', $plan);
    }
}
