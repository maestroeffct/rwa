<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Role;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MyOrganizationCoursesController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_webinars_organization_classes");

        $user = auth()->user();

        if (!empty($user->organ_id)) {
            $organization = $user->organization;

            if (!empty($organization)) {
                $source = $request->get('source', 'courses');
                $query = $this->getQueryBySource($organization, $source);

                $pageListData = $this->getPageListData($request, $query);

                if ($request->ajax()) {
                    return $pageListData;
                }

                $topStats = $this->handlePageTopStats($organization);

                $pageTitle = trans('panel.organization_classes');
                $breadcrumbs = [
                    ['text' => trans('update.platform'), 'url' => '/'],
                    ['text' => trans('panel.dashboard'), 'url' => '/panel'],
                    ['text' => $pageTitle, 'url' => null],
                ];

                $data = [
                    'pageTitle' => $pageTitle,
                    'breadcrumbs' => $breadcrumbs,
                    'organization' => $organization,
                    'organizationHaveItems' => $this->checkOrganizationHaveItems($organization),
                    ...$topStats,
                    ...$pageListData,
                ];

                return view('design_1.panel.webinars.organization_classes.index', $data);
            }
        }

        abort(404);
    }

    private function getQueryBySource($organization, $source): Builder
    {
        if ($source == 'bundles') {
            $query = Bundle::query()->where('creator_id', $organization->id)
                ->where('status', 'active');
        } else {
            $query = Webinar::query()->where('creator_id', $organization->id)
                ->where('status', 'active');
        }

        return $query;
    }

    private function checkOrganizationHaveItems($organization)
    {
        $query = $this->getQueryBySource($organization, 'courses');
        $courses = $query->count();

        if ($courses < 1) {
            $query2 = $this->getQueryBySource($organization, 'bundles');
            $courses = $query2->count();
        }

        return ($courses > 0);
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

    private function handleFilters(Request $request, Builder $query): Builder
    {

        return $query;
    }

    private function getPageListData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = 8; // $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $courses = $query->with([
            'reviews' => function ($query) {
                $query->where('status', 'active');
            },
            'category',
            'teacher'
        ])
            ->orderBy('updated_at', 'desc')
            ->get();


        if ($request->ajax()) {
            return $this->handleAjaxResponse($request, $courses, $total, $count);
        }

        return [
            'courses' => $courses,
            'pagination' => $this->makePagination($request, $courses, $total, $count, true),
        ];
    }

    private function handleAjaxResponse(Request $request, $courses, $total, $count)
    {
        $html = "";
        $source = $request->get('source', 'courses');

        foreach ($courses as $courseItem) {
            $html .= '<div class="col-12 col-md-4 col-lg-3 col-xl-2 mt-20">';
            $html .= (string)view()->make("design_1.panel.webinars.organization_classes.grid_card", ['course' => $courseItem, 'pageSource' => $source]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $courses, $total, $count, true)
        ]);
    }

}
