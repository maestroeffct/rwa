<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\CoursePersonalNote;
use App\Models\Session;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AttendancesController extends Controller
{

    public function __construct()
    {
        if (empty(getAttendanceSettings('status'))) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $time = time();

        $query = Session::query()->where('creator_id', $user->id)
            ->where('enable_attendance', true)
            ->where('status', 'active');

        $query->where(function ($query) use ($time) {
            $query->whereHas('agoraHistory', function ($qh) {
                $qh->whereNotNull('end_at');
            });
            $query->orWhereRaw('? > ((sessions.duration * 60) + sessions.date)', [$time]);
        });

        $copyQuery = deepClone($query);

        $query = $this->handleFilters($request, $query);

        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $myCourses = Webinar::query()->select('id', 'teacher_id', 'creator_id')
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })->get();

        $data = [
            'pageTitle' => trans('update.attendances'),
            'myCourses' => $myCourses,
        ];
        $data = array_merge($data, $this->getTopStats($copyQuery));
        $data = array_merge($data, $getListData);

        return view('design_1.panel.webinars.attendances.lists.index', $data);
    }

    private function getTopStats(Builder $query): array
    {
        $sessions = $query
            ->with([
                'webinar',
                'attendances'
            ])
            ->withCount([
                'attendances as present_count' => function ($q) {
                    $q->where('status', 'present');
                },
                'attendances as late_count' => function ($q) {
                    $q->where('status', 'late');
                },
            ])
            ->get();

        $totalAttendanceCount = 0;
        $totalPresentRecords = 0;
        $totalLateRecords = 0;
        $totalAbsentRecords = 0;

        foreach ($sessions as $session) {
            $allStudentsIds = $session->webinar->getStudentsIds();

            $totalStudents = count($allStudentsIds);

            $absentCount = $totalStudents - ($session->present_count + $session->late_count);
            $absentCount = $absentCount > 0 ? $absentCount : 0;


            $totalAttendanceCount += $totalStudents;
            $totalPresentRecords += $session->present_count;
            $totalLateRecords += $session->late_count;
            $totalAbsentRecords += $absentCount;
        }


        return [
            'totalAttendanceCount' => $totalAttendanceCount,
            'totalPresentRecords' => $totalPresentRecords,
            'totalLateRecords' => $totalLateRecords,
            'totalAbsentRecords' => $totalAbsentRecords,
        ];
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $search = $request->get('search');
        $courseId = $request->get('course_id');
        $sort = $request->get('sort');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->whereTranslationLike('title', "%$search%");
            });
        }

        if (!empty($courseId)) {
            $query->whereHas('webinar', function ($query) use ($courseId) {
                $query->where('id', $courseId);
            });
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'present_students_asc':
                    $query->orderBy('present_count', 'asc');
                    break;
                case 'present_students_desc':
                    $query->orderBy('present_count', 'desc');
                    break;
                case 'late_students_asc':
                    $query->orderBy('late_count', 'asc');
                    break;
                case 'late_students_desc':
                    $query->orderBy('late_count', 'desc');
                    break;
                case 'session_start_date_asc':
                    $query->orderBy('date', 'asc');
                    break;
                case 'session_start_date_desc':
                    $query->orderBy('date', 'desc');
                    break;
            }
        } else {
            $query->orderBy('date', 'desc');
        }

        return $query;
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $sessions = $query
            ->with([
                'webinar',
                'attendances'
            ])
            ->withCount([
                'attendances as present_count' => function ($q) {
                    $q->where('status', 'present');
                },
                'attendances as late_count' => function ($q) {
                    $q->where('status', 'late');
                },
            ])
            ->get();

        foreach ($sessions as $session) {
            if (!empty($session->webinar)) {
                $allStudentsIds = $session->webinar->getStudentsIds();

                $session->total_students = count($allStudentsIds);

                $absentCount = $session->total_students - ($session->present_count + $session->late_count);
                $session->absent_count = $absentCount > 0 ? $absentCount : 0;
            }
        }

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $sessions, $total, $count);
        }

        return [
            'sessions' => $sessions,
            'pagination' => $this->makePagination($request, $sessions, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $sessions, $total, $count)
    {
        $html = "";

        foreach ($sessions as $sessionRow) {
            $html .= (string)view()->make('design_1.panel.webinars.attendances.lists.table_items', ['session' => $sessionRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $sessions, $total, $count, true)
        ]);
    }
}
