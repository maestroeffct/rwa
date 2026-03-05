<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;

class EventSpeakerTranslation extends Model
{
    protected $table = 'event_speaker_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
