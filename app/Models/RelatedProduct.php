<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model
{
    protected $table = "related_products";
    public $timestamps = false;
    protected $guarded = ['id'];


    /* ==========
     | Relations
     * ==========*/

    public function targetable()
    {
        return $this->morphTo();
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
