<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionAttendanceNotification extends Model
{
    protected $table = 'session_attendance_notifications';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dateFormat = 'U';

}
