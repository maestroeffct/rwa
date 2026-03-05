<?php

namespace App\Models;

use App\Models\Traits\CascadeDeletes;
use App\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Jorenvh\Share\ShareFacade;
use Spatie\CalendarLinks\Link;

class Event extends Model implements TranslatableContract
{
    use Translatable;
    use Sluggable;
    use CascadeDeletes;

    protected $table = 'events';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dateFormat = 'U';

    public $morphsFunctions = ['productBadgeContents', 'relatedCourses', 'specificLocation', 'deleteRequest', 'visits'];

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

    /* ==========
     | Translation
     * ==========*/
    public $translatedAttributes = ['title', 'subtitle', 'description', 'summary', 'seo_description'];

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

    public function getSummaryAttribute()
    {
        return getTranslateAttributeValue($this, 'summary');
    }

    public function getSeoDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'seo_description');
    }

    /* ==========
     | Relations
     * ==========*/
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function filterOptions()
    {
        return $this->hasMany(EventFilterOption::class, 'event_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id', 'id');
    }

    public function speakers()
    {
        return $this->hasMany(EventSpeaker::class, 'event_id', 'id');
    }

    public function productBadgeContents()
    {
        return $this->morphMany(ProductBadgeContent::class, 'targetable');
    }

    public function relatedCourses()
    {
        return $this->morphMany('App\Models\RelatedCourse', 'targetable');
    }

    public function prerequisites()
    {
        return $this->hasMany(Prerequisite::class, 'event_id', 'id');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'event_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'event_id', 'id');
    }

    public function extraDescriptions()
    {
        return $this->hasMany(WebinarExtraDescription::class, 'event_id', 'id');
    }

    public function specificLocation()
    {
        return $this->morphOne(SpecificLocation::class, 'targetable')->select(DB::raw('*, ST_AsText(geo_center) as geo_center'));
    }

    public function deleteRequest()
    {
        return $this->morphOne(ContentDeleteRequest::class, 'targetable');
    }

    public function reviews()
    {
        return $this->hasMany(WebinarReview::class, 'event_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'event_id', 'id');
    }

    public function visits()
    {
        return $this->morphMany(VisitLog::class, 'targetable');
    }

    public function session()
    {
        return $this->hasOne(Session::class, 'event_id', 'id');
    }

    /* ==========
     | Helpers
     * ==========*/
    public function getUrl()
    {
        return url("/events/{$this->slug}");
    }

    public function getIcon($useThumbnail = true)
    {
        $icon = $this->icon;

        if ($useThumbnail and empty($icon)) {
            $icon = $this->thumbnail;
        }

        return $icon;
    }

    public function getRate()
    {
        $rate = 0;

        if (!empty($this->avg_rates)) {
            $rate = $this->avg_rates;
        } else {
            $reviews = $this->reviews()
                ->where('status', 'active')
                ->get();

            if (!empty($reviews) and $reviews->count() > 0) {
                $rate = number_format($reviews->avg('rates'), 2);
            }
        }


        if ($rate > 5) {
            $rate = 5;
        }

        return $rate > 0 ? number_format($rate, 2) : 0;
    }

    public function getRateCount()
    {
        return $this->reviews()
            ->where('status', 'active')
            ->count();
    }

    public function getMinAndMaxPrice()
    {
        $min = 0;
        $max = 0;

        $tickets = $this->tickets()->where('enable', true)->get();

        foreach ($tickets as $ticket) {
            $price = $ticket->price;

            if ($min == 0 or $min > $price) {
                $min = $price;
            }

            if ($max == 0 or $max < $price) {
                $max = $price;
            }
        }

        if ($min > $max) {
            $max = $min;
        }

        return [
            'min' => $min,
            'max' => $max
        ];
    }

    public function getAllSales($buyer = null, $justCount = false)
    {
        $query = EventTicketSold::query()
            ->whereHas('eventTicket', function ($query) {
                $query->where('event_id', $this->id);
            })
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            });

        if (!empty($buyer)) {
            $query->where('user_id', $buyer->id);
        }

        if ($justCount) {
            return $query->count();
        }

        return $query->get();
    }

    public function getSoldTicketsCount()
    {
        return $this->getAllSales(null, true);
    }

    public function checkUserHasBought($user = null): bool
    {
        $hasBought = false;

        if (empty($user) and auth()->check()) {
            $user = auth()->user();
        }

        if (empty($user)) {
            $user = apiAuth();
        }

        if (!empty($user)) {
            $salesCount = $this->getAllSales($user, true);
            $hasBought = ($salesCount > 0);
        }

        return $hasBought;
    }

    public function getAllStudentsIds()
    {
        return $this->getAllSales()->pluck('user_id')->unique()->toArray();
    }

    public function getCapacityPercent()
    {
        $percent = 0;
        $salesCount = $this->getSoldTicketsCount();

        if ($salesCount > 0) {
            $percent = (!empty($this->capacity) and $this->capacity > 0) ? (($salesCount * 100) / $this->capacity) : 0;
        }

        if ($percent < 0) {
            $percent = 0;
        }

        if ($percent > 100) {
            $percent = 100;
        }

        return $percent;
    }

    public function getAvailableCapacity()
    {
        $result = null;

        if (!is_null($this->capacity)) {
            $salesCount = $this->getSoldTicketsCount();

            $result = $this->capacity - $salesCount;
        }

        if(!empty($this->sales_end_date) and $this->sales_end_date <= time()) {
            $result = 0;
        }

        if ($result < 0) {
            $result = 0;
        }

        return $result;
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

    public function addToCalendarLink()
    {

        $date = \DateTime::createFromFormat('j M Y H:i', dateTimeFormat($this->start_date, 'j M Y H:i', false));

        $link = Link::create($this->title, $date, $date); //->description('Cookies & cocktails!')

        return $link->google();
    }

    public function getShareLink($social)
    {
        $link = ShareFacade::page($this->getUrl(), $this->title)
            ->facebook()
            ->twitter()
            ->whatsapp()
            ->telegram()
            ->linkedin()
            ->getRawLinks();

        return !empty($link[$social]) ? $link[$social] : '';
    }

    public function getMaximumTicketsDiscount()
    {
        $tickets = $this->tickets()->where('enable', true)->get();

        $max = 0;
        foreach ($tickets as $ticket) {
            $getAvailableCapacity = $ticket->getAvailableCapacity();

            if ((is_null($getAvailableCapacity) or $getAvailableCapacity >= 0) and $ticket->hasDiscount()) {
                if ($max < $ticket->discount) {
                    $max = $ticket->discount;
                }
            }
        }

        return ($max > 0) ? $max : null;
    }

    public function checkIsSoldOutAllTickets()
    {
        $result = true;
        $tickets = $this->tickets()->where('enable', true)->get();

        foreach ($tickets as $ticket) {
            if (!$ticket->checkIsSoldOut()) {
                $result = false;
            }
        }

        return $result;
    }

    public function getCountdownTimes()
    {
        $times = null;

        if ($this->enable_countdown) {
            if ($this->countdown_time_reference == "sales_end_date") {
                $times = $this->sales_end_date;
            } else {
                $times = $this->start_date;
            }
        }

        $currentTime = time();

        return (!empty($times) and $times > $currentTime) ? ($times - $currentTime) : null;
    }

    public function getCardStatus()
    {
        $status = $this->status;
        $currentTime = time();

        if ($this->start_date > $currentTime) {
            $status = "scheduled";
        } elseif ($this->end_date < $currentTime) {
            $status = "ended";
        } else {
            $status = "ongoing";
        }
    }
}
