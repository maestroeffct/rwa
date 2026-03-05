<?php

namespace App\Models;

use App\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class LandingComponent extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = "landing_components";
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['content'];


    public function getContentAttribute()
    {
        return getTranslateAttributeValue($this, 'content');
    }

    // #############
    // Relations
    // ############
    public function landingBuilderComponent()
    {
        return $this->belongsTo(LandingBuilderComponent::class, 'component_id', 'id');
    }

}
