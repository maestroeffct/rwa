<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFeaturedCategory extends Model
{
    protected $table = 'product_featured_categories';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }


}
