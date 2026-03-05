<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MeetingPackage;
use App\Models\MeetingPackageSold;
use App\Models\Session;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\Request;

class SoldMeetingPackageSessionsController extends Controller
{

    public function __construct()
    {
        if (empty(getMeetingPackagesSettings("status"))) {
            abort(403);
        }
    }

    public function index(Request $request, $id)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $id)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $meetingPackageSold = $meetingPackageSold->handleExtraData();

            $query = Session::query()->where('meeting_package_sold_id', $meetingPackageSold->id);
            //$copyQuery = deepClone($query);

            $getListData = $this->getListsData($request, $query, $meetingPackageSold);

            if ($request->ajax()) {
                return $getListData;
            }


            $data = [
                'pageTitle' => trans('update.meeting_package_detail'),
                'meetingPackageSold' => $meetingPackageSold,
                'meetingPackage' => $meetingPackageSold->meetingPackage,
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.meeting.sold_packages.sessions.index', $data);
        }

        abort(404);
    }

    private function getListsData(Request $request, Builder $query, $meetingPackageSold)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $sessions = $query
            ->get();

        $startIndex = ($page - 1) * $count;
        foreach ($sessions as $index => $session) {
            $session->number_row = $startIndex + ($index + 1);
        }

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $sessions, $total, $count, $meetingPackageSold);
        }

        return [
            'sessions' => $sessions,
            'pagination' => $this->makePagination($request, $sessions, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $sessions, $total, $count, $meetingPackageSold)
    {
        $html = "";

        foreach ($sessions as $sessionRow) {
            $html .= (string)view()->make('design_1.panel.meeting.sold_packages.sessions.table_items', [
                'session' => $sessionRow,
                'meetingPackageSold' => $meetingPackageSold,
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $sessions, $total, $count, true)
        ]);
    }


    public function getSessionDateForm($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {
                $meetingPackage = $meetingPackageSold->meetingPackage;

                $html = (string)view('design_1.panel.meeting.sold_packages.sessions.modals.session_time_modal', [
                    'meetingPackage' => $meetingPackage,
                    'meetingPackageSold' => $meetingPackageSold,
                    'session' => $session,
                ]);

                return response()->json([
                    'code' => 200,
                    'html' => $html,
                    'hours' => convertMinutesToHourAndMinute($meetingPackage->session_duration)
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function updateSessionDate(Request $request, $soldId, $sessionId)
    {
        $this->validate($request, [
            'date' => 'required',
        ]);

        $sessionDate = convertTimeToUTCzone($request->get('date'))->getTimestamp();
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $meetingPackageSold = $meetingPackageSold->handleExtraData();

            $dateError = null;
            if ($sessionDate <= time()) {
                $dateError = trans('update.this_date_cannot_be_less_than_now');
            }

            if ($sessionDate > $meetingPackageSold->expire_at) {
                $dateError = trans('update.this_date_cannot_be_after_the_package_expiration_date', ['date' => dateTimeFormat($meetingPackageSold->expire_at, 'j M Y H:i')]);
            }

            if (!empty($dateError)) {
                return response()->json([
                    'errors' => [
                        'date' => [$dateError]
                    ]
                ], 422);
            }

            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {
                $session->update([
                    'date' => $sessionDate,
                ]);

                $notifyOptions = [
                    '[item_title]' => $meetingPackageSold->meetingPackage->title,
                    '[time.date]' => dateTimeFormat($session->date, 'j M Y , H:i'),
                ];
                sendNotification('meeting_package_session_scheduled', $notifyOptions, $meetingPackageSold->user_id);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.the_meeting_date_was_successfully_updated')
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function getSessionApiForm($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {
                $student = $meetingPackageSold->user;
                $meetingPackage = $meetingPackageSold->meetingPackage;

                $html = (string)view('design_1.panel.meeting.sold_packages.sessions.modals.create_session', [
                    'meetingPackage' => $meetingPackage,
                    'meetingPackageSold' => $meetingPackageSold,
                    'session' => $session,
                ]);

                return response()->json([
                    'code' => 200,
                    'html' => $html,
                    'student' => [
                        'avatar_path' => $student->getAvatar(),
                        'full_name' => $student->full_name,
                        'date' => dateTimeFormat($session->date, 'j M Y H:i'),
                    ]
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function updateSessionApi(Request $request, $soldId, $sessionId)
    {
        $data = $request->all();
        $this->validate($request, [
            'session_type' => 'required|in:agora,external',
            'url' => 'required_if:session_type,external',
        ]);


        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {
                $time = time();
                $link = ($data['session_type'] == 'external') ? $data['url'] : null;
                $password = ($data['session_type'] == 'external' and !empty($data['password'])) ? $data['password'] : null;
                $sessionApi = "local";
                $agoraSettings = null;

                if ($data['session_type'] == 'agora') {
                    $agoraSettings = [
                        'chat' => true,
                        'record' => true,
                        'users_join' => true
                    ];

                    $sessionApi = "agora";
                }

                $session->update([
                    'link' => $link,
                    'api_secret' => $password,
                    'session_api' => $sessionApi,
                    'agora_settings' => !empty($agoraSettings) ? json_encode($agoraSettings) : null,
                    'status' => Session::$Active,
                    'updated_at' => $time,
                ]);

                $notifyOptions = [
                    '[item_title]' => $meetingPackageSold->meetingPackage->title,
                    '[link]' => $session->getJoinLink(),
                ];
                sendNotification('meeting_package_session_generated_link', $notifyOptions, $meetingPackageSold->user_id);


                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.the_meeting_session_information_stored_successful')
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function joinToSessionModal($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {

                $html = (string)view()->make("design_1.panel.meeting.sold_packages.sessions.modals.join_session", []);

                return response()->json([
                    'code' => 200,
                    'html' => $html,
                    'password' => $session->api_secret ?? '-',
                    'url' => "/panel/meetings/sold-packages/{$meetingPackageSold->id}/sessions/{$session->id}/join-to-session"
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function joinToSession($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session) and $session->status == "active" and !empty($session->date)) {
                return redirect($session->getJoinLink());
            }
        }

        return response()->json([], 422);
    }

    public function finishSessionModal($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {

                $html = (string)view()->make("design_1.panel.meeting.sold_packages.sessions.modals.finish_session", []);

                return response()->json([
                    'code' => 200,
                    'html' => $html,
                    'password' => $session->api_secret ?? '-',
                    'url' => "/panel/meetings/sold-packages/{$meetingPackageSold->id}/sessions/{$session->id}/finish"
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function finishSession($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->whereHas('meetingPackage', function ($query) use ($user) {
                $query->where('creator_id', $user->id);
            })
            ->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {
                $session->update([
                    'status' => 'finished',
                ]);

                $notifyOptions = [
                    '[item_title]' => $meetingPackageSold->meetingPackage->title,
                ];
                sendNotification('meeting_package_session_ended', $notifyOptions, $meetingPackageSold->user_id);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.your_meeting_session_ended_successfully')
                ]);
            }
        }

        return response()->json([], 422);
    }
}
