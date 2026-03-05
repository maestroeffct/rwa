<?php

namespace App\Mixins\LandingBuilder;

use App\Http\Controllers\Web\UserProfileController;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Meeting;
use App\Models\MeetingPackage;
use App\Models\MeetingTime;
use App\Models\Product;
use App\Models\ReserveMeeting;
use App\Models\Role;
use App\Models\Sale;
use App\Models\SpecialOffer;
use App\Models\Subscribe;
use App\Models\Testimonial;
use App\Models\Ticket;
use App\Models\UpcomingCourse;
use App\Models\Webinar;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FrontComponentsDataMixins
{


    public function getNewestCoursesData($count = 4)
    {
        return Webinar::query()->where('status', 'active')
            ->where('private', false)
            ->where('only_for_students', false)
            ->orderBy('updated_at', 'desc')
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                }
            ])
            ->limit($count)
            ->get();
    }

    public function getBestSellingCoursesData($count = 3)
    {
        $bestSaleCoursesIds = Sale::query()->whereNotNull('webinar_id')
            ->select(DB::raw('COUNT(id) as cnt,webinar_id'))
            ->groupBy('webinar_id')
            ->orderBy('cnt', 'DESC')
            ->limit(6)
            ->pluck('webinar_id')
            ->toArray();

        return Webinar::query()->whereIn('id', $bestSaleCoursesIds)
            ->where('status', 'active')
            ->where('private', false)
            ->where('only_for_students', false)
            ->orderBy('updated_at', 'desc')
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                }
            ])
            ->limit($count)
            ->inRandomOrder()
            ->get();
    }

    public function getBestRatedCoursesData($count = 3)
    {
        return Webinar::query()
            ->join('webinar_reviews', function ($join) {
                $join->on("webinars.id", '=', "webinar_reviews.webinar_id");
                $join->where('webinar_reviews.status', 'active');
            })
            ->select('webinars.*', DB::raw('avg(rates) as avg_rates'))
            ->where('webinars.private', false)
            ->where('webinars.status', 'active')
            ->where('webinars.only_for_students', false)
            ->whereNotNull('webinar_reviews.rates')
            ->groupBy("webinars.id")
            ->orderBy('avg_rates', 'desc')
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                }
            ])
            ->limit($count)
            ->get();
    }

    public function getDiscountedCoursesData($count = 4)
    {
        $now = time();
        $webinarIdsHasDiscount = [];

        $tickets = Ticket::query()->where('start_date', '<', $now)
            ->where('end_date', '>', $now)
            ->get();

        foreach ($tickets as $ticket) {
            if ($ticket->isValid()) {
                $webinarIdsHasDiscount[] = $ticket->webinar_id;
            }
        }

        $specialOffersWebinarIds = SpecialOffer::query()->where('status', 'active')
            ->where('from_date', '<', $now)
            ->where('to_date', '>', $now)
            ->pluck('webinar_id')
            ->toArray();

        $webinarIdsHasDiscount = array_merge($specialOffersWebinarIds, $webinarIdsHasDiscount);

        return Webinar::query()->whereIn('id', array_unique($webinarIdsHasDiscount))
            ->where('status', 'active')
            ->where('private', false)
            ->where('only_for_students', false)
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
                'sales',
                'tickets',
                'feature'
            ])
            ->limit($count)
            ->get();
    }

    public function getFreeCoursesData($count = 4)
    {
        return Webinar::query()->where('status', Webinar::$active)
            ->where('private', false)
            ->where('only_for_students', false)
            ->where(function ($query) {
                $query->whereNull('price')
                    ->orWhere('price', '0');
            })
            ->orderBy('updated_at', 'desc')
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
                'tickets',
                'feature'
            ])
            ->limit($count)
            ->get();
    }

    public function getUpcomingCoursesData($count = 4)
    {
        return UpcomingCourse::query()->where('status', Webinar::$active)
            ->orderBy('created_at', 'desc')
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                }
            ])
            ->limit($count)
            ->get();
    }

    public function getCourseBundlesData($count = 4)
    {
        return Bundle::query()->where('status', Webinar::$active)
            ->orderBy('updated_at', 'desc')
            ->with([
                'teacher' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
                'tickets',
            ])
            ->limit($count)
            ->get();
    }

    public function getStoreProductsData($count = 6)
    {
        return Product::query()->where('status', Product::$active)
            ->orderBy('updated_at', 'desc')
            ->with([
                'creator' => function ($qu) {
                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
            ])
            ->limit($count)
            ->get();
    }

    public function getMeetingBookingListInstructorsByIds($ids = []): Collection
    {
        $instructors = collect();

        if (!empty($ids)) {
            $instructors = User::query()->whereIn('id', $ids)
                ->where('users.status', 'active')
                ->with([
                    'meeting' => function ($query) {
                        $query->with('meetingTimes');
                        $query->withCount('meetingTimes');
                    }
                ])
                ->get();

            $userProfileController = (new UserProfileController());

            foreach ($instructors as $instructor) {
                $meetingIds = Meeting::query()->where('creator_id', $instructor->id)->pluck('id');
                $reserveMeetingsQuery = ReserveMeeting::query()->whereIn('meeting_id', $meetingIds)
                    ->where(function ($query) {
                        $query->whereHas('sale', function ($query) {
                            $query->whereNull('refund_at');
                        });

                        $query->orWhere(function ($query) {
                            $query->whereIn('status', ['canceled']);
                            $query->whereHas('sale');
                        });
                    });

                $instructor->total_meetings = deepClone($reserveMeetingsQuery)->count();

                // weekly_hours
                $weeklyHoursCount = 0;
                $allMeetingTimes = MeetingTime::whereIn('meeting_id', $meetingIds)->get();

                foreach ($allMeetingTimes as $time) {
                    $explodeTime = explode('-', $time->time);
                    $weeklyHoursCount += strtotime($explodeTime[1]) - strtotime($explodeTime[0]);
                }

                if ($weeklyHoursCount > 0) {
                    $weeklyHoursCount = round($weeklyHoursCount / 3600, 2);
                }

                $instructor->weekly_hours = $weeklyHoursCount;

                // Earliest Available Time
                $instructor->earliestAvailableTime = $userProfileController->getNearestDate($allMeetingTimes);

            }
        }

        return $instructors;
    }

    public function getSubscriptionsPlansByIds($ids = []): Collection
    {
        $plans = collect();

        if (!empty($ids)) {
            $plans = Subscribe::query()->whereIn('id', $ids)
                ->get();
        }

        return $plans;
    }

    public function getTestimonialsByIds($ids = []): Collection
    {
        $testimonials = collect();

        if (!empty($ids)) {
            $testimonials = Testimonial::query()->whereIn('id', $ids)
                ->where('status', 'active')
                ->get();
        }

        return $testimonials;
    }

    public function getUsersByIds($ids = [], $role = 'user'): Collection
    {
        $users = collect();

        if (!empty($ids)) {
            $users = User::query()->whereIn('id', $ids)
                ->where('role_name', $role)
                ->where('users.status', 'active')
                ->get();
        }

        return $users;
    }

    public function getBlogData($count = 5)
    {
        return Blog::query()->where('status', 'publish')
            ->whereHas('author')
            ->with([
                'category',
                'author' => function ($query) {
                    $query->select('id', 'full_name', 'username', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                }
            ])
            ->orderBy('updated_at', 'desc')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->limit($count)
            ->get();
    }

    public function getCoursesByIds($ids = []): Collection
    {
        $courses = collect();

        if (!empty($ids)) {
            $courses = Webinar::query()->whereIn('id', $ids)
                ->where('status', Webinar::$active)
                ->where('private', false)
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

        return $courses;
    }

    public function getMeetingPackagesByIds($ids = []): Collection
    {
        $meetingPackages = collect();

        if (!empty($ids)) {
            $meetingPackages = MeetingPackage::query()->whereIn('id', $ids)
                ->where('enable', true)
                ->with([
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about', 'verified', 'cover_img', 'profile_secondary_image');
                    },
                ])
                ->get();
        }

        return $meetingPackages;
    }
}
