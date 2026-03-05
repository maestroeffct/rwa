<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_notifications_lists");

        $user = auth()->user();

        $query = Notification::query()->where(function ($query) use ($user) {
            $query->where('notifications.user_id', $user->id)
                ->where('notifications.type', 'single');
        })->orWhere(function ($query) use ($user) {
            if (!$user->isAdmin()) {
                $query->whereNull('notifications.user_id')
                    ->whereNull('notifications.group_id')
                    ->where('notifications.type', 'all_users');
            }
        });

        $userGroup = $user->userGroup()->first();
        if (!empty($userGroup)) {
            $query->orWhere(function ($query) use ($userGroup) {
                $query->where('notifications.group_id', $userGroup->group_id)
                    ->where('notifications.type', 'group');
            });
        }

        $query->orWhere(function ($query) use ($user) {
            $query->whereNull('notifications.user_id')
                ->whereNull('notifications.group_id')
                ->where(function ($query) use ($user) {
                    if ($user->isUser()) {
                        $query->where('notifications.type', 'students');
                    } elseif ($user->isTeacher()) {
                        $query->where('notifications.type', 'instructors');
                    } elseif ($user->isOrganization()) {
                        $query->where('notifications.type', 'organizations');
                    }
                });
        });

        /* Get Course Students Notifications */
        $userBoughtWebinarsIds = $user->getPurchasedCoursesIds();

        if (!empty($userBoughtWebinarsIds)) {
            $query->orWhere(function ($query) use ($userBoughtWebinarsIds) {
                $query->whereIn('webinar_id', $userBoughtWebinarsIds)
                    ->where('type', 'course_students');
            });
        }

        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $data = [
            'pageTitle' => trans('panel.notifications'),
            ...$getListData,
        ];

        return view('design_1.panel.notifications.index', $data);
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $notifications = $query
            ->leftJoin('notifications_status', 'notifications.id', '=', 'notifications_status.notification_id')
            ->selectRaw('notifications.*, count(notifications_status.notification_id) AS `item_count`')
            ->with(['notificationStatus'])
            ->groupBy('notifications.id')
            ->orderBy('item_count', 'asc')
            ->orderBy('notifications.created_at', 'DESC')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $notifications, $total, $count);
        }

        return [
            'notifications' => $notifications,
            'pagination' => $this->makePagination($request, $notifications, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $notifications, $total, $count)
    {
        $html = "";

        foreach ($notifications as $notificationRow) {
            $html .= (string)view()->make('design_1.panel.notifications.notif_card', ['notification' => $notificationRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $notifications, $total, $count, true)
        ]);
    }

    public function saveStatus($id)
    {
        $user = auth()->user();

        $unReadNotifications = $user->getUnReadNotifications();

        if (!empty($unReadNotifications) and !$unReadNotifications->isEmpty()) {
            $notification = $unReadNotifications->where('id', $id)->first();

            if (!empty($notification)) {
                $status = NotificationStatus::where('user_id', $user->id)
                    ->where('notification_id', $notification->id)
                    ->first();

                if (empty($status)) {
                    NotificationStatus::create([
                        'user_id' => $user->id,
                        'notification_id' => $notification->id,
                        'seen_at' => time()
                    ]);
                }
            }
        }

        return response()->json([], 200);
    }

    public function markAllAsRead()
    {
        $user = auth()->user();

        if (!empty($user)) {
            $unReadNotifications = $user->getUnReadNotifications();

            if (!empty($unReadNotifications) and !$unReadNotifications->isEmpty()) {
                foreach ($unReadNotifications as $notification) {
                    $status = NotificationStatus::where('user_id', $user->id)
                        ->where('notification_id', $notification->id)
                        ->first();

                    if (empty($status)) {
                        NotificationStatus::create([
                            'user_id' => $user->id,
                            'notification_id' => $notification->id,
                            'seen_at' => time()
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'text' => trans('update.all_your_notifications_have_been_marked_as_read'),
            'timeout' => 2000
        ]);
    }
}
