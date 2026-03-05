<?php

namespace App\Mixins\Attendances;

use App\Models\Session;
use App\Models\SessionAttendanceNotification;

class AttendanceNotifications
{

    public function sendNotifToAttendances()
    {
        if (empty(getAttendanceSettings("status"))) {
            return;
        }

        $time = time();

        $query = Session::query()
            ->where('enable_attendance', true)
            ->where('status', 'active')
            ->whereDoesntHave('attendanceNotification');

        $query->where(function ($query) use ($time) {
            $query->whereHas('agoraHistory', function ($qh) {
                $qh->whereNotNull('end_at');
            });
            $query->orWhereRaw('? > ((sessions.duration * 60) + sessions.date)', [$time]);
        });

        $sessions = $query->get();

        foreach ($sessions as $session) {
            $presents = [];
            $absents = [];
            $lates = [];

            $course = $session->webinar;
            if (!empty($course)) {
                $allStudentsIds = $course->getStudentsIds();

                $attendances = $session->attendances;

                foreach ($attendances as $attendance) {
                    if (($key = array_search($attendance->student_id, $allStudentsIds)) !== false) {
                        unset($allStudentsIds[$key]);
                    }

                    if ($attendance->status == 'present') {
                        $presents[] = $attendance->student_id;
                    } else if ($attendance->status == 'late') {
                        $lates[] = $attendance->student_id;
                    } else {
                        $absents[] = $attendance->student_id;
                    }
                }

                $absents = array_merge($absents, $allStudentsIds);
            }


            if (count($presents) > 0) {
                foreach ($presents as $presentStudentId) {
                    $notifyOptions = [
                        '[session_title]' => $session->title,
                        '[c.title]' => $course->title,
                        '[time.date]' => dateTimeFormat($session->date, 'j M Y H:i'),
                    ];
                    sendNotification('for_present_students', $notifyOptions, $presentStudentId);
                }
            }

            if (count($lates) > 0) {
                foreach ($lates as $lateStudentId) {
                    $notifyOptions = [
                        '[session_title]' => $session->title,
                        '[c.title]' => $course->title,
                        //'[time.date]' => dateTimeFormat($session->date, 'j M Y H:i'),
                    ];
                    sendNotification('for_late_students', $notifyOptions, $lateStudentId);
                }
            }

            if (count($absents) > 0) {
                foreach ($absents as $absentStudentId) {
                    $notifyOptions = [
                        '[session_title]' => $session->title,
                        '[c.title]' => $course->title,
                        '[time.date]' => dateTimeFormat($session->date, 'j M Y H:i'),
                    ];
                    sendNotification('for_absent_students', $notifyOptions, $absentStudentId);
                }
            }

            // Store Log
            SessionAttendanceNotification::query()->updateOrCreate([
                'session_id' => $session->id,
            ], [
                'notify_at' => time(),
            ]);
        }


    }

}
