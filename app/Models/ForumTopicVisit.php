<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumTopicVisit extends Model
{
    protected $table = 'forum_topic_visits';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
