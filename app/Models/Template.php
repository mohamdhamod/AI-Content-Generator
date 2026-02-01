<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'specialty_id',
        'content_type_id',
        'name',
        'description',
        'template_content',
        'variables',
        'language',
        'visibility',
        'is_active',
        'usage_count',
        'last_used_at',
        'version',
        'parent_template_id',
        'team_workspace_id',
        'allow_team_edit',
        'metadata',
    ];

    protected $casts = [
        'variables' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'allow_team_edit' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get the user who created the template.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the specialty.
     */
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Get the content type.
     */
    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * Get the team workspace.
     */
    public function teamWorkspace(): BelongsTo
    {
        return $this->belongsTo(TeamWorkspace::class);
    }

    /**
     * Get parent template (for versioning).
     */
    public function parentTemplate(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'parent_template_id');
    }

    /**
     * Get child versions.
     */
    public function childVersions(): HasMany
    {
        return $this->hasMany(Template::class, 'parent_template_id');
    }

    /**
     * Get contents created from this template.
     */
    public function generatedContents(): HasMany
    {
        return $this->hasMany(GeneratedContent::class);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Check if template is public.
     */
    public function isPublic(): bool
    {
        return $this->visibility === 'public';
    }

    /**
     * Check if template is accessible by team.
     */
    public function isTeamAccessible(): bool
    {
        return in_array($this->visibility, ['team', 'public']);
    }

    /**
     * Check if user can edit template.
     */
    public function canEdit(User $user): bool
    {
        // Owner can always edit
        if ($this->user_id === $user->id) {
            return true;
        }

        // Team members can edit if allowed
        if ($this->allow_team_edit && $this->team_workspace_id) {
            return $this->teamWorkspace
                ->members()
                ->where('user_id', $user->id)
                ->whereIn('role', ['owner', 'admin', 'editor'])
                ->where('status', 'active')
                ->exists();
        }

        return false;
    }

    /**
     * Extract variables from template content.
     */
    public function extractVariables(): array
    {
        preg_match_all('/\{\{(\w+)\}\}/', $this->template_content, $matches);
        return array_unique($matches[1]);
    }

    /**
     * Replace variables in template.
     */
    public function renderTemplate(array $variables): string
    {
        $content = $this->template_content;
        
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }

    /**
     * Scope to filter active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by visibility.
     */
    public function scopeVisibility($query, string $visibility)
    {
        return $query->where('visibility', $visibility);
    }

    /**
     * Scope to filter user's templates.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter team templates.
     */
    public function scopeForTeam($query, int $teamWorkspaceId)
    {
        return $query->where('team_workspace_id', $teamWorkspaceId)
            ->where('visibility', 'team');
    }

    /**
     * Scope to search templates.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }
}
