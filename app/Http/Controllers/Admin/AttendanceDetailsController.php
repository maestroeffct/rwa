<?php

namespace App\Http\Controllers\Admin;


use App\Exports\AttendanceHistoryDetailsExport;
use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\SessionAttendance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceDetailsController extends Controller
{


    public function index(Request $request, $sessionId)
    {
        $this->authorize("admin_attendances_history_details");


        $session = $this->getSessionQuery($sessionId)->first();

        if (!empty($session)) {
            $allStudentsIds = $session->webinar->getStudentsIds();

            $query = User::query()->select("users.*", DB::raw("session_attendance.joined_at as joined_date"));
            $query->whereIn('users.id', $allStudentsIds);
            $query->leftJoin('session_attendance', 'session_attendance.student_id', '=', 'users.id');
            $query->groupBy('users.id');

            $query = $this->handleFilters($request, $query, $session);

            $students = $query->paginate(10);

            foreach ($students as $student) {
                $studentAttendance = $session->attendances->where('student_id', $student->id)->first();

                $student->attendance = $studentAttendance;
                $student->joined_at = !empty($studentAttendance) ? $studentAttendance->joined_at : null;
            }

            $data = [
                'pageTitle' => trans('update.attendances_history'),
                'session' => $session,
                'students' => $students,
            ];
            $data = array_merge($data, $this->getPageTopStats($session, $allStudentsIds));

            return view("admin.attendances.details.index", $data);
        }

        abort(404);
    }

    private function getSessionQuery($sessionId)
    {
        $time = time();
        $query = Session::query()
            ->where('enable_attendance', true)
            ->where('status', 'active');

        $query->where('id', $sessionId);

        $query->where(function ($query) use ($time) {
            $query->whereHas('agoraHistory', function ($qh) {
                $qh->whereNotNull('end_at');
            });
            $query->orWhereRaw('? > ((sessions.duration * 60) + sessions.date)', [$time]);
        });

        $query->with([
            'webinar',
            'attendances',
            'creator' => function ($query) {
                $query->select('id', 'username', 'full_name', 'email', 'mobile', 'role_id', 'role_name', 'avatar', 'avatar_settings');
            },
        ]);

        return $query;
    }

    private function getPageTopStats($session, $allStudentsIds): array
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

    private function handleFilters(Request $request, $query, $session)
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

    public function changeStatus($sessionId, $studentId, $status)
    {
        $this->authorize("admin_attendances_history_details");
        $session = $this->getSessionQuery($sessionId)->first();

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

    public function exportExcel(Request $request, $sessionId)
    {
        $this->authorize("admin_attendances_history_details");

        $session = $this->getSessionQuery($sessionId)->first();

        if (!empty($session)) {
            $allStudentsIds = $session->webinar->getStudentsIds();

            $query = User::query()->select("users.*", DB::raw("session_attendance.joined_at as joined_date"));
            $query->whereIn('users.id', $allStudentsIds);
            $query->leftJoin('session_attendance', 'session_attendance.student_id', '=', 'users.id');

            $query = $this->handleFilters($request, $query, $session);

            $students = $query->paginate(10);

            foreach ($students as $student) {
                $studentAttendance = $session->attendances->where('student_id', $student->id)->first();

                $student->attendance = $studentAttendance;
                $student->joined_at = !empty($studentAttendance) ? $studentAttendance->joined_at : null;
            }

            $export = new AttendanceHistoryDetailsExport($students);
            return Excel::download($export, 'attendance_history_details.xlsx');
        }

        abort(404);
    }

}
