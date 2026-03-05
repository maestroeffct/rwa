<?php

namespace App\Models;

use App\Enums\MorphTypesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    protected $table = 'visits_logs';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


    /* ==========
     | Relations
     * ==========*/

    public function targetable()
    {
        return $this->morphTo();
    }

    public function webinar()
    {
        return $this->belongsTo(Webinar::class, 'targetable_id', 'id');
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'targetable_id', 'id');
    }

    public function upcomingCourse()
    {
        return $this->belongsTo(UpcomingCourse::class, 'targetable_id', 'id');
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'targetable_id', 'id');
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'targetable_id', 'id');
    }

    public function forumTopic()
    {
        return $this->belongsTo(ForumTopic::class, 'targetable_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'targetable_id', 'id');
    }

    // Use This On Models
    /*public function visits()
    {
        return $this->morphMany(VisitLog::class, 'targetable');
    }*/

    /* ==========
     | Helpers
     * ==========*/
    public function getItem()
    {
        $item = null;

        switch ($this->targetable_type) {
            case MorphTypesEnum::WEBINAR:
                $item = $this->webinar;
                break;

            case MorphTypesEnum::BUNDLE:
                $item = $this->bundle;
                break;

            case MorphTypesEnum::UPCOMING_COURSE:
                $item = $this->upcomingCourse;
                break;

            case MorphTypesEnum::BLOG:
                $item = $this->blog;
                break;

            case MorphTypesEnum::FORUM:
                $item = $this->forum;
                break;

            case MorphTypesEnum::FORUM_TOPIC:
                $item = $this->forumTopic;
                break;

            case MorphTypesEnum::PRODUCT:
                $item = $this->product;
                break;
        }

        return $item;
    }

    public function getItemTitle()
    {
        $title = "";

        $item = $this->getItem();

        if (!empty($item)) {
            $title = $item->title;
        }

        return $title;
    }

    public function getItemImage()
    {
        $path = null;

        $item = $this->getItem();

        if (!empty($item)) {
            switch ($this->targetable_type) {
                case MorphTypesEnum::WEBINAR:
                    $path = $item->thumbnail;
                    break;

                case MorphTypesEnum::BUNDLE:
                    $path = $item->thumbnail;
                    break;

                case MorphTypesEnum::UPCOMING_COURSE:
                    $path = $item->thumbnail;
                    break;

                case MorphTypesEnum::BLOG:
                    $path = $item->image;
                    break;

                case MorphTypesEnum::FORUM:
                    $path = $item->icon;
                    break;

                case MorphTypesEnum::FORUM_TOPIC:
                    if ($item->forum) {
                        $path = $item->forum->icon;
                    }
                    break;

                case MorphTypesEnum::PRODUCT:
                    $path = $item->thumbnail;
                    break;
            }
        }

        return $path;
    }

    public static function getQueryByOwner($user): Builder
    {
        $query = VisitLog::query();

        $query->where(function (Builder $query) use ($user) {

            // Webinars
            $userWebinarsIds = Webinar::query()
                ->where(function (Builder $query) use ($user) {
                    $query->where('webinars.creator_id', $user->id);
                    $query->orWhere('webinars.teacher_id', $user->id);
                })->pluck('id')->toArray();

            $query->where(function (Builder $query) use ($user, $userWebinarsIds) {
                $query->where('targetable_type', MorphTypesEnum::WEBINAR);
                $query->whereIn('targetable_id', $userWebinarsIds);
            });

            // Bundles
            $bundlesIds = Bundle::query()->where(function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
                $query->orWhere('creator_id', $user->id);
            })->pluck('id')->toArray();

            $query->orWhere(function (Builder $query) use ($bundlesIds) {
                $query->where('targetable_type', MorphTypesEnum::BUNDLE);
                $query->whereIn('targetable_id', $bundlesIds);
            });

            // UpcomingCourse
            $upcomingCourseIds = UpcomingCourse::query()->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->pluck('id')->toArray();

            $query->orWhere(function (Builder $query) use ($upcomingCourseIds) {
                $query->where('targetable_type', MorphTypesEnum::UPCOMING_COURSE);
                $query->whereIn('targetable_id', $upcomingCourseIds);
            });

            // Blog
            $query->orWhere(function (Builder $query) use ($user) {
                $query->where('targetable_type', MorphTypesEnum::BLOG);
                $query->where('owner_id', $user->id);
            });

            // Forum Topic
            $query->orWhere(function (Builder $query) use ($user) {
                $query->where('targetable_type', MorphTypesEnum::FORUM_TOPIC);
                $query->where('owner_id', $user->id);
            });

            // Product
            $query->orWhere(function (Builder $query) use ($user) {
                $query->where('targetable_type', MorphTypesEnum::PRODUCT);
                $query->where('owner_id', $user->id);
            });
        });

        return $query;
    }

}
