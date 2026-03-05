<?php

namespace App\Http\Controllers\Panel;


use App\Http\Controllers\Controller;
use App\Models\UpcomingCourse;
use App\Models\UpcomingCourseFollower;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UpcomingCourseFollowersController extends Controller
{

    public function __construct()
    {
        if (empty(getFeaturesSettings('upcoming_courses_status'))) {
            abort(404);
        }
    }

    public function index(Request $request, $id)
    {
        $this->authorize("panel_upcoming_courses_followers");

        $user = auth()->user();

        $upcomingCourse = UpcomingCourse::query()
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($upcomingCourse)) {
            $query = UpcomingCourseFollower::query()->where('upcoming_course_id', $upcomingCourse->id);

            $copyQuery = deepClone($query);
            $getListData = $this->getListsData($request, $query);

            if ($request->ajax()) {
                return $getListData;
            }

            $followerCount = deepClone($copyQuery)->count();

            $data = [
                'pageTitle' => '“' . $upcomingCourse->title . '” ' . trans('update.followers'),
                'upcomingCourse' => $upcomingCourse,
                'followerCount' => $followerCount,
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.upcoming_courses.followers.index', $data);
        }

        abort(404);
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $followers = $query
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $followers, $total, $count);
        }

        return [
            'followers' => $followers,
            'pagination' => $this->makePagination($request, $followers, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $followers, $total, $count)
    {
        $html = "";

        foreach ($followers as $followerRow) {
            $html .= (string)view()->make('design_1.panel.upcoming_courses.followers.item_card', ['follower' => $followerRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $followers, $total, $count, true)
        ]);
    }

    public function deleteFollowing($upcomingId)
    {
        $user = auth()->user();

        $follow = UpcomingCourseFollower::query()->where('user_id', $user->id)
            ->where('upcoming_course_id', $upcomingId)
            ->first();

        if (!$follow) {
            abort(404);
        }

        $follow->delete();

        return response()->json([
            'code' => 200,
        ], 200);
    }

}
