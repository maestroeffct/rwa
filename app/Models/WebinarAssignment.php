<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\Traits\SequenceContent;

class WebinarAssignment extends Model implements TranslatableContract
{
    use Translatable;
    use SequenceContent;

    protected $table = 'webinar_assignments';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title', 'description'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }


    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\WebinarChapter', 'chapter_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\WebinarAssignmentAttachment', 'assignment_id', 'id');
    }

    public function assignmentHistory()
    {
        return $this->hasOne('App\Models\WebinarAssignmentHistory', 'assignment_id', 'id');
    }

    public function personalNote()
    {
        return $this->morphOne('App\Models\CoursePersonalNote', 'targetable');
    }

    public function instructorAssignmentHistories()
    {
        return $this->hasMany('App\Models\WebinarAssignmentHistory', 'assignment_id', 'id');
    }

    public function getAssignmentHistoryByStudentId($studentId)
    {
        return $this->assignmentHistory()
            ->where('student_id', $studentId)
            ->first();
    }

    public function getDeadlineTimestamp($user = null)
    {
        $deadline = null; // default can access

        if (empty($user)) {
            $user = auth()->user();
        }

        if (!empty($this->deadline)) {
            $sale = $this->getSale($user);

            if (!empty($sale)) {
                $deadline = strtotime("+{$this->deadline} days", $sale->created_at);
            } else {
                $deadline = false;
            }
        }

        return $deadline;
    }

    public function getSale($user = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $bundleIdsByWebinar = BundleWebinar::query()->where('webinar_id', $this->webinar_id)
            ->pluck('bundle_id')->toArray();

        $sales = Sale::where('buyer_id', $user->id)
            ->where(function ($query) {
                $query->whereNotNull('webinar_id');
                $query->orWhereNotNull('bundle_id');
            })
            ->whereNull('refund_at')
            ->get();

        $selectedSale = null;

        foreach ($sales as $sale) {
            if ($sale->payment_method == Sale::$subscribe) {
                $subscribe = $sale->getUsedSubscribe($sale->buyer_id, $sale->webinar_id);

                if (!empty($subscribe)) {
                    $subscribeSale = Sale::where('buyer_id', $this->id)
                        ->where('type', Sale::$subscribe)
                        ->where('subscribe_id', $subscribe->id)
                        ->whereNull('refund_at')
                        ->latest('created_at')
                        ->first();

                    if (!empty($subscribeSale)) {
                        $usedDays = (int)diffTimestampDay(time(), $subscribeSale->created_at);

                        if ($usedDays <= $subscribe->days) {
                            if (!empty($sale->webinar_id) and $sale->webinar_id == $this->webinar_id) {
                                $selectedSale = $sale;
                            }

                            if (!empty($sale->bundle_id) and !empty($bundleIdsByWebinar) and in_array($sale->bundle_id, $bundleIdsByWebinar)) {
                                $selectedSale = $sale;
                            }
                        }
                    }
                }
            } else {
                if (!empty($sale->webinar_id) and $sale->webinar_id == $this->webinar_id) {
                    $selectedSale = $sale;
                }

                if (!empty($sale->bundle_id) and !empty($bundleIdsByWebinar) and in_array($sale->bundle_id, $bundleIdsByWebinar)) {
                    $selectedSale = $sale;
                }
            }
        }


        return $selectedSale;
    }
}
