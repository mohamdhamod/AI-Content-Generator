<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model implements TranslatableContract
{
    use Translatable;

    protected $fillable = [
        'specialty_id',
        'icon',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public array $translatedAttributes = [
        'name',
        'description',
        'prompt_hint', // AI context enhancement for this topic
    ];

    /**
     * Get the specialty that owns this topic.
     */
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Scope to get only active topics.
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
     * Get the prompt context for AI generation.
     * This combines name, description, and prompt_hint for richer context.
     */
    public function getPromptContext(): string
    {
        $context = "Topic: {$this->name}";
        
        if ($this->description) {
            $context .= "\nTopic Description: {$this->description}";
        }
        
        if ($this->prompt_hint) {
            $context .= "\nSpecific Guidelines: {$this->prompt_hint}";
        }
        
        return $context;
    }
}
