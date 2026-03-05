<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSpentOnCourse extends Model
{
    protected $table = "time_spent_on_courses";
    public $timestamps = false;
    protected $guarded = ['id'];
}
