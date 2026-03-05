<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;

class EventTicketTranslation extends Model
{
    protected $table = 'event_ticket_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
