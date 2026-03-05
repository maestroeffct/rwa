<?php

namespace App\Http\Controllers\Panel;


use App\Http\Controllers\Controller;
use App\Models\UpcomingCourse;
use App\Models\UpcomingCourseFollower;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UpcomingCourseFollowingsController extends Controller
{

    public function __construct()
    {
        if (empty(getFeaturesSettings('upcoming_courses_status'))) {
            abort(404);
        }
    }


    public function index(Request $request)
    {
        $user = auth()->user();

        $upcomingIds = UpcomingCourseFollower::query()->where('user_id', $user->id)
            ->pluck('upcoming_course_id')
            ->toArray();

        $query = UpcomingCourse::query()
            ->whereIn('id', $upcomingIds)
            ->where('status', 'active');

        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }


        $data = [
            'pageTitle' => trans('update.following_courses'),
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.upcoming_courses.followings.index', $data);
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $upcomingCourses = $query
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $upcomingCourses, $total, $count);
        }

        return [
            'upcomingCourses' => $upcomingCourses,
            'pagination' => $this->makePagination($request, $upcomingCourses, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $upcomingCourses, $total, $count)
    {
        $html = "";

        foreach ($upcomingCourses as $upcomingCourseRow) {
            $html .= '<div class="col-12 col-md-6 col-lg-3 mt-20">';
            $html .= (string)view()->make("design_1.panel.upcoming_courses.followings.grid_card", ['upcomingCourse' => $upcomingCourseRow]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $upcomingCourses, $total, $count, true)
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
