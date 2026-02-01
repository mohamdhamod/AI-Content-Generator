<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionTranslation extends Model
{
    protected $table = 'subscription_translations';
    public $timestamps = false;
    protected $fillable = ['name','description',];
}
