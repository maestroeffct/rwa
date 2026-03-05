<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Product;
use App\Models\Role;
use App\Models\UpcomingCourse;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $seoSettings = getSeoMetas('search');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.search_page_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.search_page_title');
        $pageRobot = getPageRobot('search');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'resultCount' => 0,
        ];

        $search = $request->get('search', null);

        if (!empty($search) and strlen($search) >= 3) {
            $searchData = $this->getSearchData($search);
            $data = array_merge($data, $searchData);
        }

        return view('design_1.web.search.index', $data);
    }

    private function getSearchData($search)
    {
        $webinarsQuery = Webinar::query()->where('status', 'active')
            ->where('private', false)
            ->where('only_for_students', false)
            ->where(function (Builder $query) use ($search) {
                $query->whereTranslationLike('title', "%$search%");
                $query->orWhereTranslationLike('description', "%$search%");
            })
            ->with([
                'teacher' => function ($query) {
                    $query->select('id', 'full_name', 'username', 'bio', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'reviews'
            ]);

        $webinarsCount = deepClone($webinarsQuery)->count();
        $webinars = $webinarsQuery
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $bundlesQuery = Bundle::query()->where('status', 'active')
            ->where('private', false)
            ->where('only_for_students', false)
            ->where(function (Builder $query) use ($search) {
                $query->whereTranslationLike('title', "%$search%");
                $query->orWhereTranslationLike('description', "%$search%");
            })
            ->with([
                'teacher' => function ($query) {
                    $query->select('id', 'full_name', 'username', 'bio', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
                'reviews'
            ]);

        $bundlesCount = deepClone($bundlesQuery)->count();
        $bundles = $bundlesQuery
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $upcomingCoursesQuery = UpcomingCourse::query()->where('status', 'active')
            ->where(function (Builder $query) use ($search) {
                $query->whereTranslationLike('title', "%$search%");
                $query->orWhereTranslationLike('description', "%$search%");
            })
            ->with([
                'teacher' => function ($query) {
                    $query->select('id', 'full_name', 'username', 'bio', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                },
            ]);

        $upcomingCoursesCount = deepClone($upcomingCoursesQuery)->count();
        $upcomingCourses = $upcomingCoursesQuery
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $productsQuery = Product::query()->where('status', 'active')
            ->where(function (Builder $query) use ($search) {
                $query->whereTranslationLike('title', "%$search%");
                $query->orWhereTranslationLike('summary', "%$search%");
                $query->orWhereTranslationLike('description', "%$search%");
            })
            ->with([
                'creator' => function ($query) {
                    $query->select('id', 'full_name', 'username', 'bio', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                }
            ]);

        $productsCount = deepClone($productsQuery)->count();
        $products = $productsQuery
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $postsQuery = Blog::query()->where('status', 'publish')
            ->where(function (Builder $query) use ($search) {
                $query->whereTranslationLike('title', "%$search%");
                $query->orWhereTranslationLike('description', "%$search%");
                $query->orWhereTranslationLike('content', "%$search%");
            })
            ->with([
                'author' => function ($query) {
                    $query->select('id', 'full_name', 'username', 'bio', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                }
            ]);

        $postsCount = deepClone($postsQuery)->count();
        $posts = $postsQuery
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $usersQuery = User::query()->where('status', 'active')
            ->where(function (Builder $query) use ($search) {
                $query->where('full_name', 'like', "%$search%");
                $query->orWhere('email', 'like', "%$search%");
                $query->orWhere('mobile', 'like', "%$search%");
            })
            ->where(function (Builder $query) {
                $query->where('role_name', Role::$teacher);
                $query->orWhere('role_name', Role::$organization);
            });

        $usersCount = deepClone($usersQuery)->count();

        $instructors = deepClone($usersQuery)->where('role_name', Role::$teacher)
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $organizations = deepClone($usersQuery)->where('role_name', Role::$organization)
            ->inRandomOrder()
            ->limit(20)
            ->get();

        return [
            'resultCount' => $webinarsCount + $bundlesCount + $upcomingCoursesCount + $productsCount + $postsCount + $usersCount,
            'webinars' => $webinars,
            'bundles' => $bundles,
            'products' => $products,
            'upcomingCourses' => $upcomingCourses,
            'posts' => $posts,
            'instructors' => $instructors,
            'organizations' => $organizations,
        ];
    }
}
