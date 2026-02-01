<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentAssignment extends Model
{
    protected $fillable = [
        'generated_content_id',
        'team_workspace_id',
        'assigned_to',
        'assigned_by',
        'notes',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Get the content.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(GeneratedContent::class, 'generated_content_id');
    }

    /**
     * Get the workspace.
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(TeamWorkspace::class, 'team_workspace_id');
    }

    /**
     * Get assigned user.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get who assigned.
     */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Mark as in progress.
     */
    public function markInProgress(): void
    {
        $this->update(['status' => 'in_progress']);
    }

    /**
     * Mark as completed.
     */
    public function markCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Mark as cancelled.
     */
    public function markCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Check if overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter overdue.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('status', '!=', 'completed');
    }
}
