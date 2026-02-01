<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedContent extends Model
{
    protected $fillable = [
        'user_id',
        'specialty_id',
        'topic_id',
        'content_type_id',
        'input_data',
        'output_text',
        'language',
        'country',
        'word_count',
        'credits_used',
        'tokens_used',
        'status',
        'error_message',
        'review_status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'version',
        'parent_content_id',
        'is_published',
        'published_at',
        'view_count',
        'share_count',
        'pdf_download_count',
        // Phase 3: SEO & Calendar
        'publishing_status',
        'scheduled_at',
        'publishing_notes',
        'seo_title',
        'seo_meta_description',
        'seo_focus_keyword',
        'seo_score_data',
        'seo_overall_score',
        'published_platforms',
        'last_seo_check',
        // Phase 4: Multilingual, Templates, Analytics, Teams
        'translations',
        'source_language',
        'translation_languages',
        'translation_quality_scores',
        'template_id',
        'template_variables',
        'team_workspace_id',
        'is_team_content',
        'engagement_score',
        'conversion_rate',
        'click_count',
    ];

    protected $casts = [
        'input_data' => 'array',
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'scheduled_at' => 'datetime',
        'seo_score_data' => 'array',
        'published_platforms' => 'array',
        'last_seo_check' => 'datetime',
        // Phase 4 casts
        'translations' => 'array',
        'translation_languages' => 'array',
        'translation_quality_scores' => 'array',
        'template_variables' => 'array',
        'is_team_content' => 'boolean',
        'conversion_rate' => 'decimal:2',
    ];

    /**
     * Get the user who generated this content.
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
     * Get the topic.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the content type.
     */
    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * Get the favorites for this content.
     */
    public function favorites()
    {
        return $this->hasMany(ContentFavorite::class, 'content_id');
    }

    /**
     * Get the reviewer who reviewed this content.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the parent content (for version history).
     */
    public function parentContent(): BelongsTo
    {
        return $this->belongsTo(GeneratedContent::class, 'parent_content_id');
    }

    /**
     * Get child versions of this content.
     */
    public function childVersions()
    {
        return $this->hasMany(GeneratedContent::class, 'parent_content_id');
    }

    /**
     * Get analytics entries for this content.
     */
    public function analytics()
    {
        return $this->hasMany(ContentAnalytics::class);
    }

    /**
     * Get the template used for this content (Phase 4).
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the team workspace this content belongs to (Phase 4).
     */
    public function teamWorkspace(): BelongsTo
    {
        return $this->belongsTo(TeamWorkspace::class);
    }

    /**
     * Get assignments for this content (Phase 4).
     */
    public function assignments()
    {
        return $this->hasMany(ContentAssignment::class);
    }

    /**
     * Get comments for this content (Phase 4).
     */
    public function comments()
    {
        return $this->hasMany(ContentComment::class);
    }

    /**
     * Check if content is favorited by a specific user.
     */
    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * Check if content needs medical review.
     */
    public function needsReview(): bool
    {
        return $this->review_status === 'draft' || $this->review_status === 'rejected';
    }

    /**
     * Check if content is approved for publishing.
     */
    public function isApproved(): bool
    {
        return $this->review_status === 'approved';
    }

    /**
     * Submit content for review.
     */
    public function submitForReview(): void
    {
        $this->update(['review_status' => 'pending_review']);
        ContentAnalytics::track($this->id, 'submit_for_review');
    }

    /**
     * Approve content.
     */
    public function approve(int $reviewerId, ?string $notes = null): void
    {
        $this->update([
            'review_status' => 'approved',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes,
            'is_published' => true,
            'published_at' => now(),
        ]);
        ContentAnalytics::track($this->id, 'approve');
    }

    /**
     * Reject content.
     */
    public function reject(int $reviewerId, string $notes): void
    {
        $this->update([
            'review_status' => 'rejected',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);
        ContentAnalytics::track($this->id, 'reject');
    }

    /**
     * Increment view count and track.
     */
    public function incrementViews(): void
    {
        $this->increment('view_count');
        ContentAnalytics::track($this->id, 'view');
    }

    /**
     * Increment share count and track.
     */
    public function incrementShares(?string $platform = null): void
    {
        $this->increment('share_count');
        ContentAnalytics::track($this->id, 'share', $platform);
    }

    /**
     * Increment PDF download count and track.
     */
    public function incrementPdfDownloads(): void
    {
        $this->increment('pdf_download_count');
        ContentAnalytics::track($this->id, 'pdf_download');
    }

    /**
     * Check if content is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->publishing_status === 'scheduled';
    }

    /**
     * Check if content is published.
     */
    public function isPublished(): bool
    {
        return $this->publishing_status === 'published';
    }

    /**
     * Check if scheduled date is in the past.
     */
    public function isOverdue(): bool
    {
        return $this->isScheduled() 
            && $this->scheduled_at 
            && $this->scheduled_at->isPast();
    }

    /**
     * Get SEO score grade.
     */
    public function getSeoGrade(): string
    {
        if (!$this->seo_overall_score) {
            return 'N/A';
        }

        if ($this->seo_overall_score >= 90) return 'A';
        if ($this->seo_overall_score >= 80) return 'B';
        if ($this->seo_overall_score >= 70) return 'C';
        if ($this->seo_overall_score >= 60) return 'D';
        return 'F';
    }

    /**
     * Check if SEO check is stale (older than 7 days).
     */
    public function isSeoStale(): bool
    {
        if (!$this->last_seo_check) {
            return true;
        }

        return $this->last_seo_check->diffInDays(now()) > 7;
    }

    /**
     * Scope to filter by user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get word count from output.
     */
    public function calculateWordCount(): int
    {
        return str_word_count(strip_tags($this->output_text));
    }
}
