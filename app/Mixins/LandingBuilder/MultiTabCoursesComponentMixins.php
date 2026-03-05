<?php

namespace App\Mixins\LandingBuilder;


use App\Models\Event;
use App\Models\EventTicketSold;
use App\Models\Webinar;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MultiTabCoursesComponentMixins
{

    public function getCoursesByTabSource($tabContent, $count = 5)
    {
        // Sort => 'best_rated', 'lowest_price', 'highest_price', 'publish_date'
        $sort = !empty($tabContent['sort']) ? $tabContent['sort'] : "publish_date";
        $source = !empty($tabContent['source']) ? $tabContent['source'] : null;


        $noRecord = false;

        $query = Webinar::query()->where('webinars.status', Webinar::$active)
            ->where('webinars.private', false)
            ->where('webinars.only_for_students', false);

        if (!empty($source)) {
            if ($source == "category") {
                $categoryId = !empty($tabContent['category']) ? $tabContent['category'] : null;

                if (!empty($categoryId)) {
                    $query->where('webinars.category_id', $categoryId);
                } else {
                    $noRecord = true;
                }
            } else if ($source == "instructor") {
                $instructorId = !empty($tabContent['instructor']) ? $tabContent['instructor'] : null;

                if (!empty($instructorId)) {
                    $query->where('webinars.teacher_id', $instructorId);
                } else {
                    $noRecord = true;
                }
            } else if ($source == "custom") {
                $ids = !empty($tabContent['custom_courses']) ? $tabContent['custom_courses'] : [];

                if (!empty($ids)) {
                    $query->whereIn('webinars.id', array_values($ids));
                } else {
                    $noRecord = true;
                }
            }
        } else {
            $noRecord = true;
        }

        if ($noRecord) {
            $query->whereRaw('1 = 0');
        }

        switch ($sort) {
            case 'publish_date':
                $query->orderBy('webinars.created_at', "desc");
                break;

            case 'highest_price':
                $query->orderBy('webinars.price', "desc");
                break;

            case 'lowest_price':
                $query->orderBy('webinars.price', "asc");
                break;

            case 'best_rated':
                $query->leftJoin('webinar_reviews', function ($join) {
                    $join->on("webinars.id", '=', "webinar_reviews.webinar_id");
                    $join->where('webinar_reviews.status', 'active');
                })
                    //->whereNotNull('rates')
                    ->select("webinars.*", DB::raw('avg(rates) as rates'))
                    ->groupBy("webinars.id")
                    ->orderBy('rates', 'desc');
                break;
        }

        $query->with([
            'teacher' => function ($qu) {
                $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
            },
            'reviews' => function ($query) {
                $query->where('status', 'active');
            }
        ]);

        return $query->limit($count)->get();
    }


    private function getByCategory($categoryId, $sort, $count = 4)
    {
        $courses = Webinar::query()->where('category_id', $ids)
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                }
            ])
            ->get();
    }
}
