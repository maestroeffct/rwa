<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use Illuminate\Http\Request;

class RewardCoursesController extends Controller
{
    public function index(Request $request)
    {
        $webinarsQuery = Webinar::where('webinars.status', 'active')
            ->where('private', false)
            ->whereNotNull('points');

        $classesController = new ClassesController();


        $filterMaxPrice = $webinarsQuery->max('price') ?? 10000;
        $coursesRatingsCount = $classesController->getCoursesCountByRatings(deepClone($webinarsQuery));


        $webinarsQuery = $classesController->handleFilters($request, $webinarsQuery);

        $sort = $request->get('sort', null);

        if (empty($sort)) {
            $webinarsQuery = $webinarsQuery->orderBy('webinars.created_at', 'desc')
                ->orderBy('webinars.updated_at', 'desc');
        }

        $getListData = $classesController->getListData($request, $webinarsQuery);

        if ($request->ajax()) {
            return $getListData;
        }

        $seoSettings = getSeoMetas('reward_courses');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : '';
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : '';
        $pageRobot = getPageRobot('reward_courses');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'pageBasePath' => $request->getPathInfo(),
            'filterMaxPrice' => ($filterMaxPrice > 1000) ? $filterMaxPrice : 1000,
            'coursesRatingsCount' => $coursesRatingsCount,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.web.courses.lists.reward_courses', $data);
    }
}
