<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class SubscribeSpecificationItem extends Model
{
    protected $table = 'subscribe_specification_items';
    public $timestamps = false;
    protected $guarded = ['id'];


}
