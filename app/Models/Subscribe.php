<?php

namespace App\Models;

use App\Mixins\Installment\InstallmentPlans;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Subscribe extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'subscribes';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'subtitle', 'description'];

    // Enums
    static $targetTypes = ['all', 'courses', 'bundles'];
    static $courseTargets = ['all_courses', 'live_classes', 'video_courses', 'text_courses', 'specific_categories', 'specific_instructors', 'specific_courses'];
    static $bundleTargets = ['all_bundles', 'specific_categories', 'specific_instructors', 'specific_bundles'];


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

    /* ==========
     | Relations
     * ==========*/

    public function sales()
    {
        return $this->hasMany('App\Models\Sale', 'subscribe_id', 'id');
    }

    public function uses()
    {
        return $this->hasMany('App\Models\SubscribeUse', 'subscribe_id', 'id');
    }

    public function specificationItems() // used just in query
    {
        return $this->hasMany(SubscribeSpecificationItem::class, 'subscribe_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'subscribe_specification_items', 'subscribe_id', 'category_id');
    }

    public function instructors()
    {
        return $this->belongsToMany(User::class, 'subscribe_specification_items', 'subscribe_id', 'instructor_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Webinar::class, 'subscribe_specification_items', 'subscribe_id', 'course_id');
    }

    public function bundles()
    {
        return $this->belongsToMany(Bundle::class, 'subscribe_specification_items', 'subscribe_id', 'bundle_id');
    }



    /* ==========
     | Helpers
     * ==========*/

    public static function getActiveSubscribe($userId)
    {
        $activePlan = null;
        $subscribe = null;
        $saleCreatedAt = null;

        $lastSubscribeSale = Sale::where('buyer_id', $userId)
            ->where('type', Sale::$subscribe)
            ->whereNull('refund_at')
            ->latest('created_at')
            ->first();

        if ($lastSubscribeSale) {
            $subscribe = $lastSubscribeSale->subscribe;
            $saleCreatedAt = $lastSubscribeSale->created_at;
        }

        /* check installment */
        if (empty($subscribe)) {
            $installmentOrder = InstallmentOrder::query()->where('user_id', $userId)
                ->whereNotNull('subscribe_id')
                ->where('status', 'open')
                ->whereNull('refund_at')
                ->latest('created_at')
                ->first();

            if (!empty($installmentOrder)) {
                $subscribe = $installmentOrder->subscribe;
                $subscribe->installment_order_id = $installmentOrder->id;
                $saleCreatedAt = $installmentOrder->created_at;

                if ($installmentOrder->checkOrderHasOverdue()) {
                    $overdueIntervalDays = getInstallmentsSettings('overdue_interval_days');

                    if (empty($overdueIntervalDays) or $installmentOrder->overdueDaysPast() > $overdueIntervalDays) {
                        $subscribe = null;
                    }
                }
            }
        }

        if (!empty($subscribe) and !empty($saleCreatedAt)) {
            $useCount = SubscribeUse::where('user_id', $userId)
                ->where('subscribe_id', $subscribe->id)
                ->whereHas('sale', function ($query) use ($saleCreatedAt) {
                    $query->where('created_at', '>', $saleCreatedAt);
                    $query->whereNull('refund_at');
                })
                ->count();

            $subscribe->used_count = $useCount;

            $countDayOfSale = (int)diffTimestampDay(time(), $saleCreatedAt);

            if (
                ($subscribe->usable_count > $useCount or $subscribe->infinite_use)
                and
                $subscribe->days >= $countDayOfSale
            ) {
                $activePlan = $subscribe;
            }
        }

        if (!empty($activePlan)) {
            $activePlan->saleCreatedAt = $saleCreatedAt;
            $remainedDays = 0;
            $remainedDaysPercent = 0;
            $expireAt = null;

            if (!empty($saleCreatedAt)) {
                $saleDays = (int)diffTimestampDay(time(), $saleCreatedAt);
                $remainedDays = $activePlan->days - $saleDays;

                if ($activePlan->days > 0 and $remainedDays > 0) {
                    $remainedDaysPercent = ($remainedDays / $activePlan->days) * 100;
                }

                $expireAt = $saleCreatedAt + ($activePlan->days * 24 * 60 * 60);
            }

            $activePlan->remained_days = $remainedDays;
            $activePlan->remained_days_percent = $remainedDaysPercent;

            $activePlan->expire_at = $expireAt;
        }

        return $activePlan;
    }

    public static function getDayOfUse($userId)
    {
        $lastSubscribeSale = Sale::where('buyer_id', $userId)
            ->where('type', Sale::$subscribe)
            ->whereNull('refund_at')
            ->latest('created_at')
            ->first();

        return $lastSubscribeSale ? (int)diffTimestampDay(time(), $lastSubscribeSale->created_at) : 0;
    }

    public function activeSpecialOffer()
    {
        $activeSpecialOffer = SpecialOffer::where('subscribe_id', $this->id)
            ->where('status', SpecialOffer::$active)
            ->where('from_date', '<', time())
            ->where('to_date', '>', time())
            ->first();

        return $activeSpecialOffer ?? false;
    }

    public function getPrice()
    {
        $price = $this->price;

        $specialOffer = $this->activeSpecialOffer();
        if (!empty($specialOffer)) {
            $price = $price - ($price * $specialOffer->percent / 100);
        }

        return $price;
    }

    public function hasInstallment($user = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $hasInstallment = false;
        $installmentPlans = new InstallmentPlans($user);

        if (getInstallmentsSettings('status') and $this->price > 0 and (empty($user) or $user->enable_installments)) {
            $installments = $installmentPlans->getPlans('subscription_packages', $this->id);

            $hasInstallment = (!empty($installments) and count($installments));
        }

        return $hasInstallment;
    }
}
