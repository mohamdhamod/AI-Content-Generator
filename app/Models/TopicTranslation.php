<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicTranslation extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
    ];
}
