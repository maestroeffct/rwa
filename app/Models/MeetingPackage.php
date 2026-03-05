<?php

namespace App\Models;


use App\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class MeetingPackage extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'meeting_packages';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dateFormat = 'U';


    /* ==========
     | Translation
     * ==========*/
    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    /* ==========
     | Relations
     * ==========*/
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(MeetingPackageSold::class, 'meeting_package_id', 'id');
    }

    /* ==========
     | Helpers
     * ==========*/

    public function getPrices()
    {
        $realPrice = $price = $this->price;
        $discount = $this->discount;

        if ($realPrice > 0 and $discount > 0) {
            $price = $realPrice - ($realPrice * $discount / 100);
        }

        return [
            'price' => $price,
            'real_price' => $realPrice,
        ];
    }

    public function getDiscountPrice()
    {
        $percent = $this->discount;
        $price = 0;

        if ($percent > 0) {
            $price = ($this->price * $percent / 100);
        }

        return ($price > 0) ? $price : 0;
    }
}
