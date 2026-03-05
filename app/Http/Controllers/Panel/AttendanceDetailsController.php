<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\CoursePersonalNote;
use App\Models\Session;
use App\Models\SessionAttendance;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceDetailsController extends Controller
{

    public function __construct()
    {
        if (empty(getAttendanceSettings('status'))) {
            abort(403);
        }
    }

    public function index(Request $request, $sessionId)
    {
        $user = auth()->user();
        $session = $this->getSessionById($user, $sessionId);

        if (!empty($session)) {
            $allStudentsIds = $session->webinar->getStudentsIds();

            $query = User::query()->select("users.*", DB::raw("session_attendance.joined_at as joined_date"));
            $query->whereIn('users.id', $allStudentsIds);
            $query->leftJoin('session_attendance', 'session_attendance.student_id', '=', 'users.id');
            $query->groupBy('users.id');

            $query = $this->handleFilters($request, $query, $session);

            $getListData = $this->getListsData($request, $query, $session);

            if ($request->ajax()) {
                return $getListData;
            }

            $data = [
                'pageTitle' => trans('update.attendance_details'),
                'session' => $session,
                'changeAttendanceStatus' => !empty(getAttendanceSettings("allow_instructor_to_change_attendance_status")),
            ];
            $data = array_merge($data, $this->getTopStats($session, $allStudentsIds));
            $data = array_merge($data, $getListData);

            return view('design_1.panel.webinars.attendances.details.index', $data);
        }

        abort(404);
    }

    private function getSessionById($user, $sessionId)
    {
        $time = time();

        $query = Session::query()->where('id', $sessionId)
            ->where('creator_id', $user->id)
            ->where('enable_attendance', true)
            ->where('status', 'active');

        $query->where(function ($query) use ($time) {
            $query->whereHas('agoraHistory', function ($qh) {
                $qh->whereNotNull('end_at');
            });
            $query->orWhereRaw('? > ((sessions.duration * 60) + sessions.date)', [$time]);
        });

        $query->with([
            'webinar',
            'attendances'
        ]);

        return $query->first();
    }

    private function getTopStats($session, $allStudentsIds): array
    {
        $totalAttendanceCount = count($allStudentsIds);
        $totalPresentRecords = $session->attendances->where('status', 'present')->count();
        $totalLateRecords = $session->attendances->where('status', 'late')->count();
        $totalAbsentRecords = $totalAttendanceCount - ($totalPresentRecords + $totalLateRecords);

        return [
            'totalAttendanceCount' => $totalAttendanceCount,
            'totalPresentRecords' => $totalPresentRecords,
            'totalLateRecords' => $totalLateRecords,
            'totalAbsentRecords' => $totalAbsentRecords > 0 ? $totalAbsentRecords : 0,
        ];
    }

    private function handleFilters(Request $request, Builder $query, $session): Builder
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $sort = $request->get('sort');

        if (!empty($search)) {
            $query->where('full_name', "like", "%$search%");
        }

        if (!empty($status)) {
            if ($status == "absent") {
                $query->whereDoesntHave('sessionAttendance', function ($query) use ($session) {
                    $query->where('session_id', $session->id);
                    $query->whereIn('status', ['present', 'late']);
                });
            } else if ($status == "present") {
                $query->whereHas('sessionAttendance', function ($query) use ($session) {
                    $query->where('session_id', $session->id);
                    $query->where('status', 'present');
                });
            } else if ($status == "late") {
                $query->whereHas('sessionAttendance', function ($query) use ($session) {
                    $query->where('session_id', $session->id);
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
            }
        } else {
            $query->orderBy('joined_date', 'desc');
        }

        return $query;
    }

    private function getListsData(Request $request, Builder $query, $session)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->get()->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $students = $query->get();

        foreach ($students as $student) {
            $studentAttendance = $session->attendances->where('student_id', $student->id)->first();

            $student->attendance = $studentAttendance;
            $student->joined_at = !empty($studentAttendance) ? $studentAttendance->joined_at : null;
        }

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $students, $total, $count, $session);
        }

        return [
            'students' => $students,
            'pagination' => $this->makePagination($request, $students, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $students, $total, $count, $session)
    {
        $changeAttendanceStatus = !empty(getAttendanceSettings("allow_instructor_to_change_attendance_status"));
        $html = "";

        foreach ($students as $studentRow) {
            $html .= (string)view()->make('design_1.panel.webinars.attendances.details.table_items', [
                'student' => $studentRow,
                'changeAttendanceStatus' => $changeAttendanceStatus,
                'session' => $session,
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $students, $total, $count, true)
        ]);
    }


    public function changeStatus($sessionId, $studentId, $status)
    {
        $user = auth()->user();
        $session = $this->getSessionById($user, $sessionId);

        if (!empty($session)) {
            $attendance = SessionAttendance::query()->where('session_id', $sessionId)
                ->where('student_id', $studentId)
                ->first();

            if (!empty($attendance)) {
                $attendance->update([
                    'status' => $status,
                    'edited_at' => time(),
                ]);
            } else {
                SessionAttendance::query()->create([
                    'session_id' => $sessionId,
                    'student_id' => $studentId,
                    'status' => $status,
                    'joined_at' => time(),
                    'edited_at' => time(),
                ]);
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.attendance_status_changed_successfully'),
            ]);
        }

        return response()->json([], 422);
    }

}
