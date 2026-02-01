<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ContentType extends Model implements TranslatableContract
{
    use Translatable;

    protected $fillable = [
        'key',
        'icon',
        'color',
        'active',
        'sort_order',
        'credits_cost',
        'slug',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public array $translatedAttributes = [
        'name',
        'description',
        'placeholder',
    ];

    /**
     * Get prompt templates for this content type.
     */
    public function promptTemplates(): HasMany
    {
        return $this->hasMany(PromptTemplate::class);
    }

    /**
     * Get generated contents of this type.
     */
    public function generatedContents(): HasMany
    {
        return $this->hasMany(GeneratedContent::class);
    }

    /**
     * Scope to get only active content types.
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function(ContentType $contentType){
            if (empty($contentType->slug)) {
                $base = $contentType->translateOrNew(app()->getLocale())->name ?? 'content-type-'.$contentType->id;
                $contentType->slug = Str::slug($base);
            }
        });
        static::saved(function(ContentType $contentType){
            if (empty($contentType->slug)) {
                $contentType->slug = 'content-type-'.$contentType->id;
                $contentType->saveQuietly();
            }
        });
    }
}
