<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\UserOccupation;
use App\User;
use App\Models\Role;
use App\Models\Meeting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InstructorsController extends Controller
{
    public function instructors(Request $request)
    {
        $query = $this->getListQuery(Role::$teacher);
        $query = $this->handleFilters($request, $query);

        $getListData = $this->getListData($request, $query, 10);

        if ($request->ajax()) {
            return $getListData;
        }

        $seoSettings = getSeoMetas('instructors');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.instructors');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.instructors');
        $pageRobot = getPageRobot('instructors');

        $categories = Category::query()->where('parent_id', null)
            ->with('subCategories')
            ->get();


        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'categories' => $categories,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.web.instructors.index', $data);
    }

    public function organizations(Request $request)
    {
        $query = $this->getListQuery(Role::$organization);
        $query = $this->handleFilters($request, $query);

        $getListData = $this->getListData($request, $query, 9, Role::$organization);

        if ($request->ajax()) {
            return $getListData;
        }

        $seoSettings = getSeoMetas('organizations');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('home.organizations');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('home.organizations');
        $pageRobot = getPageRobot('organizations');

        $categories = Category::query()->where('parent_id', null)
            ->with('subCategories')
            ->get();

        $topOrganizations = null;

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'categories' => $categories,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.web.organizations.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {


        return $query;
    }

    private function getListQuery($role): Builder
    {
        return User::where('role_name', $role)
            //->where('verified', true)
            ->where('users.status', 'active')
            ->where(function ($query) {
                $query->where('users.ban', false)
                    ->orWhere(function ($query) {
                        $query->whereNotNull('users.ban_end_at')
                            ->orWhere('users.ban_end_at', '<', time());
                    });
            })
            ->with([
                'meeting' => function ($query) {
                    $query->with('meetingTimes');
                    $query->withCount('meetingTimes');
                }
            ]);
    }

    private function getListData(Request $request, Builder $query, $count = 9, $userRole = null)
    {
        if (empty($userRole)) {
            $userRole = Role::$teacher;
        }

        $page = $request->get('page') ?? 1;
        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $users = $query->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $users, $total, $count, $userRole);
        }

        return [
            'users' => $users,
            'pagination' => $this->makePagination($request, $users, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $users, $total, $count, $userRole)
    {
        if ($userRole == Role::$organization) {
            $html = (string)view()->make('design_1.web.organizations.components.cards.grids.index', [
                'organizations' => $users,
                'gridCardClassName' => "",
                'withoutStyles' => true
            ]);
        } else {
            $html = (string)view()->make('design_1.web.instructors.components.cards.grids.index', [
                'instructors' => $users,
                'gridCardClassName' => "",
                'withoutStyles' => true
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $users, $total, $count, true)
        ]);
    }

    private function getBestRateUsers($role)
    {
        $query = $this->getListQuery($role);
        $bestRateUsers = $query
            ->limit(6)
            ->get();


        return $bestRateUsers;
    }

    private function filterInstructors($request, $query, $role)
    {
        $categories = $request->get('categories', null);
        $sort = $request->get('sort', null);
        $availableForMeetings = $request->get('available_for_meetings', null);
        $hasFreeMeetings = $request->get('free_meetings', null);
        $withDiscount = $request->get('discount', null);
        $search = $request->get('search', null);


        if (!empty($categories) and is_array($categories)) {
            $userIds = UserOccupation::whereIn('category_id', $categories)->pluck('user_id')->toArray();

            $query->whereIn('users.id', $userIds);
        }

        if (!empty($sort) and $sort == 'top_rate') {
            $query = $this->getBestRateUsers($query, $role);
        }

        if (!empty($sort) and $sort == 'top_sale') {
            $query = $this->getTopSalesUsers($query, $role);
        }

        if (!empty($availableForMeetings) and $availableForMeetings == 'on') {
            $hasMeetings = DB::table('meetings')
                ->where('meetings.disabled', 0)
                ->join('meeting_times', 'meetings.id', '=', 'meeting_times.meeting_id')
                ->select('meetings.creator_id', DB::raw('count(meeting_id) as counts'))
                ->groupBy('creator_id')
                ->orderBy('counts', 'desc')
                ->get();

            $hasMeetingsInstructorsIds = [];
            if (!empty($hasMeetings)) {
                $hasMeetingsInstructorsIds = $hasMeetings->pluck('creator_id')->toArray();
            }

            $query->whereIn('users.id', $hasMeetingsInstructorsIds);
        }

        if (!empty($hasFreeMeetings) and $hasFreeMeetings == 'on') {
            $freeMeetingsIds = Meeting::where('disabled', 0)
                ->where(function ($query) {
                    $query->whereNull('amount')->orWhere('amount', '0');
                })->groupBy('creator_id')
                ->pluck('creator_id')
                ->toArray();

            $query->whereIn('users.id', $freeMeetingsIds);
        }

        if (!empty($withDiscount) and $withDiscount == 'on') {
            $withDiscountMeetingsIds = Meeting::where('disabled', 0)
                ->whereNotNull('discount')
                ->groupBy('creator_id')
                ->pluck('creator_id')
                ->toArray();

            $query->whereIn('users.id', $withDiscountMeetingsIds);
        }

        if (!empty($search)) {
            $query->where(function ($qu) use ($search) {
                $qu->where('users.full_name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%")
                    ->orWhere('users.mobile', 'like', "%$search%");
            });
        }

        return $query;
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $option = $request->get('option', null);
        $user = auth()->user();

        if (!empty($term)) {
            $query = User::select('id', 'full_name')
                ->where(function ($query) use ($term) {
                    $query->where('full_name', 'like', '%' . $term . '%');
                    $query->orWhere('email', 'like', '%' . $term . '%');
                    $query->orWhere('mobile', 'like', '%' . $term . '%');
                })
                ->where('id', '<>', $user->id)
                ->whereNotIn('role_name', ['admin']);

            if (!empty($option) and $option == 'just_teachers') {
                $query->where('role_name', 'teacher');
            }

            if ($option == "just_student_role") {
                $query->where('role_name', Role::$user);
            }

            if ($option == "just_post_authors") {
                $query->whereHas('blog');
            }

            $users = $query->get();

            return response()->json($users, 200);
        }

        return response('', 422);
    }

}
