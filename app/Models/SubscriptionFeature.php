<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionFeature extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'subscription_features';

    protected $fillable = [
        'subscription_id',
        'icon',
        'is_highlighted',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'is_highlighted' => 'boolean',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public array $translatedAttributes = [
        'feature_text',
    ];

    /**
     * Get the subscription that owns this feature.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Scope to get only active features.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope to get highlighted features.
     */
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlighted', true);
    }
}
