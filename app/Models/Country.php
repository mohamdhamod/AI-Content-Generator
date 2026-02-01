<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Storage;
/**
 * @mixin Builder
 * @property string name
 * @property string phone_extension
 * @property boolean is_active
 */

class Country extends Model implements TranslatableContract
{
    use Translatable;

    public $guarded = ['id'];

    public $translatedAttributes = ['name'];
    public function users(){
        return $this->hasMany(User::class,'country_id');
    }

    /**
     * Get a public URL to the flag image if available.
     * Expected that the `flag` column stores a relative path like "flags/us.svg" or just filename "us.svg".
     */
    public function getFlagUrlAttribute(){
        return $this->flag != null ? asset('storage/'.$this->flag) : asset('images/flags/1x1/'.$this->code.'.svg');
    }
}
