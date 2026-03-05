<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\CoursePersonalNote;
use App\Models\Session;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyAttendancesController extends Controller
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
        $coursesIds = $user->getPurchasedCoursesIds();

        $query = Session::query()->select("sessions.*", DB::raw("session_attendance.joined_at as joined_date"))
            ->whereIn('sessions.webinar_id', $coursesIds)
            ->where('sessions.enable_attendance', true)
            ->where('sessions.status', 'active');

        $query->leftJoin('session_attendance', function ($join) use ($user) {
            $join->on('sessions.id', '=', 'session_attendance.session_id');
            $join->where('session_attendance.student_id', '=', $user->id);
        });

        $query->where(function ($query) use ($time) {
            $query->whereHas('agoraHistory', function ($qh) {
                $qh->whereNotNull('end_at');
            });
            $query->orWhereRaw('? > ((sessions.duration * 60) + sessions.date)', [$time]);
        });


        $copyQuery = deepClone($query);

        $query = $this->handleFilters($request, $query, $user);

        $getListData = $this->getListsData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }


        $data = [
            'pageTitle' => trans('update.attendances'),
        ];
        $data = array_merge($data, $this->getTopStats($copyQuery, $user));
        $data = array_merge($data, $getListData);

        return view('design_1.panel.webinars.attendances.my_attendance.index', $data);
    }

    private function getTopStats(Builder $query, $user): array
    {
        $sessions = $query
            ->with([
                'attendances'
            ])
            ->get();

        $totalLiveSessions = $sessions->count();
        $totalPresentSessions = 0;
        $totalLateSessions = 0;
        $totalAbsentSessions = 0;

        foreach ($sessions as $session) {
            $myAttendance = $session->attendances->where('student_id', $user->id)->first();

            $status = "absent";

            if (!empty($myAttendance)) {
                $status = $myAttendance->status;
            }

            if ($status == "present") {
                $totalPresentSessions += 1;
            } else if ($status == "late") {
                $totalLateSessions += 1;
            } else {
                $totalAbsentSessions += 1;
            }
        }


        return [
            'totalLiveSessions' => $totalLiveSessions,
            'totalPresentSessions' => $totalPresentSessions,
            'totalLateSessions' => $totalLateSessions,
            'totalAbsentSessions' => $totalAbsentSessions,
        ];
    }

    private function handleFilters(Request $request, Builder $query, $user): Builder
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $sort = $request->get('sort');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->whereTranslationLike('title', "%$search%");
            });
        }

        if (!empty($status)) {
            if ($status == "absent") {
                $query->whereDoesntHave('attendances', function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                    $query->whereIn('status', ['present', 'late']);
                });
            } else if ($status == "present") {
                $query->whereHas('attendances', function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                    $query->where('status', 'present');
                });
            } else if ($status == "late") {
                $query->whereHas('attendances', function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                    $query->where('status', 'late');
                });
            }
        }


        if (!empty($sort)) {
            switch ($sort) {
                case 'joined_date_asc':
                    $query->orderBy('joined_date', 'asc');
                    break;
                case 'joined_date_desc':
                    $query->orderBy('joined_date', 'desc');
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

    private function getListsData(Request $request, Builder $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $sessions = $query
            ->with([
                'webinar',
                'creator' => function ($query) {
                    $query->select('id', 'username', 'full_name', 'role_id', 'role_name', 'email', 'mobile', 'avatar', 'avatar_settings');
                },
                'attendances'
            ])
            ->get();

        foreach ($sessions as $session) {
            $session->myAttendance = $session->attendances->where('student_id', $user->id)->first();
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
            $html .= (string)view()->make('design_1.panel.webinars.attendances.my_attendance.table_items', ['session' => $sessionRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $sessions, $total, $count, true)
        ]);
    }
}
