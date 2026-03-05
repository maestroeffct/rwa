<?php

namespace App\Mixins\RegistrationPackage;


use App\Models\Subscribe;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;

class SubscribeMixins
{


    /**
     * @param Subscribe $subscribe
     * @param Builder $query
     *
     * @return Builder
     * */
    public function handleTargetProductLimitationOnCourseQuery($subscribe, Builder $query, $targetType): Builder
    {
        if (!in_array($subscribe->target_type, ["all", $targetType])) { // If this plan was not for courses|bundles then the result should be empty.
            return $query->whereRaw('1 = 0');
        }

        if ($subscribe->target_type == $targetType) {
            switch ($subscribe->target) {
                case 'live_classes':
                    $query->where('type', Webinar::$webinar);
                    break;

                case 'video_courses':
                    $query->where('type', Webinar::$course);
                    break;

                case 'text_courses':
                    $query->where('type', Webinar::$textLesson);
                    break;

                case 'specific_categories':
                    $categoriesIds = $subscribe->categories->pluck('id')->toArray();

                    if (count($categoriesIds)) {
                        $query->whereIn('category_id', $categoriesIds);
                    }
                    break;

                case 'specific_instructors':
                    $usersIds = $subscribe->instructors->pluck('id')->toArray();

                    if (count($usersIds)) {
                        $query->where(function ($query) use ($usersIds) {
                            $query->whereIn('teacher_id', $usersIds);
                            $query->orWhereIn('creator_id', $usersIds);
                        });
                    }
                    break;

                case 'specific_courses':
                    $courseIds = $subscribe->courses->pluck('id')->toArray();

                    if (count($courseIds)) {
                        $query->whereIn('id', $courseIds);
                    }
                    break;

                case 'specific_bundles':
                    $bundlesIds = $subscribe->bundles->pluck('id')->toArray();

                    if (count($bundlesIds)) {
                        $query->whereIn('id', $bundlesIds);
                    }
                    break;
            }
        }

        return $query;
    }


    /*
     *
     * */
    public function getSubscribesByTargetProducts($targetType, $categoryId, $instructorId, $itemId, $courseType = null)
    {
        $query = Subscribe::query();

        $query->where(function (Builder $query) use ($targetType, $categoryId, $instructorId, $itemId, $courseType) {
            $query->where('target_type', 'all');

            $query->orWhere(function (Builder $query) use ($targetType, $categoryId, $instructorId, $itemId, $courseType) {
                if ($targetType == 'courses') {
                    $query->where('target_type', 'courses');
                } else {
                    $query->where('target_type', 'bundles');
                }

                // Targets
                $query->where(function (Builder $query) use ($targetType, $categoryId, $instructorId, $itemId, $courseType) {

                    if ($targetType == 'courses') {
                        $query->where('target', 'all_courses');

                        $courseTypeTarget = ($courseType == Webinar::$webinar) ? 'live_classes' : (($courseType == Webinar::$course) ? 'video_courses' : 'text_courses');
                        $query->orWhere('target', $courseTypeTarget);

                        // Specific Course
                        $query->orWhere(function (Builder $query) use ($itemId) {
                            $query->where('target', 'specific_courses');
                            $query->whereHas('specificationItems', function ($query) use ($itemId) {
                                $query->where('course_id', $itemId);
                            });
                        });
                    } else {
                        $query->where('target', 'all_bundles');

                        // Specific Bundles
                        $query->orWhere(function (Builder $query) use ($itemId) {
                            $query->where('target', 'specific_bundles');
                            $query->whereHas('specificationItems', function ($query) use ($itemId) {
                                $query->where('bundle_id', $itemId);
                            });
                        });
                    }

                    // Specific Category
                    $query->orWhere(function (Builder $query) use ($categoryId) {
                        $query->where('target', 'specific_categories');
                        $query->whereHas('specificationItems', function ($query) use ($categoryId) {
                            $query->where('category_id', $categoryId);
                        });
                    });

                    // Specific Instructor
                    $query->orWhere(function (Builder $query) use ($instructorId) {
                        $query->where('target', 'specific_instructors');
                        $query->whereHas('specificationItems', function ($query) use ($instructorId) {
                            $query->where('instructor_id', $instructorId);
                        });
                    });

                });

            });
        });

        return $query->get();
    }

}
