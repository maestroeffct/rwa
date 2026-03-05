<?php

namespace App\Http\Controllers\Panel\Traits;


use App\Models\Sale;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentHistory;
use App\Models\WebinarAssignmentHistoryMessage;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait AssignmentTrait
{

    private function getAssignmentDeadline(&$assignment, $user)
    {
        $assignment->deadlineTime = null;

        if (!empty($assignment->deadline)) {
            $sale = Sale::where('buyer_id', $user->id)
                ->where('webinar_id', $assignment->webinar_id)
                ->whereNull('refund_at')
                ->first();

            if (!empty($sale)) {
                $assignment->deadlineTime = strtotime("+{$assignment->deadline} days", $sale->created_at);
            }
        }

        return $assignment;
    }

    private function handleMyAssignmentsFilters(Request $request, $query, $user)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $webinarId = $request->get('webinar_id');
        $courseType = $request->get('course_type');
        $instructor_id = $request->get('instructor_id');
        $search = $request->get('search');
        $status = $request->get('status');

        // $from and $to
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($webinarId)) {
            $query->where('webinar_id', $webinarId);
        }

        if (!empty($courseType)) {
            $query->whereHas('webinar', function ($q) use ($courseType) {
                $q->where('type', $courseType);
            });
        }

        if (!empty($instructor_id)) {
            $query->whereHas('webinar', function ($q) use ($instructor_id) {
                $q->where('teacher_id', $instructor_id);
            });
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereTranslationLike('title', "%$search%");
                $q->orWhereTranslationLike('description', "%$search%");
            });
        }

        if (!empty($status)) {
            $query->whereHas('assignmentHistory', function ($query) use ($user, $status) {
                $query->where('student_id', $user->id);
                $query->where('status', $status);
            });
        }

        return $query;
    }

    private function getMyAssignmentsListTopStats($copyQuery, $user, $purchasedCoursesIds)
    {
        $courseAssignmentsCount = deepClone($copyQuery)->count();

        $pendingReviewCount = deepClone($copyQuery)->whereHas('assignmentHistory', function ($query) use ($user) {
            $query->where('student_id', $user->id);
            $query->where('status', WebinarAssignmentHistory::$pending);
        })->count();

        $passedCount = deepClone($copyQuery)->whereHas('assignmentHistory', function ($query) use ($user) {
            $query->where('student_id', $user->id);
            $query->where('status', WebinarAssignmentHistory::$passed);
        })->count();

        $failedCount = deepClone($copyQuery)->whereHas('assignmentHistory', function ($query) use ($user) {
            $query->where('student_id', $user->id);
            $query->where('status', WebinarAssignmentHistory::$notPassed);
        })->count();

        $pendingAssignments = WebinarAssignment::query()->whereIn('webinar_id', $purchasedCoursesIds)
            ->where('status', 'active')
            ->where(function (Builder $query) use ($user) {
                $query->whereDoesntHave('assignmentHistory', function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                });

                $query->orWhereHas('assignmentHistory', function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                    $query->where('status', WebinarAssignmentHistory::$notSubmitted);
                });
            })
            ->get();

        foreach ($pendingAssignments as $key => $pendingAssignment) {
            $itemDeadline = $pendingAssignment->getDeadlineTimestamp($user);

            if (is_bool($itemDeadline) and !$itemDeadline) {
                $pendingAssignments->forget($key);
            } elseif (!empty($itemDeadline) and $itemDeadline < time()) {
                $pendingAssignments->forget($key);
            }

            $pendingAssignment = $this->getAssignmentDeadline($pendingAssignment, $user);
        }

        return [
            'courseAssignmentsCount' => $courseAssignmentsCount,
            'pendingReviewCount' => $pendingReviewCount,
            'passedCount' => $passedCount,
            'failedCount' => $failedCount,
            'pendingAssignments' => $pendingAssignments,
        ];
    }

    private function getMyAssignmentsListData(Request $request, $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $assignments = $query
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
            ->get();

        foreach ($assignments as &$assignment) {

            $assignment->deadlineTime = $assignment->getDeadlineTimestamp($user);

            $assignment->usedAttemptsCount = 0;

            if (!empty($assignment->assignmentHistory) and count($assignment->assignmentHistory->messages)) {
                try {
                    $assignment->last_submission = $assignment->assignmentHistory->messages->first()->created_at;
                    $assignment->first_submission = $assignment->assignmentHistory->messages->last()->created_at;
                    $assignment->usedAttemptsCount = $assignment->assignmentHistory->messages->count();
                } catch (\Exception $exception) {

                }
            }
        }

        if ($request->ajax()) {
            return $this->getMyAssignmentsAjaxResponse($request, $assignments, $total, $count);
        }

        return [
            'assignments' => $assignments,
            'pagination' => $this->makePagination($request, $assignments, $total, $count, true),
        ];
    }

    private function getMyAssignmentsAjaxResponse(Request $request, $assignments, $total, $count)
    {
        $html = "";

        foreach ($assignments as $assignment) {
            $html .= (string)view()->make('design_1.panel.assignments.my_assignments.table_items', ['assignment' => $assignment]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $assignments, $total, $count, true)
        ]);
    }

    private function handleAssignmentStudentsFilters(Request $request, $query)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $studentId = $request->get('student_id');
        $status = $request->get('status');

        // $from and $to
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($studentId)) {
            $query->where('student_id', $studentId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }


    private function myCoursesAssignmentsListTopStats($user)
    {
        $query = WebinarAssignment::query()->where('creator_id', $user->id);
        $ids = deepClone($query)->pluck('id')->toArray();
        $historyIds = WebinarAssignmentHistory::query()->whereIn('assignment_id', $ids)->pluck('id')->toArray();

        $totalAssignmentsCount = deepClone($query)->count();
        $activeAssignmentsCount = deepClone($query)->where('status', 'active')->count();
        $disabledAssignmentsCount = deepClone($query)->where('status', 'inactive')->count();

        $totalSubmissionsCount = WebinarAssignmentHistoryMessage::whereIn('assignment_history_id', $historyIds)
            ->where('sender_id', '!=', $user->id)
            ->count();

        return [
            'totalAssignmentsCount' => $totalAssignmentsCount,
            'activeAssignmentsCount' => $activeAssignmentsCount,
            'disabledAssignmentsCount' => $disabledAssignmentsCount,
            'totalSubmissionsCount' => $totalSubmissionsCount,
        ];
    }

    private function myCoursesAssignmentsListData(Request $request, $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $assignments = $query
            ->with([
                'webinar',
                'instructorAssignmentHistories' => function ($query) use ($user) {
                    $query->where('instructor_id', $user->id);
                },
            ])
            ->get();

        foreach ($assignments as &$assignment) {
            $grades = $assignment->instructorAssignmentHistories->filter(function ($item) {
                return !is_null($item->grade);
            });

            $historyIds = $assignment->instructorAssignmentHistories->pluck('id')->toArray();

            $assignment->min_grade = count($grades) ? $grades->min('grade') : null;
            $assignment->average_grade = count($grades) ? $grades->avg('grade') : null;


            $submissionQuery = WebinarAssignmentHistoryMessage::query()->whereIn('assignment_history_id', $historyIds)
                ->where('sender_id', '!=', $user->id)
                ->orderBy('created_at', 'desc');

            $assignment->submissions = deepClone($submissionQuery)->count();

            $assignment->lastSubmission = deepClone($submissionQuery)->first();

            $assignment->pendingCount = $assignment->instructorAssignmentHistories->where('status', WebinarAssignmentHistory::$pending)->count();
            $assignment->passedCount = $assignment->instructorAssignmentHistories->where('status', WebinarAssignmentHistory::$passed)->count();
            $assignment->failedCount = $assignment->instructorAssignmentHistories->where('status', WebinarAssignmentHistory::$notPassed)->count();
        }

        if ($request->ajax()) {
            return $this->myCoursesAssignmentsAjaxResponse($request, $assignments, $total, $count);
        }

        return [
            'assignments' => $assignments,
            'pagination' => $this->makePagination($request, $assignments, $total, $count, true),
        ];
    }

    private function myCoursesAssignmentsAjaxResponse(Request $request, $assignments, $total, $count)
    {
        $html = "";

        foreach ($assignments as $assignment) {
            $html .= (string)view()->make('design_1.panel.assignments.my-courses-assignments.item_table', ['assignment' => $assignment]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $assignments, $total, $count, true)
        ]);
    }

    private function myCoursesAssignmentsAllHistoriesListData(Request $request, $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $assignmentHistories = $query
            ->with([
                'assignment' => function ($query) use ($user) {
                    $query->with([
                        'webinar',
                        'instructorAssignmentHistories' => function ($query) use ($user) {
                            $query->where('instructor_id', $user->id);
                        },
                    ]);
                },
                'student' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
                },
                'messages'
            ])
            ->get();

        foreach ($assignmentHistories as &$assignmentHistory) {
            $assignment = $assignmentHistory->assignment;
            $assignmentSale = $assignment->getSale($assignmentHistory->student);

            $assignmentHistory->purchase_date = !empty($assignmentSale) ? $assignmentSale->created_at : null;

            $submissionQuery = WebinarAssignmentHistoryMessage::query()->where('assignment_history_id', $assignmentHistory->id)
                ->where('sender_id', '!=', $user->id);

            $assignmentHistory->firstSubmission = deepClone($submissionQuery)->orderBy('created_at', 'asc')->first();

            $assignmentHistory->lastSubmission = deepClone($submissionQuery)->orderBy('created_at', 'desc')->first();

            $assignmentHistory->attempts = $assignment->attempts;
            $assignmentHistory->usedAttemptsCount = $assignmentHistory->messages->count();

        }

        if ($request->ajax()) {
            return $this->myCoursesAssignmentsAllHistoriesAjaxResponse($request, $assignmentHistories, $total, $count);
        }

        return [
            'assignmentHistories' => $assignmentHistories,
            'pagination' => $this->makePagination($request, $assignmentHistories, $total, $count, true),
        ];
    }

    private function myCoursesAssignmentsAllHistoriesAjaxResponse(Request $request, $assignmentHistories, $total, $count)
    {
        $html = "";

        foreach ($assignmentHistories as $assignmentHistory) {
            $html .= (string)view()->make('design_1.panel.assignments.histories.item_table', ['assignmentHistory' => $assignmentHistory]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $assignmentHistories, $total, $count, true)
        ]);
    }

    private function myCoursesAssignmentsAllHistoriesTopStats($user)
    {
        $query = WebinarAssignmentHistory::query()->where('instructor_id', $user->id);

        $totalSubmissionsCount = deepClone($query)->count();
        $passedAssignmentsCount = deepClone($query)->where('status', 'passed')->count();
        $failedAssignmentsCount = deepClone($query)->where('status', 'not_passed')->count();
        $pendingReviewAssignmentsCount = deepClone($query)->where('status', 'pending')->count();

        return [
            'totalSubmissionsCount' => $totalSubmissionsCount,
            'passedAssignmentsCount' => $passedAssignmentsCount,
            'failedAssignmentsCount' => $failedAssignmentsCount,
            'pendingReviewAssignmentsCount' => $pendingReviewAssignmentsCount,
        ];
    }

    private function assignmentStudentsListData(Request $request, $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $assignmentHistories = $query
            ->with([
                'assignment' => function ($query) use ($user) {
                    $query->with([
                        'webinar',
                        'instructorAssignmentHistories' => function ($query) use ($user) {
                            $query->where('instructor_id', $user->id);
                        },
                    ]);
                },
                'student' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
                },
                'messages'
            ])
            ->get();

        foreach ($assignmentHistories as &$assignmentHistory) {
            $assignment = $assignmentHistory->assignment;
            $assignmentSale = $assignment->getSale($assignmentHistory->student);

            $assignmentHistory->purchase_date = !empty($assignmentSale) ? $assignmentSale->created_at : null;

            $submissionQuery = WebinarAssignmentHistoryMessage::query()->where('assignment_history_id', $assignmentHistory->id)
                ->where('sender_id', '!=', $user->id);

            $assignmentHistory->firstSubmission = deepClone($submissionQuery)->orderBy('created_at', 'asc')->first();

            $assignmentHistory->lastSubmission = deepClone($submissionQuery)->orderBy('created_at', 'desc')->first();

            $assignmentHistory->attempts = $assignment->attempts;
            $assignmentHistory->usedAttemptsCount = $assignmentHistory->messages->count();

        }

        if ($request->ajax()) {
            return $this->assignmentStudentsAjaxResponse($request, $assignmentHistories, $total, $count);
        }

        return [
            'assignmentHistories' => $assignmentHistories,
            'pagination' => $this->makePagination($request, $assignmentHistories, $total, $count, true),
        ];
    }

    private function assignmentStudentsAjaxResponse(Request $request, $assignmentHistories, $total, $count)
    {
        $html = "";

        foreach ($assignmentHistories as $assignmentHistory) {
            $html .= (string)view()->make('design_1.panel.assignments.students.item_table', ['assignmentHistory' => $assignmentHistory]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $assignmentHistories, $total, $count, true)
        ]);
    }

    private function assignmentStudentsTopStats($assignment, $user): array
    {
        $query = WebinarAssignmentHistory::query()->where('assignment_id', $assignment->id);
        /*$submissionsQuery = WebinarAssignmentHistoryMessage::whereIn('assignment_history_id', $historyIds)
            ->where('sender_id', '!=', $user->id);*/

        $courseStudentsIds = $assignment->webinar->getStudentsIds();

        $totalSubmissionsCount = deepClone($query)->count();
        $totalPassedSubmissions = deepClone($query)->where('status', 'passed')->count();
        $totalFailedSubmissions = deepClone($query)->where('status', 'not_passed')->count();
        $totalPendingSubmissions = deepClone($query)->where('status', 'pending')->count();
        $successRatePercent = ($totalPassedSubmissions > 0 and $totalSubmissionsCount > 0) ? (($totalPassedSubmissions / $totalSubmissionsCount) * 100) : 0;

        $totalNotSubmitted = count($courseStudentsIds) - (deepClone($query)->where('status', '!=', 'not_submitted')->count());

        return [
            'totalSubmissionsCount' => $totalSubmissionsCount,
            'totalPassedSubmissions' => $totalPassedSubmissions,
            'totalFailedSubmissions' => $totalFailedSubmissions,
            'totalPendingSubmissions' => $totalPendingSubmissions,
            'totalNotSubmitted' => ($totalNotSubmitted > 0) ? $totalNotSubmitted : 0,
            'successRatePercent' => round($successRatePercent, 2),
        ];
    }

    private function myCoursesMostActiveAssignments($user)
    {
        $query = WebinarAssignment::query()->where('creator_id', $user->id);
        $query->join('webinar_assignment_history', 'webinar_assignment_history.assignment_id', '=', 'webinar_assignments.id');
        $query->select('webinar_assignments.*', DB::raw('count(webinar_assignment_history.id) as history_count'));
        $query->groupBy('webinar_assignments.id');
        $query->orderBy('history_count', 'desc');
        $query->limit(4);
        $query->with([
            'instructorAssignmentHistories',
            'webinar'
        ]);
        $query->withCount([
            'instructorAssignmentHistories'
        ]);

        $assignments = $query->get();

        foreach ($assignments as &$assignment) {
            $userIds = WebinarAssignmentHistory::query()->where('assignment_id', $assignment->id)
                ->inRandomOrder()
                ->limit(4)
                ->pluck('student_id')
                ->toArray();

            $assignment->someStudents = User::query()->select('id', 'full_name', 'avatar', 'avatar_settings')
                ->whereIn('id', $userIds)
                ->get();
        }

        return $assignments;
    }

    private function pendingReviewAssignments($user, $assignment = null)
    {
        $query = WebinarAssignmentHistory::query()->where('instructor_id', $user->id);

        if (!empty($assignment)) {
            $query->where('assignment_id', $assignment->id);
        }

        $query->where('status', 'pending');
        $query->orderBy('created_at', 'desc');
        $query->limit(4);
        $query->with([
            'assignment' => function ($query) use ($user) {
                $query->with([
                    'webinar',
                ]);
            },
            'student' => function ($query) {
                $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
            }
        ]);

        return $query->get();
    }

}
