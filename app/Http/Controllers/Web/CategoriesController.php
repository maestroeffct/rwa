<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\FeatureWebinar;
use App\Models\Sale;
use App\Models\Ticket;
use App\Models\Translation\CategoryTranslation;
use App\Models\Webinar;
use App\Models\WebinarFilterOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function index(Request $request, $categorySlug, $subCategorySlug = null)
    {
        if (!empty($categorySlug)) {
            $categoryQuery = Category::query()->where('slug', $categorySlug);

            if (!empty($subCategorySlug)) {
                $categoryQuery = Category::query()->where('slug', $subCategorySlug);
            }

            $categoryQuery->where('enable', true);

            $category = $categoryQuery->withCount('webinars')
                ->with(['filters' => function ($query) {
                    $query->with('options');
                }])->first();

            if (!empty($category)) {
                $categoryIds = [$category->id];

                if (!empty($category->subCategories) and count($category->subCategories)) {
                    $categoryIds = array_merge($categoryIds, $category->subCategories->pluck('id')->toArray());
                }

                $featureWebinars = FeatureWebinar::whereIn('page', ['categories', 'home_categories'])
                    ->where('status', 'publish')
                    ->whereHas('webinar', function ($q) use ($categoryIds) {
                        $q->where('status', Webinar::$active);
                        $q->whereHas('category', function ($q) use ($categoryIds) {
                            $q->whereIn('id', $categoryIds);
                        });
                    })
                    ->with([
                        'webinar' => function ($query) {
                            $query->with([
                                'teacher' => function ($qu) {
                                    $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                                }
                            ]);
                        }])
                    ->orderBy('updated_at', 'desc')
                    ->get();


                $webinarsQuery = Webinar::where('webinars.status', 'active')
                    ->whereIn('category_id', $categoryIds);

                $filterMaxPrice = $webinarsQuery->max('price') ?? 10000;

                $classesController = new ClassesController();
                $moreOptions = $request->get('moreOptions');
                $tableName = 'webinars';

                if (!empty($moreOptions) and is_array($moreOptions) and in_array('bundles', $moreOptions)) {
                    $webinarsQuery = Bundle::where('bundles.status', 'active')
                        ->whereIn('category_id', $categoryIds);

                    $tableName = 'bundles';
                    $classesController->tableName = 'bundles';
                    $classesController->columnId = 'bundle_id';
                }


                $webinarsQuery->where('private', false); // Ignore Private (Courses||Bundles)
                $webinarsQuery->where('only_for_students', false); // Ignore Available Only for Students

                $coursesRatingsCount = $classesController->getCoursesCountByRatings(deepClone($webinarsQuery));

                $webinarsQuery = $classesController->handleFilters($request, $webinarsQuery);

                $sort = $request->get('sort', null);

                if (empty($sort)) {
                    $webinarsQuery = $webinarsQuery->orderBy("{$tableName}.created_at", 'desc');
                }

                $getListData = $classesController->getListData($request, $webinarsQuery);

                if ($request->ajax()) {
                    return $getListData;
                }

                $seoSettings = getSeoMetas('categories');
                $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.categories_page_title');
                $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.categories_page_title');
                $pageRobot = getPageRobot('categories');

                $data = [
                    'pageTitle' => $pageTitle,
                    'pageDescription' => $pageDescription,
                    'pageRobot' => $pageRobot,
                    'category' => $category,
                    'featureWebinars' => $featureWebinars,
                    'sortFormAction' => $category->getUrl(),
                    'filterMaxPrice' => ($filterMaxPrice > 1000) ? $filterMaxPrice : 1000,
                    'coursesRatingsCount' => $coursesRatingsCount,
                    'pageBasePath' => $request->getPathInfo(),
                ];

                $data = array_merge($data, $getListData);

                return view('design_1.web.courses.lists.with_category', $data);
            }
        }

        abort(404);
    }

}
