<?php

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class MeetingPackageSold extends Model
{
    protected $table = 'meeting_packages_sold';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dateFormat = 'U';


    /* ==========
     | Relations
     * ==========*/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function meetingPackage()
    {
        return $this->belongsTo(MeetingPackage::class, 'meeting_package_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'meeting_package_sold_id', 'id');
    }

    /* ==========
     | Helpers
     * ==========*/

    public function handleExtraData()
    {
        $time = time();
        $ended = 0;
        $scheduled = 0;
        $notScheduled = 0;
        $allSessionsFinished = true;

        foreach ($this->sessions as $session) {

            if ($session->status == "finished") {
                $ended += 1;
            } else {
                $allSessionsFinished = false;

                if (!empty($session->date)) {
                    $scheduled += 1;
                } else {
                    $notScheduled += 1;
                }
            }
        }

        $status = "finished";
        if ($this->expire_at > $time and !$allSessionsFinished) {
            $status = "open";
        }

        $this->status = $status;

        $this->ended = $ended;
        $this->scheduled = $scheduled;
        $this->notScheduled = $notScheduled;

        return $this;
    }

    public function getTotalDuration()
    {
        return $this->session_duration * $this->sessions->count();
    }
}
