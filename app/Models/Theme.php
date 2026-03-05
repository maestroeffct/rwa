<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $table = 'themes';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


    /*===========
     * Relations
     * ========*/

    public function color()
    {
        return $this->belongsTo(ThemeColorFont::class, 'color_id', 'id');
    }

    public function font()
    {
        return $this->belongsTo(ThemeColorFont::class, 'font_id', 'id');
    }

    public function header()
    {
        return $this->belongsTo(ThemeHeaderFooter::class, 'header_id', 'id');
    }

    public function footer()
    {
        return $this->belongsTo(ThemeHeaderFooter::class, 'footer_id', 'id');
    }

    public function homeLanding()
    {
        return $this->belongsTo(Landing::class, 'home_landing_id', 'id');
    }

    /*===========
     * Helpers
     * ========*/
}
