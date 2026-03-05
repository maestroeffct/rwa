<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ForumRecommendedTopic extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'forum_recommended_topics';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'subtitle'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getSubtitleAttribute()
    {
        return getTranslateAttributeValue($this, 'subtitle');
    }


    public function topics()
    {
        return $this->belongsToMany('App\Models\ForumTopic', 'forum_recommended_topic_items',
            'recommended_topic_id', 'topic_id');
    }
}
