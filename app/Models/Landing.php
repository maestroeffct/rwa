<?php

namespace App\Models;

use App\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = "landings";
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];



    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    // #############
    // Relations
    // ############

    public function components()
    {
        return $this->hasMany(LandingComponent::class, 'landing_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(ThemeColorFont::class, 'color_id', 'id');
    }


    // #############
    // Helpers
    // ############
    public function getUrl()
    {
        return "/landings/{$this->url}";
    }
}
