<?php

namespace App\Models;

use App\Mixins\Certificate\MakeCertificate;
use App\Models\Traits\SequenceContent;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Quiz extends Model implements TranslatableContract
{
    use Translatable;
    use SequenceContent;

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    public $timestamps = false;
    protected $table = 'quizzes';
    protected $guarded = ['id'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getDescriptionAttribute()
    {
        return getTranslateAttributeValue($this, 'description');
    }


    public function quizQuestions()
    {
        return $this->hasMany('App\Models\QuizzesQuestion', 'quiz_id', 'id');
    }

    public function quizResults()
    {
        return $this->hasMany('App\Models\QuizzesResult', 'quiz_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

    public function certificates()
    {
        return $this->hasMany('App\Models\Certificate', 'quiz_id', 'id');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Models\WebinarChapter', 'chapter_id', 'id');
    }

    public function personalNote()
    {
        return $this->morphOne('App\Models\CoursePersonalNote', 'targetable');
    }


    public function increaseTotalMark($grade)
    {
        $total_mark = $this->total_mark + $grade;
        return $this->update(['total_mark' => $total_mark]);
    }

    public function decreaseTotalMark($grade)
    {
        $total_mark = $this->total_mark - $grade;
        return $this->update(['total_mark' => $total_mark]);
    }

    public function getUserCertificate($user, $quiz_result)
    {
        if (!empty($user) and !empty($quiz_result)) {
            $makeCertificate = (new MakeCertificate());

            return $makeCertificate->saveQuizCertificate($user, $this, $quiz_result);
        }

        return null;
    }


    public function canAccessToEdit($user = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $result = false;

        if (!empty($user)) {
            $result = ($this->creator_id == $user->id);

            if (!$result) {
                $webinar = null;
                if (!empty($this->webinar_id)) {
                    $webinar = Webinar::query()->find($this->webinar_id);
                }

                if (!empty($webinar) and $webinar->canAccess($user)) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    public function getExpireTimestamp($user = null)
    {
        $timestamp = null;

        if (!empty($this->expiry_days)) {
            $webinar = $this->webinar;

            if (empty($user)) {
                $user = auth()->user();
            }

            $sale = $webinar->getSaleItem($user);

            if (!empty($sale)) {
                $purchaseDate = $sale->created_at;
                $gift = $sale->gift;

                if (!empty($gift) and !empty($gift->date)) {
                    $purchaseDate = $gift->date;
                }

                $purchaseDate = endOfDayTimestamp($purchaseDate);

                $timestamp = strtotime("+{$this->expiry_days} days", $purchaseDate);
            }
        }

        return $timestamp;
    }

    public function checkCanAccessByExpireDays($user = null)
    {
        $hasAccess = true;

        if (!empty($this->expiry_days)) {
            $expireTimestamp = $this->getExpireTimestamp($user);

            $time = time();
            $hasAccess = (!empty($expireTimestamp) and $expireTimestamp > $time);
        }

        return $hasAccess;
    }

    public function checkUserCanStartByAttempt($user = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $userQuizDone = QuizzesResult::where('quiz_id', $this->id)
            ->where('user_id', $user->id)
            ->get();

        $statusPass = false;
        foreach ($userQuizDone as $result) {
            if ($result->status == QuizzesResult::$passed) {
                $statusPass = true;
            }
        }

        // true => user can start
        // false => user can not start
        return (!isset($this->attempt) or ($userQuizDone->count() < $this->attempt and !$statusPass));
    }

    public function hasDescriptiveQuestion()
    {
        $questions = $this->quizQuestions()->where('type', QuizzesQuestion::$descriptive)->count();

        return ($questions > 0);
    }

    public function getStatusByUser($user=null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $status = "not_participated";

        $userQuizDone = QuizzesResult::where('quiz_id', $this->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($userQuizDone->isNotEmpty()) {
            $passed = false;
            foreach ($userQuizDone as $result) {
                if ($result->status == QuizzesResult::$passed) {
                    $passed = true;
                }
            }

            $status = $passed ? QuizzesResult::$passed : $userQuizDone->first()->status;
        }

        return $status;
    }

    public function getQuestionsCount()
    {
        $count = 0;

        if ($this->display_limited_questions and !empty($this->display_number_of_questions)) {
            $count = $this->display_number_of_questions;
        } else {
            $count = $this->quizQuestions->count();
        }

        return $count;
    }
}
