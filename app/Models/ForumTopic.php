<?php

namespace App\Models;

use App\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ForumTopic extends Model
{
    use Sluggable;

    protected $table = 'forum_topics';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function makeSlug($title)
    {
        return SlugService::createSlug(self::class, 'slug', $title);
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function forum()
    {
        return $this->belongsTo('App\Models\Forum', 'forum_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\ForumTopicAttachment', 'topic_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\ForumTopicLike', 'topic_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\ForumTopicPost', 'topic_id', 'id');
    }

    public function visits()
    {
        return $this->morphMany(VisitLog::class, 'targetable');
    }


    public function getPostsUrl()
    {
        return "/forums/{$this->forum->slug}/topics/{$this->slug}/posts";
    }

    public function getLikeUrl()
    {
        return "/forums/{$this->forum->slug}/topics/{$this->slug}/likeToggle";
    }

    public function getBookmarkUrl()
    {
        return "/forums/{$this->forum->slug}/topics/{$this->slug}/bookmark";
    }

    public function getEditUrl()
    {
        return "/forums/{$this->forum->slug}/topics/{$this->slug}/edit";
    }

    public function getParticipatesUsers($count = 6)
    {
        $userIdsQuery = ForumTopicPost::query()->select('user_id', DB::raw("count(user_id) as user_post_count"))
            ->where('topic_id', $this->id)
            ->groupBy('user_id')
            ->orderBy('user_post_count', 'desc');

        $total = $userIdsQuery->get()->count();

        if (!empty($count)) {
            $userIdsQuery->limit($count);
        }

        $userIds = $userIdsQuery->pluck('user_id')->toArray();

        $query = User::query();
        $query->select('id', 'role_id', 'role_name', 'full_name', 'mobile', 'email', 'avatar', 'avatar_settings', 'created_at');
        $query->whereIn('id', $userIds);
        $activeUsers = $query->get();


        return [
            'users' => $activeUsers,
            'count' => $total
        ];
    }
}
