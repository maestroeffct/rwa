<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;

class EventTranslation extends Model
{
    protected $table = 'event_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
