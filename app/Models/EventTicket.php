<?php

namespace App\Models;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'event_tickets';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dateFormat = 'U';


    /* ==========
     | Translation
     * ==========*/
    public $translatedAttributes = ['title', 'description', 'options'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }

    public function getOptionsAttribute()
    {
        return getTranslateAttributeValue($this, 'options');
    }

    /* ==========
     | Relations
     * ==========*/
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'event_ticket_id', 'id')
            ->whereNull('refund_at');
    }

    public function eventTicketsSolds()
    {
        return $this->hasMany(EventTicketSold::class, 'event_ticket_id', 'id');
    }

    /* ==========
     | Helpers
     * ==========*/


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
            $salesCount = $this->sales()->where('buyer_id', $user->id)->count();

            $hasBought = ($salesCount > 0);
        }

        return $hasBought;
    }

    public function getAllCapacity()
    {
        $capacity = $this->capacity;

        if (is_null($capacity) and !is_null($this->event->capacity)) {
            $capacity = $this->event->capacity;
        }

        return $capacity;
    }

    public function getAvailableCapacity()
    {
        $result = null;
        $capacity = $this->getAllCapacity();

        if (!is_null($capacity)) {
            $salesCount = $this->sales()->count();

            $result = $capacity - $salesCount;
        }

        $event = $this->event;
        if(!empty($event->sales_end_date) and $event->sales_end_date <= time()) {
            $result = 0;
        }


        if ($result < 0) {
            $result = 0;
        }

        return $result;
    }

    public function getCapacityPercent()
    {
        $percent = 0;
        $salesCount = $this->sales()->count();
        $capacity = $this->getAllCapacity();

        if ($salesCount > 0 and $capacity > 0) {
            $percent = (($salesCount * 100) / $capacity);
        }

        if ($percent < 0) {
            $percent = 0;
        }

        if ($percent > 100) {
            $percent = 100;
        }

        return $percent;
    }

    public function checkIsSoldOut(): bool
    {
        $availableCapacity = $this->getAvailableCapacity();

        return (!is_null($availableCapacity) and $availableCapacity < 1);
    }

    public function hasDiscount()
    {
        $time = time();
        return (!empty($this->discount) and !empty($this->discount_start_at) and $this->discount_start_at <= $time and !empty($this->discount_end_at) and $this->discount_end_at >= $time);
    }

    public function getPriceWithDiscount()
    {
        $percent = $this->discount;
        $price = $this->price;

        if ($percent > 0 and $this->hasDiscount()) {
            $price = $price - ($price * $percent / 100);
        }

        return ($price > 0) ? $price : 0;
    }

    public function getDiscountPrice()
    {
        $percent = $this->discount;
        $price = 0;

        if ($percent > 0 and $this->hasDiscount()) {
            $price = ($this->price * $percent / 100);
        }

        return ($price > 0) ? $price : 0;
    }

    public function getIconText()
    {
        $icon = 'bul-triangle';

        if (!empty($this->icon)) {
            $icon = $this->icon;
        } else {
            $setting = getEventsSettings("tickets_default_icon");

            if (!empty($setting)) {
                $icon = $setting;
            }
        }

        return $icon;
    }
}
