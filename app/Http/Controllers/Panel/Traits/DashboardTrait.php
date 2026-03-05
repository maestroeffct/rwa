<?php

namespace App\Http\Controllers\Panel\Traits;

use App\Enums\MorphTypesEnum;
use App\Models\Bundle;
use App\Models\Certificate;
use App\Models\MeetingPackageSold;
use App\Models\Product;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\ReserveMeeting;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Session;
use App\Models\Support;
use App\Models\TimeSpentOnCourse;
use App\Models\UpcomingCourse;
use App\Models\VisitLog;
use App\Models\Webinar;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentHistory;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait DashboardTrait
{

    private function getStudentHelloBoxData($user, $userBoughtWebinarsIds): array
    {
        $coursesCount = count($userBoughtWebinarsIds);

        $meetingsCount = ReserveMeeting::where('user_id', $user->id)
            ->whereNotNull('reserved_at')
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            })->where('status', ReserveMeeting::$open)
            ->count();

        $certificatesCount = Certificate::query()->where('student_id', $user->id)->count();

        $passedQuizCount = QuizzesResult::where('user_id', $user->id)->where('status', QuizzesResult::$passed)->count();

        $continueLearningCourses = [];
        $enrollOnCoursesFromAdmin = null;

        if (count($userBoughtWebinarsIds) >= 2) {
            $courses = Webinar::query()->whereIn('id', $userBoughtWebinarsIds)
                ->inRandomOrder()
                ->get();

            foreach ($courses as $course) {
                $learningProgress = $course->getProgress(true);

                if ($learningProgress < 100 and count($continueLearningCourses) < 2) {
                    $continueLearningCourses[] = $course;
                }
            }
        } else {
            $coursesIdsFromAdmin = getUserDashboardDataSettings("student_enroll_on_courses");

            if (!empty($coursesIdsFromAdmin) and is_array($coursesIdsFromAdmin) and count($coursesIdsFromAdmin)) {
                $enrollOnCoursesFromAdmin = Webinar::query()->whereIn('id', $coursesIdsFromAdmin)
                    ->inRandomOrder()
                    ->limit(2)
                    ->get();
            }
        }

        return [
            'coursesCount' => $coursesCount,
            'meetingsCount' => $meetingsCount,
            'certificatesCount' => $certificatesCount,
            'passedQuizCount' => $passedQuizCount,
            'continueLearningCourses' => $continueLearningCourses,
            'enrollOnCoursesFromAdmin' => $enrollOnCoursesFromAdmin,
        ];
    }

    private function getStudentCoursesOverviewData($user, $userBoughtWebinarsIds): array
    {
        $totalCourses = count($userBoughtWebinarsIds);
        $completedCourses = 0;
        $openCourses = 0;

        $courses = null;
        $overviewCoursesFromAdmin = null;

        if (count($userBoughtWebinarsIds)) {
            $courses = Webinar::query()->whereIn('id', $userBoughtWebinarsIds)
                ->inRandomOrder()
                ->get();

            foreach ($courses as $course) {
                if ($course->getProgress() >= 100) {
                    $completedCourses += 1;
                } else {
                    $openCourses += 1;
                }
            }
        } else {
            $coursesIdsFromAdmin = getUserDashboardDataSettings("student_overview_courses");

            if (!empty($coursesIdsFromAdmin) and is_array($coursesIdsFromAdmin) and count($coursesIdsFromAdmin)) {
                $overviewCoursesFromAdmin = Webinar::query()->whereIn('id', $coursesIdsFromAdmin)
                    ->inRandomOrder()
                    ->limit(2)
                    ->get();
            }
        }

        return [
            'totalCourses' => $totalCourses,
            'completedCourses' => $completedCourses,
            'openCourses' => $openCourses,
            'overviewCoursesFromAdmin' => $overviewCoursesFromAdmin,
            'courses' => $courses,
        ];
    }

    private function getStudentMyAssignmentsData($user, $userBoughtWebinarsIds)
    {
        return WebinarAssignment::whereIn('webinar_id', $userBoughtWebinarsIds)
            ->where('status', 'active')
            ->with([
                'webinar',
                'assignmentHistory' => function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                    $query->with([
                        'messages' => function ($query) use ($user) {
                            $query->where('sender_id', $user->id);
                            $query->orderBy('created_at', 'desc');
                        }
                    ]);
                },
            ])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }


    private function getStudentLearningActivityData($user, $userBoughtWebinarsIds): array
    {
        $learningPageActivityQuery = TimeSpentOnCourse::query()->where('user_id', $user->id)
            ->where('page', 'learning_page');

        $time = time();
        $beginOfYear = strtotime(date('Y-01-01', $time));// First day of the year.
        $endOfYear = strtotime(date('Y-m-d', strtotime('12/31'))); // Last day of the year.

        $haveLearningActivity = deepClone($learningPageActivityQuery)
                ->whereBetween('entry_time', [$beginOfYear, $endOfYear])
                ->count() > 0;


        $labels = [];
        $data = [];
        $stats = [];
        $topActivityCourses = null;
        $continueLearningCourses = null;

        if ($haveLearningActivity) {
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create(date('Y'), $month);

                $startDate = $date->timestamp;
                $endDate = $date->copy()->endOfMonth()->timestamp;

                $labels[] = trans('panel.month_' . $month);

                $activitySeconds = deepClone($learningPageActivityQuery)->where('entry_time', '>=', $startDate)
                    ->where('exit_time', '<=', $endDate)
                    ->sum('seconds_spent');

                $data[] = ($activitySeconds > 0) ? round($activitySeconds / 60, 1) : 0;
            }

            // Today Activity
            $beginOfDay = strtotime("today", $time);
            $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;

            $todayActivitySeconds = deepClone($learningPageActivityQuery)->where('entry_time', '>=', $beginOfDay)
                ->where('exit_time', '<=', $endOfDay)
                ->sum('seconds_spent');

            // Current Month
            $beginOfMonth = strtotime(date('Y-m-01', $time));// First day of the month.
            $endOfMonth = strtotime(date('Y-m-t', $time));// Last day of the month.

            $monthActivitySeconds = deepClone($learningPageActivityQuery)->where('entry_time', '>=', $beginOfMonth)
                ->where('exit_time', '<=', $endOfMonth)
                ->sum('seconds_spent');

            // Current Year
            $yearActivitySeconds = deepClone($learningPageActivityQuery)->where('entry_time', '>=', $beginOfYear)
                ->where('exit_time', '<=', $endOfYear)
                ->sum('seconds_spent');


            $stats = [
                'today' => ($todayActivitySeconds > 0) ? round($todayActivitySeconds / 60, 1) : 0,
                'month' => ($monthActivitySeconds > 0) ? round($monthActivitySeconds / 60, 1) : 0,
                'year' => ($yearActivitySeconds > 0) ? round($yearActivitySeconds / 60, 1) : 0,
            ];

            // Top Courses
            $topSpentTimeCourses = deepClone($learningPageActivityQuery)->select('*', DB::raw("sum(seconds_spent) as total_spent"))
                ->where('entry_time', '>=', $beginOfYear)
                ->where('exit_time', '<=', $endOfYear)
                ->groupBy('course_id')
                ->orderBy('total_spent', 'desc')
                ->limit(2)
                ->get();

            $topActivityCoursesIds = $topSpentTimeCourses->pluck('course_id')->toArray();
            $topActivityCourses = Webinar::query()->whereIn('id', $topActivityCoursesIds)->get();

            foreach ($topActivityCourses as $course) {
                $topSpentTimeCourse = $topSpentTimeCourses->where('course_id', $course->id)->first();

                $total_time_spent = 0;

                if (!empty($topSpentTimeCourse) and $topSpentTimeCourse->total_spent > 0) {
                    $total_time_spent = round($topSpentTimeCourse->total_spent / 60, 1);
                }
                $course->total_time_spent = $total_time_spent;
            }
        } else {
            $continueLearningCourses = Webinar::query()->whereIn('id', $userBoughtWebinarsIds)
                ->inRandomOrder()
                ->limit(2)
                ->get();
        }

        $learningActivityChart = [
            'labels' => $labels,
            'data' => $data,
        ];

        return [
            'learningActivityChart' => $learningActivityChart,
            'haveLearningActivity' => $haveLearningActivity,
            'activityStats' => $stats,
            'topActivityCourses' => $topActivityCourses,
            'continueLearningCourses' => $continueLearningCourses,
        ];
    }

    private function getStudentSupportMessagesData($user, $userBoughtWebinarsIds): array
    {
        $query = Support::query()->select('*', DB::raw("case
            when status = 'open' then 'a'
            when status = 'replied' then 'a'
            when status = 'supporter_replied' then 'b'
            when status = 'close' then 'c'
            end as status_order
        "))->where('user_id', $user->id);

        $totalTickets = deepClone($query)->count();
        $openTickets = deepClone($query)->where('status', '!=', 'close')->count();

        $supports = deepClone($query)
            ->orderBy('status_order', 'asc')
            ->with([
                'conversations' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'webinar',
                'department',
            ])
            ->limit(10)
            ->get();

        $supportCourse = null;

        if ($totalTickets < 1) {
            $supportCourse = Webinar::query()->whereIn('id', $userBoughtWebinarsIds)
                ->inRandomOrder()
                ->first();
        }


        return [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'supports' => $supports,
            'supportCourse' => $supportCourse,
        ];
    }

    private function getStudentMyQuizzesData($user, $userBoughtWebinarsIds): array
    {
        $pendingReviewCount = 0;
        $notParticipatedCount = 0;

        $pendingQuizzes = Quiz::query()->whereIn('webinar_id', $userBoughtWebinarsIds)
            ->where('status', 'active')
            ->where(function (Builder $query) use ($user) {
                $query->whereDoesntHave('quizResults', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });

                $query->orWhereHas('quizResults', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                    $query->where('status', 'waiting');
                });
            })
            ->with([
                'webinar',
                'quizResults' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'creator' => function ($query) {
                    $query->select('id', 'username', 'full_name', 'avatar', 'avatar_settings');
                }
            ])
            //->orderBy('created_at', 'desc')
            ->inRandomOrder()
            ->get();

        $quizzes = [];

        foreach ($pendingQuizzes as $quiz) {
            if ($quiz->quizResults->isEmpty()) {
                $notParticipatedCount += 1;

                if (count($quizzes) < 2) { // Priority with not Participate
                    $quizzes[] = $quiz;
                }
            } else {
                $pendingReviewCount += 1;
            }
        }

        if (count($quizzes) < 2 and count($pendingQuizzes) >= 2) { // If the number is less than 2, we use pending review.
            foreach ($pendingQuizzes as $quiz) {
                if ($quiz->quizResults->isNotEmpty() and count($quizzes) < 2) {
                    $lastResult = $quiz->quizResults->first();

                    $quiz->submited_at = $lastResult->created_at;

                    $quizzes[] = $quiz;
                }
            }
        }

        return [
            'notParticipatedCount' => $notParticipatedCount,
            'pendingReviewCount' => $pendingReviewCount,
            'quizzes' => $quizzes,
        ];
    }

    public function getStudentUpcomingLiveSessionsData($user, $userBoughtWebinarsIds)
    {
        $time = time();
        $sessionsQuery = Session::query()->whereIn('webinar_id', $userBoughtWebinarsIds)
            ->where('status', 'active')
            ->where('date', '>', $time)
            ->with([
                'webinar',
                'creator' => function ($query) {
                    $query->select('id', 'username', 'full_name', 'role_id', 'avatar', 'avatar_settings');
                }
            ])
            ->orderBy('date', 'asc');

        $totalSessions = deepClone($sessionsQuery)->count();
        $sessions = $sessionsQuery->limit(2)->get();

        $offerCourses = null;

        if ($totalSessions < 1) {
            $ids = getUserDashboardDataSettings("student_when_dont_upcoming_live_session");

            if (!empty($ids) and is_array($ids) and count($ids)) {
                $offerCourses = Webinar::query()->whereIn('id', $ids)->get();
            }
        }

        return [
            'totalSessions' => $totalSessions,
            'sessions' => $sessions,
            'offerCourses' => $offerCourses,
        ];
    }

    public function getStudentOpenMeetingsData($user, $userBoughtWebinarsIds)
    {
        $reserveMeetingsQuery = ReserveMeeting::query()->where('user_id', $user->id)
            ->where('status', 'open')
            ->whereNotNull('reserved_at')
            ->whereHas('sale', function ($query) {
                //$query->whereNull('refund_at');
            })->with([
                'meeting' => function ($query) {
                    $query->with([
                        'creator' => function ($query) {
                            $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'email');
                        }
                    ]);
                }
            ])
            //->where('start_at', '>', time())
            ->orderBy('start_at', 'asc');

        $totalMeetings = deepClone($reserveMeetingsQuery)->count();
        $reserveMeetings = $reserveMeetingsQuery->limit(3)->get();

        /* Meeting Package Scheduled Sessions */
        $meetingPackageScheduledSessions = null;
        $meetingPackagesSoldIds = MeetingPackageSold::query()
            ->where('user_id', $user->id)
            ->pluck('id')
            ->toArray();

        if (!empty($meetingPackagesSoldIds)) {
            $scheduledSessionsQuery = Session::query()
                ->whereIn('meeting_package_sold_id', $meetingPackagesSoldIds)
                ->where('status', '!=', 'finished')
                ->whereNotNull('date')
                ->where('date', '>', time());

            $totalScheduledSessions = deepClone($scheduledSessionsQuery)->count();

            if ($totalScheduledSessions > 0) {
                $totalMeetings += $totalScheduledSessions;

                $meetingPackageScheduledSessions = deepClone($scheduledSessionsQuery)->limit(3)->get();
            }
        }

        $instructors = null;

        if ($totalMeetings < 1) {
            $instructors = User::query()->select('id', 'full_name', 'username', 'avatar', 'avatar_settings', 'email')
                ->where('users.status', 'active')
                ->whereHas('meeting', function ($query) {
                    $query->whereHas('meetingTimes');
                })
                ->inRandomOrder()
                ->limit(3)
                ->get();
        }

        return [
            'totalMeetings' => $totalMeetings,
            'reserveMeetings' => $reserveMeetings,
            'instructors' => $instructors,
            'meetingPackageScheduledSessions' => $meetingPackageScheduledSessions,
        ];
    }

    /***************
     * | Instructors Data
     * ***********/
    private function getInstructorHelloBoxData($user, $meetingIds, $userWebinars): array
    {
        $coursesCount = count($userWebinars);

        $meetingsCount = count($meetingIds);

        $instructorsCount = 0;
        $studentsCount = 0;
        $productsCount = 0;
        $bundlesCount = 0;


        if ($user->isOrganization()) {
            $instructorsCount = $user->getOrganizationTeachers()->count();
            $studentsCount = $user->getOrganizationStudents()->count();
        } else {
            $productsCount = Product::query()->where('creator_id', $user->id)->count();

            $bundlesCount = Bundle::query()->where(function (Builder $query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })->count();
        }

        $manageCourses = $userWebinars->take(2);

        return [
            'coursesCount' => $coursesCount,
            'meetingsCount' => $meetingsCount,
            'productsCount' => $productsCount,
            'bundlesCount' => $bundlesCount,
            'instructorsCount' => $instructorsCount,
            'studentsCount' => $studentsCount,
            'manageCourses' => $manageCourses,
        ];
    }

    private function getInstructorCoursesOverviewData($user, $userWebinars): array
    {
        $totalLiveCourses = $userWebinars->where('type', Webinar::$webinar)->count();
        $totalVideoCourses = $userWebinars->where('type', Webinar::$course)->count();
        $totalTextCourses = $userWebinars->where('type', Webinar::$textLesson)->count();

        $courses = $userWebinars->take(5);

        return [
            'totalLiveCourses' => $totalLiveCourses,
            'totalVideoCourses' => $totalVideoCourses,
            'totalTextCourses' => $totalTextCourses,
            'courses' => $courses,
        ];
    }

    private function getInstructorSalesOverviewData($user, $userWebinarsIds): array
    {
        $totalCourseSales = 0;
        $totalProductSales = 0;
        $totalMeetingSales = 0;

        $hasSalesOverviewData = false;
        $monthSalesAmount = 0;
        $yearSalesAmount = 0;
        $totalSalesAmount = 0;
        $chart = [
            'labels' => [],
            'courseSales' => [],
            'meetingSales' => [],
            'productSales' => [],
        ];

        $salesQuery = Sale::query()
            ->where(function (Builder $query) use ($user, $userWebinarsIds) {
                $query->where('seller_id', $user->id);
                $query->orWhereIn('webinar_id', $userWebinarsIds);
            })
            ->whereNull('refund_at');

        if (deepClone($salesQuery)->count() > 0) {
            $hasSalesOverviewData = true;
            $time = time();

            $totalCourseSales = deepClone($salesQuery)->whereNotNull('webinar_id')->sum('total_amount');

            $totalProductSales = deepClone($salesQuery)->whereNotNull('product_order_id')->sum('total_amount');

            $totalMeetingSales = deepClone($salesQuery)->whereNotNull('meeting_id')->sum('total_amount');


            // Current Month Sales
            $beginOfMonth = strtotime(date('Y-m-01', $time));// First day of the month.
            $endOfMonth = strtotime(date('Y-m-t', $time));// Last day of the month.

            $monthSalesAmount = deepClone($salesQuery)->whereBetween('created_at', [$beginOfMonth, $endOfMonth])
                ->sum('total_amount');

            // Current Year
            $beginOfYear = strtotime(date('Y-01-01', $time));// First day of the year.
            $endOfYear = strtotime(date('Y-m-d', strtotime('12/31'))); // Last day of the year.

            $yearSalesAmount = deepClone($salesQuery)->whereBetween('created_at', [$beginOfYear, $endOfYear])
                ->sum('total_amount');

            // Total Sales
            $totalSalesAmount = deepClone($salesQuery)->sum('total_amount');

            // Chart
            for ($month = 1; $month <= 12; $month++) {
                $date = Carbon::create(date('Y'), $month);

                $startMonthDate = $date->timestamp;
                $endMonthDate = $date->copy()->endOfMonth()->timestamp;

                $chart['labels'][] = trans('panel.month_' . $month);


                $chart['courseSales'][] = deepClone($salesQuery)->whereNotNull('webinar_id')
                    ->whereBetween('created_at', [$startMonthDate, $endMonthDate])
                    ->sum('total_amount');

                $chart['meetingSales'][] = deepClone($salesQuery)->whereNotNull('meeting_id')
                    ->whereBetween('created_at', [$startMonthDate, $endMonthDate])
                    ->sum('total_amount');

                $chart['productSales'][] = deepClone($salesQuery)->whereNotNull('product_order_id')
                    ->whereBetween('created_at', [$startMonthDate, $endMonthDate])
                    ->sum('total_amount');
            }
        }

        return [
            'hasSalesOverviewData' => $hasSalesOverviewData,
            'totalCourseSales' => $totalCourseSales,
            'totalProductSales' => $totalProductSales,
            'totalMeetingSales' => $totalMeetingSales,
            'monthSalesAmount' => $monthSalesAmount,
            'yearSalesAmount' => $yearSalesAmount,
            'totalSalesAmount' => $totalSalesAmount,
            'chart' => $chart
        ];
    }

    private function getInstructorStudentAssignmentsData($user, $userWebinarsIds)
    {
        $pendingAssignmentQuery = WebinarAssignmentHistory::query()->where('status', WebinarAssignmentHistory::$pending)
            ->whereHas('assignment', function (Builder $query) use ($user, $userWebinarsIds) {
                $query->whereIn('webinar_id', $userWebinarsIds);
            })
            ->groupBy('assignment_id')
            ->with([
                'assignment',
                'student'
            ]);


        $pendingAssignments = deepClone($pendingAssignmentQuery)->where('status', 'pending')
            ->limit(3)
            ->get();

        if ($pendingAssignments->count() < 3) {
            $count = 3 - $pendingAssignments->count();

            $assignments = deepClone($pendingAssignmentQuery)->where('status', '!=', 'pending')
                ->limit($count)
                ->get();

            $assignments->each(function ($assignment) use ($pendingAssignments) {
                $pendingAssignments->push($assignment);
            });
        }

        return $pendingAssignments;
    }

    private function getInstructorSupportMessagesData($user, $userWebinarsIds): array
    {
        $query = Support::query()->select('*', DB::raw("case
            when status = 'open' then 'a'
            when status = 'replied' then 'a'
            when status = 'supporter_replied' then 'b'
            when status = 'close' then 'c'
            end as status_order
        "))->whereIn('webinar_id', $userWebinarsIds)
            ->whereHas('user');

        $totalTickets = deepClone($query)->count();
        $openTickets = deepClone($query)->where('status', '!=', 'close')->count();

        $supports = deepClone($query)
            ->orderBy('status_order', 'asc')
            ->with([
                'conversations' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'user',
            ])
            ->limit(10)
            ->get();

        return [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'supports' => $supports,
        ];
    }

    private function getInstructorVisitorsStatisticsData($user, $userWebinarsIds)
    {
        $query = VisitLog::getQueryByOwner($user);

        $time = time();

        // Current Month
        $beginOfMonth = strtotime(date('Y-m-01', $time));// First day of the month.
        $endOfMonth = strtotime(date('Y-m-t', $time));// Last day of the month.

        $monthVisitorsCount = deepClone($query)->whereBetween('visited_at', [$beginOfMonth, $endOfMonth])->count();
        $totalVisitorsCount = deepClone($query)->count();

        $chart = [
            'labels' => [],
            'datasets' => []
        ];

        $currentDate = Carbon::now();

        for ($day = 0; $day < 7; $day++) {
            $date = $currentDate->copy()->startOfWeek()->addDays($day);

            $startDate = $date->startOfDay()->timestamp;
            $endDate = $date->endOfDay()->timestamp;

            $chart['labels'][] = trans('panel.day_' . ($day + 1));

            $visits = deepClone($query)->whereBetween('visited_at', [$startDate, $endDate])->get();

            $chart['datasets'][] = $visits->count();
        }

        // Top Views
        $topViews = deepClone($query)->select('*', DB::raw("count(id) as total"))
            ->groupBy(['targetable_type', 'targetable_id'])
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get();


        return [
            'monthVisitorsCount' => $monthVisitorsCount,
            'totalVisitorsCount' => $totalVisitorsCount,
            'chart' => $chart,
            'topViews' => $topViews,
        ];
    }

    private function getInstructorUpcomingLiveSessionsData($user, $userWebinarsIds)
    {
        $time = time();
        $sessionsQuery = Session::query()->whereIn('webinar_id', $userWebinarsIds)
            ->where('status', 'active')
            ->where('date', '>', $time)
            ->whereHas('webinar')
            ->with([
                'webinar',
            ])
            ->orderBy('date', 'asc');

        $totalSessions = deepClone($sessionsQuery)->count();
        $sessions = $sessionsQuery->limit(2)->get();

        foreach ($sessions as $session) {
            $webinar = $session->webinar;
            $studentsIds = $webinar->getStudentsIds();

            $session->total_students = count($studentsIds);
            $session->participatesUsers = User::query()->select('id', 'username', 'full_name', 'avatar', 'avatar_settings')
                ->whereIn('id', $studentsIds)
                ->limit(4)
                ->get();
        }

        return [
            'totalSessions' => $totalSessions,
            'sessions' => $sessions,
        ];
    }

    private function getInstructorReviewStudentQuizzes($user, $userWebinarsIds)
    {
        $query = QuizzesResult::query()->where('status', 'waiting')
            ->whereHas('quiz', function ($query) use ($user, $userWebinarsIds) {
                $query->where('creator_id', $user->id);
            })
            ->with([
                'quiz',
                'user' => function ($query) {
                    $query->select('id', 'username', 'full_name', 'avatar', 'avatar_settings');
                }
            ])
            ->orderBy('created_at', 'asc');

        $total = deepClone($query)->count();
        $results = $query->limit(2)->get();

        return [
            'total' => $total,
            'quizResults' => $results,
        ];
    }

    private function getInstructorOpenMeetingsData($user, $userWebinarsIds)
    {
        $query = ReserveMeeting::query()
            ->whereHas('meeting', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->whereIn('status', ['open', 'pending'])
            ->whereNotNull('reserved_at')
            ->whereHas('sale', function ($query) {
                //$query->whereNull('refund_at');
            })->with([
                'user' => function ($query) {
                    $query->select('id', 'username', 'full_name', 'avatar', 'avatar_settings');
                }
            ])
            ->orderBy('start_at', 'asc');

        $totalMeetings = deepClone($query)->count();
        $reserveMeetings = $query->limit(3)->get();

        /* Meeting Package Scheduled Sessions */
        $scheduledSessionsQuery = Session::query()
            ->where('creator_id', $user->id)
            ->whereNotNull('meeting_package_sold_id')
            ->where('status', '!=', 'finished')
            ->whereNotNull('date')
            ->where('date', '>', time())
            ->with([
                'meetingPackageSold' => function ($query) {
                    $query->with([
                        'user' => function ($query) {
                            $query->select('id', 'username', 'full_name', 'avatar', 'avatar_settings');
                        }
                    ]);
                }
            ]);

        $totalScheduledSessions = deepClone($scheduledSessionsQuery)->count();

        $totalMeetings += $totalScheduledSessions;
        $meetingPackageScheduledSessions = deepClone($scheduledSessionsQuery)->limit(3)->get();

        $instructors = null;

        if ($totalMeetings < 1) {
            $instructors = User::query()->select('id', 'full_name', 'username', 'avatar', 'avatar_settings', 'email')
                ->where('users.status', 'active')
                ->whereHas('meeting', function ($query) {
                    $query->whereHas('meetingTimes');
                })
                ->inRandomOrder()
                ->limit(3)
                ->get();
        }

        return [
            'totalMeetings' => $totalMeetings,
            'reserveMeetings' => $reserveMeetings,
            'instructors' => $instructors,
            'meetingPackageScheduledSessions' => $meetingPackageScheduledSessions,
        ];
    }

    private function getOrganizationTopInstructorsData($user)
    {
        $query = User::query()->where('organ_id', $user->id)
            ->where('role_name', Role::$teacher);

        $total = deepClone($query)->count();
        $totalActive = deepClone($query)->whereHas('webinars')->count();
        $instructors = $query->limit(5)->get();


        return [
            'total' => $total,
            'totalActive' => $totalActive,
            'instructors' => $instructors,
        ];
    }

    private function getOrganizationTopStudentsData($user)
    {
        $query = User::query()->where('organ_id', $user->id)
            ->where('role_name', Role::$user);

        $total = deepClone($query)->count();
        $students = $query->limit(5)->get();

        $totalActive = deepClone($query)
            ->whereHas('timesSpentOnCourse', function ($query) {
                $query->where('page', 'learning_page');
            })
            ->count();


        return [
            'total' => $total,
            'totalActive' => $totalActive,
            'students' => $students,
        ];
    }

}
