<?php

namespace App\Http\Controllers\Admin;


use App\Exports\AttendanceHistoriesExport;
use App\Http\Controllers\Admin\traits\AttendanceSettingsTrait;
use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Webinar;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    use AttendanceSettingsTrait;

    public function index(Request $request)
    {
        $this->authorize("admin_attendances_history");


        $sessionsQuery = $this->getAttendanceHistoryQuery($request);
        $copyQuery = deepClone($sessionsQuery);
        $sessions = $sessionsQuery->paginate(10);

        foreach ($sessions as $session) {
            $allStudentsIds = $session->webinar->getStudentsIds();

            $session->total_students = count($allStudentsIds);

            $absentCount = $session->total_students - ($session->present_count + $session->late_count);
            $session->absent_count = $absentCount > 0 ? $absentCount : 0;
        }

        $data = [
            'pageTitle' => trans('update.attendances_history'),
            'sessions' => $sessions,
        ];
        $data = array_merge($data, $this->getSessionsHistoryTopStats($copyQuery));

        $user_ids = $request->get('student_ids', null);
        if (!empty($user_ids)) {
            $data['students'] = User::query()->whereIn('id', $user_ids)->select('id', 'full_name')->get();
        }

        $teacher_ids = $request->get('teacher_ids', null);
        if (!empty($teacher_ids)) {
            $data['teachers'] = User::query()->whereIn('id', $teacher_ids)->select('id', 'full_name')->get();
        }

        $webinar_ids = $request->get('webinar_ids', null);
        if (!empty($webinar_ids)) {
            $data['webinars'] = Webinar::query()->whereIn('id', $webinar_ids)->select('id', 'slug')->get();
        }

        return view("admin.attendances.history.index", $data);
    }

    private function getAttendanceHistoryQuery(Request $request)
    {
        $time = time();
        $query = Session::query()
            ->where('enable_attendance', true)
            ->where('status', 'active');

        $query->where(function ($query) use ($time) {
            $query->whereHas('agoraHistory', function ($qh) {
                $qh->whereNotNull('end_at');
            });
            $query->orWhereRaw('? > ((sessions.duration * 60) + sessions.date)', [$time]);
        });

        $query = $this->handleFiltersSessions($request, $query);

        $query->with([
            'webinar',
            'attendances',
            'creator' => function ($query) {
                $query->select('id', 'username', 'full_name', 'email', 'mobile', 'role_id', 'role_name', 'avatar', 'avatar_settings');
            },
        ]);

        $query->withCount([
            'attendances as present_count' => function ($q) {
                $q->where('status', 'present');
            },
            'attendances as late_count' => function ($q) {
                $q->where('status', 'late');
            },
        ]);

        return $query;
    }

    private function getSessionsHistoryTopStats($query): array
    {
        $sessions = $query->get();

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

    private function handleFiltersSessions(Request $request, $query)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $webinar_ids = $request->get('webinar_ids', null);
        $teacher_ids = $request->get('teacher_ids', null);
        $student_ids = $request->get('student_ids', null);
        $session_api = $request->get('session_api', null);
        $sort = $request->get('sort', null);


        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($webinar_ids)) {
            $query->whereIn('webinar_id', $webinar_ids);
        }

        if (!empty($teacher_ids)) {
            $query->whereIn('creator_id', $teacher_ids);
        }

        if (!empty($student_ids)) {
            $query->whereIn('user_id', $student_ids);
        }

        if (!empty($session_api)) {
            $query->where('session_api', $session_api);
        }

        if (!empty($sort)) {
            switch ($sort) {

            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }


    public function exportExcel(Request $request)
    {
        $this->authorize("admin_gift_export");

        $sessions = $this->getAttendanceHistoryQuery($request)->get();

        foreach ($sessions as $session) {
            $allStudentsIds = $session->webinar->getStudentsIds();

            $session->total_students = count($allStudentsIds);

            $absentCount = $session->total_students - ($session->present_count + $session->late_count);
            $session->absent_count = $absentCount > 0 ? $absentCount : 0;
        }

        $export = new AttendanceHistoriesExport($sessions);
        return Excel::download($export, 'attendance_history.xlsx');
    }


}
