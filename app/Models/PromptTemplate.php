<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptTemplate extends Model
{
    protected $fillable = [
        'specialty_id',
        'content_type_id',
        'locale',
        'system_prompt',
        'user_prompt_template',
        'required_fields',
        'optional_fields',
        'active',
    ];

    protected $casts = [
        'required_fields' => 'array',
        'optional_fields' => 'array',
        'active' => 'boolean',
    ];

    /**
     * Get the specialty for this template.
     */
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Get the content type for this template.
     */
    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * Scope to get only active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to filter by locale.
     */
    public function scopeForLocale($query, string $locale = null)
    {
        return $query->where('locale', $locale ?? app()->getLocale());
    }

    /**
     * Get template for specific specialty and content type.
     */
    public static function findTemplate(?int $specialtyId, int $contentTypeId, string $locale = 'en'): ?self
    {
        // First try to find specialty-specific template
        $template = self::where('specialty_id', $specialtyId)
            ->where('content_type_id', $contentTypeId)
            ->where('locale', $locale)
            ->active()
            ->first();

        // If not found, try generic template (no specialty)
        if (!$template) {
            $template = self::whereNull('specialty_id')
                ->where('content_type_id', $contentTypeId)
                ->where('locale', $locale)
                ->active()
                ->first();
        }

        // Fallback to English if locale not found
        if (!$template && $locale !== 'en') {
            return self::findTemplate($specialtyId, $contentTypeId, 'en');
        }

        return $template;
    }
}
