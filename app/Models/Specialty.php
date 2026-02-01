<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Specialty extends Model implements TranslatableContract
{
    use Translatable;

    protected $fillable = [
        'key',
        'icon',
        'color',
        'active',
        'sort_order',
        'slug'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public array $translatedAttributes = [
        'name',
        'description',
    ];

    /**
     * Get the topics for this specialty.
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class)->ordered();
    }

    /**
     * Get the generated contents for this specialty.
     */
    public function generatedContents(): HasMany
    {
        return $this->hasMany(GeneratedContent::class);
    }

    /**
     * Get active topics for this specialty.
     */
    public function activeTopics(): HasMany
    {
        return $this->hasMany(Topic::class)->active()->ordered();
    }

    /**
     * Scope to get only active specialties.
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
     * Get localized topic names for the current locale.
     */
    public function getLocalizedTopicsAttribute(): array
    {
        return $this->activeTopics->pluck('name')->toArray();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function(Specialty $specialty){
            if (empty($specialty->slug)) {
                $base = $specialty->translateOrNew(app()->getLocale())->name ?? 'specialty-'.$specialty->id;
                $specialty->slug = Str::slug($base);
            }
        });
        static::saved(function(Specialty $specialty){
            if (empty($specialty->slug)) {
                $specialty->slug = 'specialty-'.$specialty->id;
                $specialty->saveQuietly();
            }
        });
    }
}
