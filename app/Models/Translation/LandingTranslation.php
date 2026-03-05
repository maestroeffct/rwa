<?php

namespace App\Models\Translation;


use Illuminate\Database\Eloquent\Model;

class LandingTranslation extends Model
{
    protected $table = 'landing_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

}
