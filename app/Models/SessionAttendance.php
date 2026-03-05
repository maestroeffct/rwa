<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SessionAttendance extends Model
{
    protected $table = 'session_attendance';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dateFormat = 'U';


    /* ==========
     | Relations
     * ==========*/
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id', 'id');
    }


    /* ==========
     | Helpers
     * ==========*/


}
