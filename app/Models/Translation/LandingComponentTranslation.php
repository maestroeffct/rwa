<?php

namespace App\Models\Translation;


use Illuminate\Database\Eloquent\Model;

class LandingComponentTranslation extends Model
{
    protected $table = 'landing_component_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

}
