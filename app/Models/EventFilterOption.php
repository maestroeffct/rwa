<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFilterOption extends Model
{
    protected $table = 'event_filter_options';
    public $timestamps = false;

    protected $guarded = ['id'];
}
