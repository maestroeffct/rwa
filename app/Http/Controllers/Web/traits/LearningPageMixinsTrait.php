<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\TimeSpentOnCourse;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use Illuminate\Support\Facades\Request;

trait LearningPageMixinsTrait
{
    public function getCourse($slug, $user = null, $relation = null, $relationWith = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $query = Webinar::where('slug', $slug);

        if (!empty($relation)) {
            $query->with([
                "{$relation}" => function ($query) use ($relation, $relationWith) {
                    if ($relation == 'forums') {
                        $query->orderBy('pin', 'desc');
                    }

                    $query->orderBy('created_at', 'desc');

                    if (!empty($relationWith)) {
                        $query->with($relationWith);
                    }
                }
            ])->withCount([
                "{$relation}"
            ]);
        }

        $query->with([
            'chapters' => function ($query) use ($user) {
                $query->where('status', WebinarChapter::$chapterActive);
                $query->orderBy('order', 'asc');

                $query->with([
                    'chapterItems' => function ($query) {
                        $query->orderBy('order', 'asc');
                    }
                ]);
            }
        ]);

        $course = $query->first();

        if (!empty($course) and ($course->checkUserHasBought($user) or !empty($course->getInstallmentOrder()))) {
            $isPrivate = $course->private;
            $hasBought = $course->checkUserHasBought($user);

            if (!empty($user) and ($user->id == $course->creator_id or $user->organ_id == $course->creator_id or $user->isAdmin() or $hasBought)) {
                $isPrivate = false;
            }

            if ($isPrivate) {
                return 'not_access';
            }

            return $course;
        }

        return 'not_access';
    }

    private function handleStartTrackingTime($courseId, $userId)
    {
        $time = time();

        TimeSpentOnCourse::query()->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'page' => "learning_page",
            'entry_time' => $time,
            'exit_time' => $time + 10, // After entering the page, we record the last time every 10 seconds. So at the beginning, we also record the exit time 10 seconds earlier.
            'seconds_spent' => 10,
        ]);
    }

    public function trackSpentTime(Request $request, $courseSlug)
    {
        $course = $this->getCourse($courseSlug);

        if ($course == 'not_access') {
            abort(404);
        }

        $user = auth()->user();

        $trackingTime = TimeSpentOnCourse::query()->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->orderBy('entry_time', 'desc')
            ->first();

        $forceReload = true;

        if (!empty($trackingTime)) {
            $forceReload = false;
            $time = time();
            $exitTime = $time + 10;
            $secondsSpent = $exitTime - $trackingTime->entry_time;

            $trackingTime->update([
                'exit_time' => $exitTime,
                'seconds_spent' => $secondsSpent,
            ]);
        }

        return response()->json([
            'code' => 200,
            'force_reload' => $forceReload,
        ]);
    }
}
