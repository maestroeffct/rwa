<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Comment;
use App\Models\CourseForum;
use App\Models\Gift;
use App\Models\InstallmentOrder;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\Role;
use App\Models\Sale;
use App\Models\TimeSpentOnCourse;
use App\Models\VisitLog;
use App\Models\Webinar;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentHistory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WebinarStatisticController extends Controller
{

    public function index(Request $request, $courseId)
    {
        $user = auth()->user();

        $course = Webinar::where('id', $courseId)
            ->where(function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                });

                $query->orWhereHas('webinarPartnerTeacher', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                });
            })
            ->with([
                'chapters' => function ($query) {
                    $query->where('status', 'active');
                },
                'sessions' => function ($query) {
                    $query->where('status', 'active');
                },
                'assignments' => function ($query) {
                    $query->where('status', 'active');
                },
                'quizzes' => function ($query) {
                    $query->where('status', 'active');
                },
                'files' => function ($query) {
                    $query->where('status', 'active');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->first();

        if (!empty($course)) {
            $studentsIds = Sale::where('webinar_id', $course->id)
                ->whereNull('refund_at')
                ->pluck('buyer_id')
                ->toArray();

            $gifts = Gift::query()->where('webinar_id', $course->id)
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('date');
                    $query->orWhere('date', '<', time());
                })
                ->whereHas('sale')
                ->get();

            $courseStudentsLists = $this->getStudentsLists($request, $course, $studentsIds, $gifts);

            if ($request->ajax()) {
                return $courseStudentsLists;
            }

            $topSummary = $this->getTopSummary($course, $studentsIds, $gifts);

            $data = [
                'pageTitle' => trans('update.course_statistics'),
                'course' => $course,
                'pieCharts' => $this->getPieChartsData($course, $studentsIds),
                'learningActivity' => $this->getStudentLearningActivityData($studentsIds),
                'courseProgressLineChart' => $this->handleCourseProgressLineChart($course, $studentsIds),
                'monthlySalesChart' => $this->getMonthlySalesChart($course->id),
                'visitorsChart' => $this->handleVisitorsChart($user),
                'courseStudents' => $courseStudentsLists,
            ];

            $data = array_merge($data, $topSummary);
            $data = array_merge($data, $this->getAvgStatsData($course));

            return view('design_1.panel.webinars.course_statistics.index', $data);
        }

        abort(404);
    }

    private function getTopSummary($course, $studentsIds, $gifts)
    {

        $courseWatchTimeSeconds = TimeSpentOnCourse::query()->where('course_id', $course->id)
            ->sum('seconds_spent');
        $courseWatchTimeMinutes = ($courseWatchTimeSeconds > 0) ? $courseWatchTimeSeconds / 60 : 0;

        $visitsCount = $course->visits()->count();

        return [
            'studentsCount' => count(array_unique($studentsIds)) + count($gifts),
            'commentsCount' => $this->getCommentsCount($course->id),
            'salesAmount' => $this->getSalesAmounts($course->id, $gifts->pluck('id')->toArray()),
            'courseForumsMessagesCount' => $this->getCourseForumsMessagesCount($course->id),
            'pendingAssignmentsCount' => $this->getPendingAssignmentsCount($course->id),
            'pendingQuizzesCount' => $this->getPendingQuizzesCount($course->id),
            'courseWatchTimeMinutes' => $courseWatchTimeMinutes,
            'visitsCount' => $visitsCount,
        ];
    }

    private function getAvgStatsData($course): array
    {
        return [
            'courseRate' => $course->getRate(),
            'courseRateCount' => $course->reviews->count(),
            'quizzesAverageGrade' => $this->getQuizzesAverageGrade($course->id),
            'assignmentsAverageGrade' => $this->getAssignmentsAverageGrade($course->id),
            'courseForumsMessagesCount' => $this->getCourseForumsMessagesCount($course->id),
            'courseForumsStudentsCount' => $this->getCourseForumsStudentsCount($course->id),
        ];
    }

    private function getPieChartsData($course, $studentsIds)
    {
        $studentsUserRolesChart = $this->handleStudentsUserRolesChart($studentsIds);
        $courseProgressChart = $this->handleCourseProgressChart($course, $studentsIds);
        $quizStatusChart = $this->handleQuizStatusChart($course);
        $assignmentsStatusChart = $this->handleAssignmentsStatusChart($course);

        return [
            'studentsUserRolesChart' => $studentsUserRolesChart,
            'courseProgressChart' => $courseProgressChart,
            'quizStatusChart' => $quizStatusChart,
            'assignmentsStatusChart' => $assignmentsStatusChart,
        ];
    }

    private function getStudentLearningActivityData($studentsIds): array
    {
        $learningPageActivityQuery = TimeSpentOnCourse::query()->whereIn('user_id', $studentsIds)
            ->where('page', 'learning_page');

        $time = time();
        $beginOfYear = strtotime(date('Y-01-01', $time));// First day of the year.
        $endOfYear = strtotime(date('Y-m-d', strtotime('12/31'))); // Last day of the year.


        $labels = [];
        $data = [];

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

        $totalActivitySeconds = deepClone($learningPageActivityQuery)->sum('seconds_spent');

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
            'total' => ($totalActivitySeconds > 0) ? round($totalActivitySeconds / 60, 1) : 0,
            'year' => ($yearActivitySeconds > 0) ? round($yearActivitySeconds / 60, 1) : 0,
            'month' => ($monthActivitySeconds > 0) ? round($monthActivitySeconds / 60, 1) : 0,
        ];


        $learningActivityChart = [
            'labels' => $labels,
            'data' => $data,
        ];

        return [
            'learningActivityChart' => $learningActivityChart,
            'activityStats' => $stats,
        ];
    }

    private function getCommentsCount($webinarId)
    {
        return Comment::where('webinar_id', $webinarId)
            ->where('status', 'active')
            ->count();
    }

    private function getSalesAmounts($webinarId, $giftsIds)
    {
        return Sale::query()
            ->where(function ($query) use ($webinarId, $giftsIds) {
                $query->where('webinar_id', $webinarId);
                $query->orWhereIn('gift_id', $giftsIds);
            })
            ->whereNull('refund_at')
            ->sum('total_amount');
    }

    private function getPendingQuizzesCount($webinarId)
    {
        return Quiz::where('webinar_id', $webinarId)
            ->where('status', 'active')
            ->whereHas('quizResults', function ($query) {
                $query->where('status', 'waiting');
            })
            ->count();
    }

    private function getPendingAssignmentsCount($webinarId)
    {
        return WebinarAssignment::where('webinar_id', $webinarId)
            ->where('status', 'active')
            ->whereHas('assignmentHistory', function ($query) {
                $query->where('status', 'pending');
            })
            ->count();
    }

    private function getQuizzesAverageGrade($webinarId)
    {
        $quizzes = Quiz::where('webinar_id', $webinarId)
            ->join('quizzes_results', 'quizzes_results.quiz_id', 'quizzes.id')
            ->select(DB::raw('avg(quizzes_results.user_grade) as result_grade'))
            ->whereIn('quizzes_results.status', ['passed', 'failed'])
            ->groupBy('quizzes_results.quiz_id')
            ->get();

        return $quizzes->avg('result_grade');
    }

    private function getAssignmentsAverageGrade($webinarId)
    {
        $assignments = WebinarAssignment::where('webinar_id', $webinarId)
            ->join('webinar_assignment_history', 'webinar_assignment_history.assignment_id', 'webinar_assignments.id')
            ->select(DB::raw('avg(webinar_assignment_history.grade) as result_grade'))
            ->whereIn('webinar_assignment_history.status', ['passed', 'not_passed'])
            ->groupBy('webinar_assignment_history.assignment_id')
            ->get();

        return $assignments->avg('result_grade') ?? 0;
    }

    private function getCourseForumsMessagesCount($webinarId)
    {
        $forums = CourseForum::where('webinar_id', $webinarId)
            ->join('course_forum_answers', 'course_forum_answers.forum_id', 'course_forums.id')
            ->select(DB::raw('count(course_forum_answers.id) as count'))
            ->groupBy('course_forum_answers.forum_id')
            ->get();

        return $forums->sum('count') ?? 0;
    }

    private function getCourseForumsStudentsCount($webinarId)
    {
        $forums = CourseForum::where('webinar_id', $webinarId)
            ->join('course_forum_answers', 'course_forum_answers.forum_id', 'course_forums.id')
            ->select(DB::raw('count(distinct course_forum_answers.user_id) as count'))
            ->groupBy('course_forum_answers.forum_id')
            ->get();

        return $forums->sum('count') ?? 0;
    }

    private function handleStudentsUserRolesChart($studentsIds)
    {
        $labels = [
            trans('public.students'),
            trans('public.instructors'),
            trans('home.organizations'),
        ];

        $users = User::whereIn('id', $studentsIds)
            ->select('id', 'role_name', DB::raw('count(id) as count'))
            ->groupBy('role_name')
            ->get();

        $data = [0, 0, 0];
        $hasData = false;

        foreach ($users as $user) {
            if ($user->count > 0) {
                $hasData = true;
            }

            if ($user->role_name == Role::$user) {
                $data[0] = $user->count;
            } else if ($user->role_name == Role::$teacher) {
                $data[1] = $user->count;
            } else if ($user->role_name == Role::$organization) {
                $data[2] = $user->count;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'hasData' => $hasData,
        ];
    }

    private function handleQuizStatusChart($webinar)
    {
        $labels = [
            trans('quiz.passed'),
            trans('public.pending'),
            trans('quiz.failed'),
        ];

        $hasData = false;

        $data[0] = 0; // passed
        $data[1] = 0; // pending
        $data[2] = 0; // failed

        $quizzes = $webinar->quizzes;

        foreach ($quizzes as $quiz) {
            $passed = $quiz->quizResults()->where('status', QuizzesResult::$passed)->count();
            $pending = $quiz->quizResults()->where('status', QuizzesResult::$waiting)->count();
            $failed = $quiz->quizResults()->where('status', QuizzesResult::$failed)->count();

            $data[0] += $passed;
            $data[1] += $pending;
            $data[2] += $failed;

            if (($passed + $pending + $failed) > 0) {
                $hasData = true;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'hasData' => $hasData,
        ];
    }

    private function handleAssignmentsStatusChart($webinar)
    {
        $labels = [
            trans('quiz.passed'),
            trans('public.pending'),
            trans('quiz.failed'),
        ];

        $hasData = false;

        $data[0] = 0; // passed
        $data[1] = 0; // pending
        $data[2] = 0; // failed

        $assignments = $webinar->assignments;

        foreach ($assignments as $quiz) {
            $passed = $quiz->assignmentHistory()->where('status', WebinarAssignmentHistory::$passed)->count();
            $pending = $quiz->assignmentHistory()->where('status', WebinarAssignmentHistory::$pending)->count();
            $failed = $quiz->assignmentHistory()->where('status', WebinarAssignmentHistory::$notPassed)->count();

            $data[0] += $passed;
            $data[1] += $pending;
            $data[2] += $failed;

            if (($passed + $pending + $failed) > 0) {
                $hasData = true;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'hasData' => $hasData,
        ];
    }

    private function getMonthlySalesChart($webinarId)
    {
        $labels = [];
        $data = [];

        $salesQuery = Sale::query()->whereNull('refund_at')
            ->where('webinar_id', $webinarId);

        $time = time();
        $topStats = [];

        // Total Sales
        $topStats['total'] = deepClone($salesQuery)->sum('total_amount');


        // Current Month Sales
        $beginOfMonth = strtotime(date('Y-m-01', $time));// First day of the month.
        $endOfMonth = strtotime(date('Y-m-t', $time));// Last day of the month.

        $topStats['month'] = deepClone($salesQuery)->whereBetween('created_at', [$beginOfMonth, $endOfMonth])
            ->sum('total_amount');

        // Current Year
        $beginOfYear = strtotime(date('Y-01-01', $time));// First day of the year.
        $endOfYear = strtotime(date('Y-m-d', strtotime('12/31'))); // Last day of the year.

        $topStats['year'] = deepClone($salesQuery)->whereBetween('created_at', [$beginOfYear, $endOfYear])
            ->sum('total_amount');


        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(date('Y'), $month);

            $start_date = $date->timestamp;
            $end_date = $date->copy()->endOfMonth()->timestamp;

            $labels[] = trans('panel.month_' . $month);

            $amount = deepClone($salesQuery)->whereBetween('created_at', [$start_date, $end_date])
                ->sum('total_amount');

            $data[] = round($amount, 2);
        }

        $chart = [
            'labels' => $labels,
            'data' => $data
        ];

        return [
            'chart' => $chart,
            'topStats' => $topStats,
        ];
    }

    public function getCourseProgressForStudent($webinar, $userId)
    {
        $progress = 0;

        $filesStat = $webinar->getFilesLearningProgressStat($userId);
        $sessionsStat = $webinar->getSessionsLearningProgressStat($userId);
        $textLessonsStat = $webinar->getTextLessonsLearningProgressStat($userId);
        $assignmentsStat = $webinar->getAssignmentsLearningProgressStat($userId);
        $quizzesStat = $webinar->getQuizzesLearningProgressStat($userId);

        $passed = $filesStat['passed'] + $sessionsStat['passed'] + $textLessonsStat['passed'] + $assignmentsStat['passed'] + $quizzesStat['passed'];
        $count = $filesStat['count'] + $sessionsStat['count'] + $textLessonsStat['count'] + $assignmentsStat['count'] + $quizzesStat['count'];

        if ($passed > 0 and $count > 0) {
            $progress = ($passed * 100) / $count;
        }

        return round($progress, 2);
    }

    public function handleCourseProgressChart($webinar, $studentsIds)
    {
        $labels = [
            trans('update.completed'),
            trans('webinars.in_progress'),
            trans('update.not_started'),
        ];

        $data[0] = 0; // completed
        $data[1] = 0; // in_progress
        $data[2] = 0; // not_started

        $hasData = false;

        foreach ($studentsIds as $userId) {

            $progress = $this->getCourseProgressForStudent($webinar, $userId);

            if ($progress > 0 and $progress < 100) {
                $data[1] += 1;

                $hasData = true;
            } elseif ($progress == 100) {
                $data[0] += 1;

                $hasData = true;
            } else {
                $data[2] += 1;

                $hasData = true;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'hasData' => $hasData,
        ];
    }

    public function handleCourseProgressLineChart($webinar, $studentsIds)
    {
        $labels = [];
        $data = [];

        $progress = [];

        $topStats = [
            'notStarted' => 0,
            'pending' => 0,
            'completed' => 0,
        ];

        foreach ($studentsIds as $userId) {
            $percent = $this->getCourseProgressForStudent($webinar, $userId);
            $progress[] = $percent;

            if ($percent >= 100) {
                $topStats['completed'] += 1;
            } else if ($percent < 100 and $percent > 0) {
                $topStats['pending'] += 1;
            } else {
                $topStats['notStarted'] += 1;
            }
        }

        for ($percent = 0; $percent < 100; $percent += 10) {
            $endPercent = $percent + 10;
            $labels[] = $percent . '-' . $endPercent;

            $count = 0;

            foreach ($progress as $value) {
                if ($value >= $percent and $value < $endPercent) {
                    $count += 1;
                }
            }

            $data[] = $count;
        }

        $chart = [
            'labels' => $labels,
            'data' => $data
        ];

        return [
            'chart' => $chart,
            'topStats' => $topStats,
        ];
    }

    public function handleVisitorsChart($user)
    {
        $query = VisitLog::getQueryByOwner($user);

        $time = time();
        $topStats = [];

        // Total Sales
        $topStats['total'] = deepClone($query)->count();

        // Current Month Sales
        $beginOfMonth = strtotime(date('Y-m-01', $time));// First day of the month.
        $endOfMonth = strtotime(date('Y-m-t', $time));// Last day of the month.

        $topStats['month'] = deepClone($query)->whereBetween('visited_at', [$beginOfMonth, $endOfMonth])
            ->count();

        // Current Year
        $beginOfYear = strtotime(date('Y-01-01', $time));// First day of the year.
        $endOfYear = strtotime(date('Y-m-d', strtotime('12/31'))); // Last day of the year.

        $topStats['year'] = deepClone($query)->whereBetween('visited_at', [$beginOfYear, $endOfYear])
            ->count();


        $labels = [];
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(date('Y'), $month);

            $startDate = $date->timestamp;
            $endDate = $date->copy()->endOfMonth()->timestamp;

            $labels[] = trans('panel.month_' . $month);

            $count = deepClone($query)->whereBetween('visited_at', [$startDate, $endDate])->count();

            $data[] = $count;
        }


        $chart = [
            'labels' => $labels,
            'data' => $data
        ];

        return [
            'chart' => $chart,
            'topStats' => $topStats,
        ];
    }


    private function getStudentsLists(Request $request, $course, $studentsIds, $gifts)
    {
        $receiptsGift = [];
        $unregisteredGift = [];

        foreach ($gifts as $gift) {
            $receipt = $gift->receipt;

            if (!empty($receipt)) {
                $receiptsGift[] = $receipt->id;
            } else {
                $unregisteredGift[] = $gift;
            }
        }

        $studentsIds = array_merge($studentsIds, $receiptsGift);

        $query = User::query()->whereIn('users.id', $studentsIds);
        $query->join('sales', function ($join) use ($course) {
            $join->on("users.id", '=', "sales.buyer_id");
            $join->where('webinar_id', $course->id);
            $join->whereNull('refund_at');
        });

        $query->leftJoin('certificates', function ($join) {
            $join->on("users.id", '=', "certificates.student_id");
        });

        $query->groupBy('users.id');
        $query->select('users.*', DB::raw("sales.created_at as purchased_at"), DB::raw('count(certificates.id) as certificatesCount'));

        $query = $this->handleStudentsListsFilters($request, $query);

        return $this->getStudentsItemsData($request, $query, $course);
    }

    private function handleStudentsListsFilters(Request $request, $query)
    {
        $search = $request->get('search');
        $date_range = $request->get('date_range');
        $sort = $request->get('sort');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('users.full_name', 'like', '%' . $search . '%');
            });
        }

        if (!empty($date_range)) {
            $dateRange = explode('-', $date_range);
            $from = $dateRange[0];
            $to = $dateRange[1];

            $query = fromAndToDateFilter($from, $to, $query, 'sales.created_at');
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'certificates_asc':
                    $query->orderBy('certificatesCount', 'asc');
                    break;
                case 'certificates_desc':
                    $query->orderBy('certificatesCount', 'desc');
                    break;
                case 'enrollment_date_asc':
                    $query->orderBy('purchased_at', 'asc');
                    break;
                case 'enrollment_date_desc':
                    $query->orderBy('purchased_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('purchased_at', 'desc');
        }

        return $query;
    }

    private function getStudentsItemsData(Request $request, $query, $course)
    {
        $page = $request->get('page') ?? 1;
        $count = 10;

        $cloneQuery = deepClone($query);
        $total = DB::table(DB::raw("({$cloneQuery->toSql()}) as sub"))
            ->mergeBindings($cloneQuery->getQuery()) // bind parameters
            ->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $students = $query->get();

        $quizzesIds = $course->quizzes->pluck('id')->toArray();
        $assignmentsIds = $course->assignments->pluck('id')->toArray();

        foreach ($students as $user) {
            $user->learning_activity = $user->getLearningActivity("min");

            $user->course_progress = $this->getCourseProgressForStudent($course, $user->id);

            $user->passed_quizzes = Quiz::whereIn('quizzes.id', $quizzesIds)
                ->join('quizzes_results', 'quizzes_results.quiz_id', 'quizzes.id')
                ->select(DB::raw('count(quizzes_results.id) as count'))
                ->where('quizzes_results.user_id', $user->id)
                ->where('quizzes_results.status', QuizzesResult::$passed)
                ->first()->count;

            $assignmentsQuery = WebinarAssignmentHistory::query()->whereIn('assignment_id', $assignmentsIds)
                ->where('student_id', $user->id);

            $user->total_assignments = deepClone($assignmentsQuery)->count();
            $user->passed_assignments = deepClone($assignmentsQuery)
                ->where('status', WebinarAssignmentHistory::$passed)
                ->count();

        }

        if ($request->ajax()) {
            return $this->getStudentsListsAjaxResponse($request, $students, $total, $count);
        }

        return [
            'students' => $students,
            'pagination' => $this->makePagination($request, $students, $total, $count, true),
        ];
    }

    private function getStudentsListsAjaxResponse(Request $request, $students, $total, $count)
    {
        $html = "";

        foreach ($students as $studentRow) {
            $html .= (string)view()->make('design_1.panel.webinars.course_statistics.students.item_table', ['student' => $studentRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $students, $total, $count, true),
            'no_result' => (empty($students) or count($students) < 1)
        ]);
    }

}
