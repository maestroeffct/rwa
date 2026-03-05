<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    protected $table = 'prerequisites';
    public $timestamps = false;
    protected $guarded = ['id'];



    public function course()
    {
        return $this->belongsTo('App\Models\Webinar', 'prerequisite_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

}
