<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingPackageSold;
use App\Models\MeetingTime;
use App\Models\Quiz;
use App\Models\ReserveMeeting;
use App\Models\Role;
use App\Models\Session;
use App\Models\Translation\SessionTranslation;
use App\Models\WebinarChapterItem;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ReserveMeetingController extends Controller
{
    public function reservation(Request $request)
    {
        $this->authorize("panel_meetings_my_reservation");

        $user = auth()->user();
        $reserveMeetingsQuery = ReserveMeeting::where('user_id', $user->id)
            ->whereNotNull('reserved_at')
            ->whereHas('sale', function ($query) {
                //$query->whereNull('refund_at');
            });


        $reserveMeetingsQuery = $this->filters(deepClone($reserveMeetingsQuery), $request);
        $pageListData = $this->getPageListData($request, deepClone($reserveMeetingsQuery));

        if ($request->ajax()) {
            return $pageListData;
        }

        $openReserveCount = deepClone($reserveMeetingsQuery)->where('status', \App\models\ReserveMeeting::$open)->count();
        $totalReserveCount = deepClone($reserveMeetingsQuery)->count();

        $meetingIds = deepClone($reserveMeetingsQuery)->pluck('meeting_id')->toArray();
        $teacherIds = Meeting::whereIn('id', array_unique($meetingIds))
            ->pluck('creator_id')
            ->toArray();
        $instructors = User::select('id', 'full_name')
            ->whereIn('id', array_unique($teacherIds))
            ->get();


        $activeMeetingTimeIds = ReserveMeeting::where('user_id', $user->id)
            ->where('status', ReserveMeeting::$open)
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            })
            ->pluck('meeting_time_id');

        $activeMeetingTimes = MeetingTime::whereIn('id', $activeMeetingTimeIds)->get();

        $activeHoursCount = 0;
        foreach ($activeMeetingTimes as $time) {
            $explodetime = explode('-', $time->time);
            $activeHoursCount += strtotime($explodetime[1]) - strtotime($explodetime[0]);
        }

        $data = [
            'pageTitle' => trans('meeting.meeting_list_page_title'),
            'instructors' => $instructors,
            'openReserveCount' => $openReserveCount,
            'totalReserveCount' => $totalReserveCount,
            'activeHoursCount' => round($activeHoursCount / 3600, 2),
            'meetingPackageScheduledSessions' => $this->getMeetingPackageScheduledSessions($user),
        ];
        $data = array_merge($data, $pageListData);

        return view('design_1.panel.meeting.reservation.index', $data);
    }

    private function getMeetingPackageScheduledSessions($user)
    {
        $meetingPackagesSoldIds = MeetingPackageSold::query()
            ->where('user_id', $user->id)
            ->pluck('id')
            ->toArray();

        if (!empty($meetingPackagesSoldIds)) {
            return Session::query()
                ->whereIn('meeting_package_sold_id', $meetingPackagesSoldIds)
                ->where('status', '!=', 'finished')
                ->whereNotNull('date')
                ->get();
        }

        return null;
    }

    public function requests(Request $request)
    {
        $this->authorize("panel_meetings_requests");

        $meetingIds = Meeting::where('creator_id', auth()->user()->id)->pluck('id');

        $reserveMeetingsQuery = ReserveMeeting::whereIn('meeting_id', $meetingIds)
            ->where(function ($query) {
                $query->whereHas('sale', function ($query) {
                    $query->whereNull('refund_at');
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('status', ['canceled']);
                    $query->whereHas('sale');
                });
            });

        $reserveMeetingsQuery = $this->filters(deepClone($reserveMeetingsQuery), $request);
        $pageListData = $this->getPageListData($request, deepClone($reserveMeetingsQuery), true);

        if ($request->ajax()) {
            return $pageListData;
        }

        $pendingReserveCount = deepClone($reserveMeetingsQuery)->where('status', \App\models\ReserveMeeting::$pending)->count();
        $totalReserveCount = deepClone($reserveMeetingsQuery)->count();
        $sumReservePaid = deepClone($reserveMeetingsQuery)->sum('paid_amount');

        $userIdsReservedTime = deepClone($reserveMeetingsQuery)->pluck('user_id')->toArray();
        $usersReservedTimes = User::select('id', 'full_name')
            ->whereIn('id', array_unique($userIdsReservedTime))
            ->get();


        $activeMeetingTimeIds = ReserveMeeting::whereIn('meeting_id', $meetingIds)
            ->where('status', ReserveMeeting::$pending)
            ->whereHas('sale', function ($query) {
                $query->whereNull('refund_at');
            })
            ->pluck('meeting_time_id')
            ->toArray();

        $meetingTimesCount = array_count_values($activeMeetingTimeIds);
        $activeMeetingTimes = MeetingTime::whereIn('id', $activeMeetingTimeIds)->get();

        $activeHoursCount = 0;
        foreach ($activeMeetingTimes as $time) {
            $explodetime = explode('-', $time->time);
            $hour = strtotime($explodetime[1]) - strtotime($explodetime[0]);

            if (!empty($meetingTimesCount) and is_array($meetingTimesCount) and !empty($meetingTimesCount[$time->id])) {
                $hour = $hour * $meetingTimesCount[$time->id];
            }

            $activeHoursCount += $hour;
        }

        $data = [
            'pageTitle' => trans('meeting.meeting_requests_page_title'),
            'pendingReserveCount' => $pendingReserveCount,
            'totalReserveCount' => $totalReserveCount,
            'sumReservePaid' => $sumReservePaid,
            'activeHoursCount' => $activeHoursCount,
            'usersReservedTimes' => $usersReservedTimes,
        ];
        $data = array_merge($data, $pageListData);

        return view('design_1.panel.meeting.requests.index', $data);
    }

    public function filters($query, $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $day = $request->get('day');
        $instructor_id = $request->get('instructor_id');
        $student_id = $request->get('student_id');
        $status = $request->get('status');
        $meetingType = $request->get('meeting_type');
        $population = $request->get('population');
        $openMeetings = $request->get('open_meetings');

        // $from and $to
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($day) and $day != 'all') {
            $meetingTimeIds = $query->pluck('meeting_time_id');
            $meetingTimeIds = MeetingTime::whereIn('id', $meetingTimeIds)
                ->where('day_label', $day)
                ->pluck('id');

            $query->whereIn('meeting_time_id', $meetingTimeIds);
        }

        if (!empty($instructor_id) and $instructor_id != 'all') {

            $meetingsIds = Meeting::where('creator_id', $instructor_id)
                ->where('disabled', false)
                ->pluck('id')
                ->toArray();

            $query->whereIn('meeting_id', $meetingsIds);
        }

        if (!empty($student_id) and $student_id != 'all') {
            $query->where('user_id', $student_id);
        }


        if (!empty($meetingType) and $meetingType != 'All') {
            $query->where('meeting_type', strtolower($meetingType));
        }

        if (!empty($population) and $population != 'All') {
            if ($population == "group") {
                $query->where('student_count', '>', 1);
            } else {
                $query->where('student_count', '=', 1);
            }
        }

        if (!empty($status) and $status != 'All') {
            $query->where('status', strtolower($status));
        }

        if (!empty($openMeetings) and $openMeetings == 'on') {
            $query->where('status', 'open');
        }

        return $query;
    }

    private function getPageListData(Request $request, Builder $query, $isRequestsPage = false)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $reserveMeetings = $query->with([
            'meetingTime',
            'meeting' => function ($query) {
                $query->with([
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'email');
                    }
                ]);
            },
            'user' => function ($query) {
                $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'email');
            },
            'sale'
        ])->get();


        if ($request->ajax()) {
            return $this->handleAjaxResponse($request, $reserveMeetings, $total, $count, $isRequestsPage);
        }

        return [
            'reserveMeetings' => $reserveMeetings,
            'pagination' => $this->makePagination($request, $reserveMeetings, $total, $count, true),
        ];
    }

    private function handleAjaxResponse(Request $request, $reserveMeetings, $total, $count, $isRequestsPage = false)
    {
        $html = "";

        if ($isRequestsPage) {
            foreach ($reserveMeetings as $reserveMeeting) {
                $html .= (string)view()->make("design_1.panel.meeting.requests.table_items", ['reserveMeeting' => $reserveMeeting]);
            }
        } else {
            foreach ($reserveMeetings as $reserveMeeting) {
                $html .= (string)view()->make("design_1.panel.meeting.reservation.table_items", ['reserveMeeting' => $reserveMeeting]);
            }
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $reserveMeetings, $total, $count, true)
        ]);
    }

    public function getFinishModal($id)
    {
        $user = auth()->user();
        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
        $ReserveMeeting = ReserveMeeting::where('id', $id)
            ->where(function ($query) use ($user, $meetingIds) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('meeting_id', $meetingIds);
            })
            ->first();

        if (!empty($ReserveMeeting)) {
            $html = (string)view()->make("design_1.panel.meeting.modals.finish_meeting", [
                'ReserveMeeting' => $ReserveMeeting,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
                'url' => "/panel/meetings/{$ReserveMeeting->id}/finish"
            ]);
        }

        return response()->json([], 422);
    }

    public function finish($id)
    {
        $user = auth()->user();
        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
        $ReserveMeeting = ReserveMeeting::where('id', $id)
            ->where(function ($query) use ($user, $meetingIds) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('meeting_id', $meetingIds);
            })
            ->with(['meeting', 'user'])
            ->first();

        if (!empty($ReserveMeeting)) {
            $ReserveMeeting->update([
                'status' => ReserveMeeting::$finished
            ]);

            $notifyOptions = [
                '[student.name]' => $ReserveMeeting->user->full_name,
                '[instructor.name]' => $ReserveMeeting->meeting->creator->full_name,
                '[time.date]' => $ReserveMeeting->day,
            ];
            sendNotification('meeting_finished', $notifyOptions, $ReserveMeeting->user_id);
            sendNotification('meeting_finished', $notifyOptions, $ReserveMeeting->meeting->creator_id);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.your_meeting_session_ended_successfully'),
            ], 200);
        }

        return response()->json([], 422);
    }

    public function getJoinModal($id)
    {
        $user = auth()->user();
        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
        $ReserveMeeting = ReserveMeeting::where('id', $id)
            ->where(function ($query) use ($user, $meetingIds) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('meeting_id', $meetingIds);
            })
            ->first();

        if (!empty($ReserveMeeting)) {
            $html = (string)view()->make("design_1.panel.meeting.modals.join_modal", [
                'ReserveMeeting' => $ReserveMeeting,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
                'password' => $ReserveMeeting->password ?? '-',
                'url' => "/panel/meetings/{$ReserveMeeting->id}/join"
            ]);
        }

        return response()->json([], 422);
    }


    public function join(Request $request, $id)
    {
        $user = auth()->user();
        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');

        $reserveMeeting = ReserveMeeting::where('id', $id)
            ->where(function ($query) use ($user, $meetingIds) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('meeting_id', $meetingIds);
            })
            ->first();

        if($reserveMeeting->meeting_type != 'in_person' and $reserveMeeting->status == ReserveMeeting::$open and (!empty($reserveMeeting->link) or !empty($reserveMeeting->session))) {
            $link = $reserveMeeting->link;

            if (!empty($reserveMeeting->session)) {
                $link = $reserveMeeting->session->getJoinLink();
            }

            if (!empty($link)) {
                return Redirect::away($link);
            }
        }

        abort(403);
    }

    public function getCreateSessionModal(Request $request, $id)
    {
        $user = auth()->user();
        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
        $ReserveMeeting = ReserveMeeting::where('id', $id)
            ->whereIn('meeting_id', $meetingIds)
            ->first();

        if (!empty($ReserveMeeting)) {
            $html = (string)view()->make("design_1.panel.meeting.modals.create_session", [
                'ReserveMeeting' => $ReserveMeeting,
            ]);

            $student = $ReserveMeeting->user;

            return response()->json([
                'code' => 200,
                'html' => $html,
                'reserve_date' => dateTimeFormat($ReserveMeeting->start_at, 'j M Y H:i'),
                'student' => [
                    'full_name' => $student->full_name,
                    'full_avatar' => $student->getAvatar(),
                ]
            ]);
        }

        return response()->json([], 422);
    }

    public function createSession(Request $request, $id)
    {
        $user = auth()->user();
        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
        $ReserveMeeting = ReserveMeeting::where('id', $id)
            ->whereIn('meeting_id', $meetingIds)
            ->first();

        if (!empty($ReserveMeeting)) {
            $data = $request->all();
            $validator = Validator::make($data, [
                'session_type' => 'required|in:agora,external',
                'url' => 'required_if:session_type,external',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }

            if ($data['session_type'] == 'agora') {
                $this->handleMakeAgoraSession($request, $ReserveMeeting, $user);

                $ReserveMeeting->update([
                    'status' => ReserveMeeting::$open,
                ]);
            } else {
                $link = $data['url'];

                $ReserveMeeting->update([
                    'link' => $link,
                    'password' => $data['password'] ?? null,
                    'status' => ReserveMeeting::$open,
                ]);

                $notifyOptions = [
                    '[link]' => $link,
                    '[instructor.name]' => $ReserveMeeting->meeting->creator->full_name,
                    '[time.date]' => $ReserveMeeting->day,
                ];
                sendNotification('new_appointment_link', $notifyOptions, $ReserveMeeting->user_id);
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.your_meeting_session_information_stored_successful'),
            ]);
        }

        return response()->json([], 422);
    }

    private function handleMakeAgoraSession(Request $request, $ReserveMeeting, $user)
    {
        $agoraSettings = [
            'chat' => true,
            'record' => true,
            'users_join' => true
        ];

        $session = Session::query()->updateOrCreate([
            'creator_id' => $user->id,
            'reserve_meeting_id' => $ReserveMeeting->id,
        ], [
            'date' => time(), // can start now
            'duration' => (($ReserveMeeting->end_at - $ReserveMeeting->start_at) / 60),
            'link' => null,
            'session_api' => 'agora',
            'agora_settings' => json_encode($agoraSettings),
            'check_previous_parts' => false,
            'status' => Session::$Active,
            'created_at' => time()
        ]);

        SessionTranslation::updateOrCreate([
            'session_id' => $session->id,
            'locale' => mb_strtolower(app()->getLocale()),
        ], [
            'title' => trans('update.new_in-app_call_session'),
            'description' => trans('update.new_in-app_call_session'),
        ]);

        // Send Notification
        $notifyOptions = [
            '[link]' => $session->getJoinLink(),
            '[instructor.name]' => $user->full_name,
            '[time.date]' => dateTimeFormat($session->date, 'j M Y H:i'),
        ];
        sendNotification('new_appointment_session', $notifyOptions, $ReserveMeeting->user_id);
    }

    public function getContactInfoModal($id)
    {
        $user = auth()->user();

        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');

        $ReserveMeeting = ReserveMeeting::where('id', $id)
            ->where(function ($query) use ($user, $meetingIds) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('meeting_id', $meetingIds);
            })
            ->first();

        if (!empty($ReserveMeeting)) {
            if ($ReserveMeeting->user_id == $user->id) {
                // return Instructor Info
                $userInfo = $ReserveMeeting->meeting->creator;
            } else {
                $userInfo = $ReserveMeeting->user;
            }

            $html = (string)view()->make("design_1.panel.meeting.modals.contact_info", [
                'userInfo' => $userInfo,
                'ReserveMeeting' => $ReserveMeeting,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

}
