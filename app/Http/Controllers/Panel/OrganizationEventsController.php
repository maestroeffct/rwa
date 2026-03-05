<?php

namespace App\Http\Controllers\Panel;


use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Role;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrganizationEventsController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize("panel_events_organization_lists");

        $user = auth()->user();

        if (!empty($user->organ_id)) {
            $organization = $user->organization;

            $query = Event::query()
                ->where('creator_id', $organization->id)
                ->where('status', 'publish');

            $getListData = $this->getListsData($request, $query);

            if ($request->ajax()) {
                return $getListData;
            }

            $topStats = $this->handlePageTopStats($organization);

            $data = [
                'pageTitle' => trans('update.my_organization_events'),
                'organization' => $organization,
            ];
            $data = array_merge($data, $getListData);
            $data = array_merge($data, $topStats);

            return view('design_1.panel.events.organization.index', $data);
        }

        abort(404);
    }

    private function handlePageTopStats($organization): array
    {
        $usersQuery = User::query()->where('organ_id', $organization->id);

        $studentsCount = deepClone($usersQuery)
            ->where('role_name', Role::$user)
            ->count();

        $instructorsCount = deepClone($usersQuery)
            ->where('role_name', Role::$teacher)
            ->count();

        $coursesCount = Webinar::where('status', Webinar::$active)
            ->where('private', false)
            ->where('creator_id', $organization->id)
            ->count();

        return [
            'studentsCount' => $studentsCount,
            'instructorsCount' => $instructorsCount,
            'coursesCount' => $coursesCount,
        ];
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $events = $query
            ->with([
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
                'creator' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about', 'verified', 'cover_img', 'profile_secondary_image');
                }
            ])
            ->withCount([
                'tickets' => function ($query) {
                    $query->where('enable', true);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        /*foreach ($events as $event) {

        }*/

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $events, $total, $count);
        }

        return [
            'events' => $events,
            'pagination' => $this->makePagination($request, $events, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $events, $total, $count)
    {
        $html = "";

        foreach ($events as $eventRow) {
            $html .= (string)view()->make("design_1.panel.events.organization.event_card", ['event' => $eventRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $events, $total, $count, true)
        ]);
    }

}
