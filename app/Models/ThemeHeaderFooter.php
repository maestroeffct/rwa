<?php

namespace App\Models;


use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ThemeHeaderFooter extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'theme_headers_footers';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translationForeignKey = 'theme_header_footer_id';
    public $translatedAttributes = ['title', 'content'];

    /*============
     * Relations
     * ==========*/

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getContentAttribute()
    {
        return getTranslateAttributeValue($this, 'content');
    }

    /*============
     * Helpers
     * ==========*/

    static $headers = [ // component_name => title
        'header_1' => 'header 1',
        'header_2' => 'header 2',
    ];

    static $footers = [ // component_name => title
        'footer_1' => 'footer 1',
        'footer_2' => 'footer 2',
    ];
}
