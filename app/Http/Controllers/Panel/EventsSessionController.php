<?php

namespace App\Http\Controllers\Panel;


use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicketSold;
use App\Models\Session;
use App\Models\Translation\SessionTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventsSessionController extends Controller
{

    public function getSessionModal($eventId)
    {
        $user = auth()->user();
        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->with([
                'session'
            ])
            ->first();

        if (!empty($event)) {

            $html = (string)view()->make("design_1.panel.events.modals.create_session", [
                'event' => $event,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

    public function createSession(Request $request, $eventId)
    {
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

        $user = auth()->user();
        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($event)) {

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

            $session = Session::query()->updateOrCreate([
                'creator_id' => $user->id,
                'event_id' => $event->id,
            ], [
                'date' => (!empty($event->start_date) and $event->start_date > $time) ? $event->start_date : $time,
                'duration' => !empty($event->duration) ? $event->duration : 30,
                'link' => $link,
                'api_secret' => $password,
                'session_api' => $sessionApi,
                'agora_settings' => !empty($agoraSettings) ? json_encode($agoraSettings) : null,
                'check_previous_parts' => false,
                'status' => Session::$Active,
                'created_at' => $time,
            ]);

            SessionTranslation::updateOrCreate([
                'session_id' => $session->id,
                'locale' => mb_strtolower(app()->getLocale()),
            ], [
                'title' => trans('update.new_in_app_call_session_for_event'),
                'description' => trans('update.new_in_app_call_session_for_event'),
            ]);

            // Send Notification
            $this->sendNewSessionNotifToStudents($event, $session);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.your_event_session_information_stored_successful'),
            ]);
        }

        return response()->json([], 422);
    }

    private function sendNewSessionNotifToStudents(Event $event, Session $session)
    {
        $purchasedTicketsUsersIds = $event->getAllStudentsIds();

        $notifyOptions = [
            '[event_title]' => $event->title,
            '[link]' => ($session->session_api == "agora") ? $session->getJoinLink() : $session->link,
            '[instructor.name]' => $event->creator->full_name,
            '[time.date]' => dateTimeFormat($session->date, 'j M Y H:i'),
        ];

        foreach ($purchasedTicketsUsersIds as $userId) {
            sendNotification("online_event_sessions_for_students", $notifyOptions, $userId);
        }
    }

    public function getJoinSessionModal($eventId)
    {
        $user = auth()->user();
        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->with([
                'session'
            ])
            ->first();

        if (!empty($event) and $event->type == "online" and !empty($event->session)) {

            $html = (string)view()->make("design_1.panel.events.modals.join_modal", [
                'event' => $event,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
                'password' => $event->session->api_secret ?? '-',
                'url' => "/panel/events/{$event->id}/join-session"
            ]);
        }

        return response()->json([], 422);
    }

    public function joinToSession($eventId)
    {
        $user = auth()->user();
        $event = Event::query()->where('id', $eventId)
            ->where('creator_id', $user->id)
            ->with([
                'session'
            ])
            ->first();

        if (!empty($event) and $event->type == "online" and !empty($event->session)) {
            return redirect($event->session->getJoinLink());
        }

        abort(403);
    }
}
