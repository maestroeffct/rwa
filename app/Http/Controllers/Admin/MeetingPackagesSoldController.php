<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingPackage;
use App\Models\MeetingPackageSold;
use App\Models\Session;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MeetingPackagesSoldController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("admin_meeting_packages_sold");

        $query = MeetingPackageSold::query();
        $meetingPackagesSold = $this->handleListsFilters($request, $query)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'email', 'mobile', 'username', 'avatar', 'avatar_settings');
                },
                'meetingPackage' => function ($query) {
                    $query->with([
                        'creator' => function ($query) {
                            $query->select('id', 'full_name', 'role_name', 'role_id', 'email', 'mobile', 'username', 'avatar', 'avatar_settings');
                        },
                    ]);
                },
                'sessions',
            ])
            ->withCount([
                'sessions'
            ])
            ->paginate(10);

        foreach ($meetingPackagesSold as $meetingPackageSold) {
            $meetingPackageSold = $meetingPackageSold->handleExtraData();
        }

        $data = [
            'pageTitle' => trans('update.sold_packages'),
            'meetingPackagesSold' => $meetingPackagesSold,
        ];
        $data = array_merge($data, $this->handleTopStats());

        $creatorsIds = $request->get('creator_ids', []);
        if (!empty($creatorsIds)) {
            $data['creators'] = User::query()->whereIn('id', $creatorsIds)->get();
        }

        $studentsIds = $request->get('student_ids', []);
        if (!empty($studentsIds)) {
            $data['students'] = User::query()->whereIn('id', $studentsIds)->get();
        }


        return view('admin.meeting_packages.sold.index', $data);
    }

    private function handleTopStats(): array
    {
        $query = MeetingPackageSold::query();
        $totalSoldPackages = deepClone($query)->count();

        $salesAmount = 0;
        $openPackages = 0;
        $finishedPackages = 0;

        $meetingPackagesSold = deepClone($query)->get();

        foreach ($meetingPackagesSold as $meetingPackageSold) {
            $meetingPackageSold = $meetingPackageSold->handleExtraData();

            if ($meetingPackageSold->status == "finished") {
                $finishedPackages += 1;
            } else {
                $openPackages += 1;
            }

            $salesAmount += $meetingPackageSold->paid_amount;
        }

        return [
            'totalSoldPackages' => $totalSoldPackages,
            'totalSalesAmount' => $salesAmount,
            'totalOpenPackages' => $openPackages,
            'totalFinishedPackages' => $finishedPackages,
        ];
    }

    private function handleListsFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');
        $creatorIds = $request->get('creator_ids');
        $studentIds = $request->get('student_ids');
        $status = $request->get('status');
        $sort = $request->get('sort');

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($search)) {
            $query->whereHas('meetingPackage', function ($query) use ($search) {
                $query->whereTranslationLike('title', '%' . $search . '%');
            });
        }

        if (!empty($creatorIds) and is_array($creatorIds)) {
            $query->whereHas('meetingPackage', function ($query) use ($creatorIds) {
                $query->whereIn('creator_id', $creatorIds);
            });
        }

        if (!empty($studentIds) and is_array($studentIds)) {
            $query->whereIn('user_id', $studentIds);
        }

        if (!empty($status)) {
            if ($status == 'open') {
                $query->where(function ($query) use ($status) {
                    $query->whereHas('sessions', function (Builder $query) {
                        $query->where('status', '!=', 'finished');
                    });

                    $query->where('expire_at', '>', time());
                });

            } elseif ($status == 'finished') {
                $query->where(function ($query) use ($status) {
                    $query->whereDoesntHave('sessions', function (Builder $query) {
                        $query->where('status', '!=', 'finished');
                    });

                    $query->orWhere('expire_at', '<', time());
                });
            }
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'paid_amount_asc':
                    $query->orderBy('paid_amount', 'asc');
                    break;
                case 'paid_amount_desc':
                    $query->orderBy('paid_amount', 'desc');
                    break;
                case 'purchase_date_asc':
                    $query->orderBy('paid_at', 'asc');
                    break;
                case 'purchase_date_desc':
                    $query->orderBy('paid_at', 'desc');
                    break;
                case 'expiry_date_asc':
                    $query->orderBy('expire_at', 'asc');
                    break;
                case 'expiry_date_desc':
                    $query->orderBy('expire_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('paid_at', 'desc');
        }

        return $query;
    }

    public function sessions(Request $request, $soldId)
    {
        $this->authorize("admin_meeting_packages_sold");

        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)->first();

        if (!empty($meetingPackageSold)) {
            $page = $request->get('page') ?? 1;
            $count = $this->perPage;
            $meetingPackageSold = $meetingPackageSold->handleExtraData();

            $query = Session::query()->where('meeting_package_sold_id', $meetingPackageSold->id);
            //$copyQuery = deepClone($query);
            $sessions = $query->paginate($count);

            $startIndex = ($page - 1) * $count;
            foreach ($sessions as $index => $session) {
                $session->number_row = $startIndex + ($index + 1);
            }


            $data = [
                'pageTitle' => trans('update.meeting_package_detail'),
                'meetingPackageSold' => $meetingPackageSold,
                'meetingPackage' => $meetingPackageSold->meetingPackage,
                'sessions' => $sessions,
            ];

            return view('admin.meeting_packages.sessions.index', $data);
        }

        abort(404);
    }

    public function getStudentDetail($id)
    {
        $this->authorize("admin_meeting_packages_sold");
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $id)->first();

        if (!empty($meetingPackageSold)) {

            $html = (string)view()->make("design_1.panel.meeting.modals.contact_info", [
                'userInfo' => $meetingPackageSold->user,
                'meetingPackageSold' => $meetingPackageSold,
            ]);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

    public function getSessionDateForm($soldId, $sessionId)
    {
        $this->authorize("admin_meeting_packages_sold");

        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {
                $meetingPackage = $meetingPackageSold->meetingPackage;

                $html = (string)view('admin.meeting_packages.sessions.modals.session_time_modal', [
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
        $this->authorize("admin_meeting_packages_sold");

        $this->validate($request, [
            'date' => 'required',
        ]);

        $sessionDate = convertTimeToUTCzone($request->get('date'))->getTimestamp();
        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)->first();

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
        $this->authorize("admin_meeting_packages_sold");

        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)->first();

        if (!empty($meetingPackageSold)) {
            $session = Session::query()->where('id', $sessionId)
                ->where('meeting_package_sold_id', $meetingPackageSold->id)
                ->first();

            if (!empty($session)) {
                $student = $meetingPackageSold->user;
                $meetingPackage = $meetingPackageSold->meetingPackage;

                $html = (string)view('admin.meeting_packages.sessions.modals.create_session', [
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
                        'date' => dateTimeFormat(time(), 'j M Y H:i'),
                    ]
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function updateSessionApi(Request $request, $soldId, $sessionId)
    {
        $this->authorize("admin_meeting_packages_sold");

        $data = $request->all();
        $this->validate($request, [
            'session_type' => 'required|in:agora,external',
            'url' => 'required_if:session_type,external',
        ]);

        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)->first();

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

    public function finishSession($soldId, $sessionId)
    {
        $this->authorize("admin_meeting_packages_sold");

        $meetingPackageSold = MeetingPackageSold::query()->where('id', $soldId)->first();

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


                $toastData = [
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.your_meeting_session_ended_successfully'),
                    'status' => 'success',
                ];
                return redirect()->back()->with(['toast' => $toastData]);
            }
        }

        abort(404);
    }
}
