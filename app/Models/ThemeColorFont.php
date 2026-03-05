<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ThemeColorFont extends Model
{
    protected $table = 'theme_colors_fonts';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];


}
