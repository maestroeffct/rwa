<?php

namespace App\Models;

use App\User;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ProductBadge extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = "product_badges";
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];


    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    /********
     * Relations
     * ******/

    public function contents()
    {
        return $this->hasMany('App\Models\ProductBadgeContent', 'product_badge_id', 'id');
    }

    /********
     * Helpers
     * ******/

    public function isActive()
    {
        $result = !!$this->enable;

        if ($result) {
            $time = time();

            if (!empty($this->start_at) and $this->start_at > $time) {
                $result = false;
            }

            if (!empty($this->expire_at) and $this->expire_at < $time) {
                $result = false;
            }
        }

        return $result;
    }


}
