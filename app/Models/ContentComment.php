<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentComment extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'generated_content_id',
        'user_id',
        'parent_comment_id',
        'comment',
        'mentions',
        'annotation_start',
        'annotation_end',
        'annotated_text',
        'is_resolved',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'mentions' => 'array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the content.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(GeneratedContent::class, 'generated_content_id');
    }

    /**
     * Get the user who commented.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get parent comment.
     */
    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(ContentComment::class, 'parent_comment_id');
    }

    /**
     * Get replies.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ContentComment::class, 'parent_comment_id');
    }

    /**
     * Get who resolved.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Resolve comment.
     */
    public function resolve(User $user): void
    {
        $this->update([
            'is_resolved' => true,
            'resolved_by' => $user->id,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Unresolve comment.
     */
    public function unresolve(): void
    {
        $this->update([
            'is_resolved' => false,
            'resolved_by' => null,
            'resolved_at' => null,
        ]);
    }

    /**
     * Check if comment is reply.
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_comment_id);
    }

    /**
     * Check if comment is annotation.
     */
    public function isAnnotation(): bool
    {
        return !is_null($this->annotation_start);
    }

    /**
     * Scope to filter unresolved comments.
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope to filter resolved comments.
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    /**
     * Scope to filter top-level comments (not replies).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_comment_id');
    }
}
