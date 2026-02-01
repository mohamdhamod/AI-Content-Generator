<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_id',
    ];

    /**
     * Get the user who favorited the content.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the favorited content.
     */
    public function content()
    {
        return $this->belongsTo(GeneratedContent::class, 'content_id');
    }
}
