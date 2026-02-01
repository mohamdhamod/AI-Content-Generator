<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentAnalytics extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'generated_content_id',
        'user_id',
        'action_type',
        'platform',
        'device_type',
        'browser',
        'os',
        'country_code',
        'city',
        'metadata',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the content this analytics entry belongs to
     */
    public function generatedContent(): BelongsTo
    {
        return $this->belongsTo(GeneratedContent::class);
    }

    /**
     * Get the user who performed this action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Track an action
     */
    public static function track(
        int $contentId,
        string $actionType,
        ?string $platform = null,
        array $metadata = []
    ): self {
        return self::create([
            'generated_content_id' => $contentId,
            'user_id' => auth()->id(),
            'action_type' => $actionType,
            'platform' => $platform,
            'device_type' => request()->header('Sec-CH-UA-Mobile') ? 'mobile' : 'desktop',
            'browser' => request()->header('User-Agent'),
            'ip_address' => request()->ip(),
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
