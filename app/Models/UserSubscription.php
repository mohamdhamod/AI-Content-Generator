<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'digistore_order_id',
        'digistore_product_id',
        'digistore_affiliate_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'started_at',
        'expires_at',
        'cancelled_at',
        'ipn_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'ipn_data' => 'array',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the subscription plan.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    /**
     * Check if the subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at > now();
    }

    /**
     * Check if the subscription is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    /**
     * Check if the subscription is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Scope for active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope for expired subscriptions.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for pending subscriptions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
