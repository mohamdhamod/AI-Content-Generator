<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model implements TranslatableContract
{
    use Translatable;
    protected $table = 'subscriptions';
    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'price',
        'currency',
        'duration_months',
        'max_content_generations',
        'digistore_product_id',
        'digistore_checkout_url',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_months' => 'integer',
        'max_content_generations' => 'integer',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all user subscriptions for this plan.
     */
    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'subscription_id');
    }

    /**
     * Get all features for this subscription plan.
     */
    public function features(): HasMany
    {
        return $this->hasMany(SubscriptionFeature::class)->ordered();
    }

    /**
     * Get active features for this subscription plan.
     */
    public function activeFeatures(): HasMany
    {
        return $this->hasMany(SubscriptionFeature::class)->active()->ordered();
    }

    /**
     * Scope for active subscription plans.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the Digistore24 checkout URL with user email.
     */
    public function getCheckoutUrl(?string $email = null): string
    {
        $url = $this->digistore_checkout_url ?? 'https://www.digistore24.com/product/' . $this->digistore_product_id;
        
        if ($email) {
            $url .= (str_contains($url, '?') ? '&' : '?') . 'email=' . urlencode($email);
        }
        
        return $url;
    }
}
