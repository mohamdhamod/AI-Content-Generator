<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens , HasFactory, HasRoles , Notifiable;
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFirstNameAttribute()
    {
        return explode(' ', $this->name)[0];
    }

    public function getFullNameAttribute()
    {
        return $this->name ;

    }
    protected $appends = ['full_path','full_name','first_name',];
    public function getFullPathAttribute(){
        return $this->image != null ? asset('storage/'.$this->image) : asset('images/img.png');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }

    public function setPasswordAttribute($pass){
        $this->attributes['password'] = Hash::make($pass);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'user_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class, 'user_id')
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function contentGenerations()
    {
        return $this->hasMany(ContentGeneration::class, 'user_id');
    }

    /**
     * Get user's favorite contents.
     */
    public function favoriteContents()
    {
        return $this->belongsToMany(GeneratedContent::class, 'content_favorites', 'user_id', 'content_id')
            ->withTimestamps()
            ->orderByDesc('content_favorites.created_at');
    }

    /**
     * Get user's favorites relationship.
     */
    public function contentFavorites()
    {
        return $this->hasMany(ContentFavorite::class, 'user_id');
    }

    /**
     * Get the number of content generations this month.
     */
    public function getMonthlyGenerationsCount(): int
    {
        return $this->contentGenerations()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Check if user can generate more content.
     */
    public function canGenerateContent(): bool
    {
        $subscription = $this->activeSubscription;
        
        if (!$subscription) {
            return false;
        }

        $plan = $subscription->subscription;
        
        // Unlimited plan
        if ($plan && $plan->max_content_generations == -1) {
            return true;
        }

        $monthlyUsage = $this->getMonthlyGenerationsCount();
        $maxAllowed = $plan?->max_content_generations ?? 0;

        return $monthlyUsage < $maxAllowed;
    }
}
