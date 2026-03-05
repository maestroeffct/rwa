<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;

class ForumRecommendedTopicTranslation extends Model
{
    protected $table = 'forum_recommended_topic_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
