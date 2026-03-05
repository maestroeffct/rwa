<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MeetingPackage;
use App\Models\MeetingPackageSold;
use App\Models\Session;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\Request;

class PurchasedMeetingPackageSessionsController extends Controller
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
            ->where('user_id', $user->id)
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

            return view('design_1.panel.meeting.purchased_packages.sessions.index', $data);
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
            $html .= (string)view()->make('design_1.panel.meeting.purchased_packages.sessions.table_items', [
                'session' => $sessionRow,
                'meetingPackageSold' => $meetingPackageSold,
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $sessions, $total, $count, true)
        ]);
    }

    public function joinToSessionModal($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->where('user_id', $user->id)
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
                    'url' => "/panel/meetings/purchased-packages/{$meetingPackageSold->id}/sessions/{$session->id}/join-to-session"
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function joinToSession($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->where('user_id', $user->id)
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
            ->where('user_id', $user->id)
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
                    'url' => "/panel/meetings/purchased-packages/{$meetingPackageSold->id}/sessions/{$session->id}/finish"
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function finishSession($soldId, $sessionId)
    {
        $user = auth()->user();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {
                $session->update([
                    'status' => 'finished',
                ]);

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
