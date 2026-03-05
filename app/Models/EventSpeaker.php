<?php

namespace App\Models;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class EventSpeaker extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'event_speakers';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dateFormat = 'U';


    /* ==========
     | Translation
     * ==========*/
    public $translatedAttributes = ['name', 'job', 'description'];

    public function getNameAttribute()
    {
        return getTranslateAttributeValue($this, 'name');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function getJobAttribute()
    {
        return getTranslateAttributeValue($this, 'job');
    }

    /* ==========
     | Relations
     * ==========*/
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }




    /* ==========
     | Helpers
     * ==========*/


}
