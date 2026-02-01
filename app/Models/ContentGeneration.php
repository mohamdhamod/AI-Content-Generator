<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentGeneration extends Model
{
    protected $fillable = [
        'user_id',
        'user_subscription_id',
        'content_type',
        'specialty',
        'language',
        'topic',
        'prompt',
        'generated_content',
        'tokens_used',
        'model_used',
        'status',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'tokens_used' => 'integer',
    ];

    /**
     * Get the user that owns the content generation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user subscription associated with this generation.
     */
    public function userSubscription()
    {
        return $this->belongsTo(UserSubscription::class);
    }

    /**
     * Scope for completed generations.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for this month's generations.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Scope for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
