<?php

namespace App\Models\Translation;


use Illuminate\Database\Eloquent\Model;

class ThemeHeaderFooterTranslation extends Model
{
    protected $table = 'theme_header_footer_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

}
