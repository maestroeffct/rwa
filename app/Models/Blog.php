<?php

namespace App\Models;

use App\Models\Traits\CascadeDeletes;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Jorenvh\Share\ShareFacade;

class Blog extends Model implements TranslatableContract
{
    use Translatable;
    use Sluggable;
    use CascadeDeletes;

    protected $table = 'blog';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $morphsFunctions = ['productBadgeContents', 'deleteRequest', 'relatedPosts'];
    public $translatedAttributes = ['title', 'subtitle', 'description', 'meta_description', 'content'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
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

    public function category()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'category_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'blog_id', 'id');
    }

    public function deleteRequest()
    {
        return $this->morphOne(ContentDeleteRequest::class, 'targetable');
    }

    public function productBadgeContents()
    {
        return $this->morphMany(ProductBadgeContent::class, 'targetable');
    }

    public function relatedPosts()
    {
        return $this->morphMany(RelatedPost::class, 'targetable');
    }

    public function visits()
    {
        return $this->morphMany(VisitLog::class, 'targetable');
    }


    public function getUrl()
    {
        return '/blog/' . $this->slug;
    }

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getSubtitleAttribute()
    {
        return getTranslateAttributeValue($this, 'subtitle');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function getMetaDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'meta_description');
    }

    public function getContentAttribute()
    {
        return getTranslateAttributeValue($this, 'content');
    }

    public function getShareLink($social)
    {
        $link = ShareFacade::page(url($this->getUrl()), $this->title)
            ->facebook()
            ->twitter()
            ->whatsapp()
            ->telegram()
            ->linkedin()
            ->getRawLinks();

        return !empty($link[$social]) ? $link[$social] : '';
    }

    public function allBadges()
    {
        $badges = collect();

        $productBadgeContents = $this->productBadgeContents()
            ->whereHas('badge', function ($query) {
                $query->where('enable', true);
            })
            ->get();

        foreach ($productBadgeContents as $productBadgeContent) {
            $badge = $productBadgeContent->badge;

            if ($badge->isActive()) {
                $badges->push($productBadgeContent->badge);
            }
        }

        return $badges;
    }
}
