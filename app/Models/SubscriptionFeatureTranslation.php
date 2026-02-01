<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionFeatureTranslation extends Model
{
    protected $table = 'sub_feature_translations';

    protected $fillable = [
        'feature_text',
    ];

    public $timestamps = true;
}
