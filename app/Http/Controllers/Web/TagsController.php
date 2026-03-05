<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Product;
use App\Models\Role;
use App\Models\Tag;
use App\Models\UpcomingCourse;
use App\Models\Webinar;
use App\User;
use Illuminate\Http\Request;

class TagsController extends Controller
{

    public function index(Request $request, $type, $tag)
    {
        $tag = urldecode($tag);

        $seoSettings = getSeoMetas('tags');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('update.tags_page_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('update.tags_page_title');
        $pageRobot = getPageRobot('tags');


        $webinars = $this->getCoursesByTag($tag);
        $bundles = $this->getBundlesByTag($tag);
        $upcomingCourses = $this->getUpcomingCourseByTag($tag);

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'tag' => $tag,
            'resultCount' => count($webinars) + count($bundles) + count($upcomingCourses),
            'webinars' => $webinars,
            'bundles' => $bundles,
            'products' => null,
            'upcomingCourses' => $upcomingCourses,
            'posts' => null,
            'instructors' => null,
            'organizations' => null,
        ];

        return view('design_1.web.search.index', $data);
    }


    private function getCoursesByTag($tag)
    {
        $ids = Tag::query()->where('title', 'like', "%{$tag}%")
            ->whereNotNull('webinar_id')
            ->pluck('webinar_id')
            ->toArray();

        return Webinar::query()->whereIn('id', $ids)
            ->where('status', 'active')
            ->where('private', false)
            ->with([
                'teacher' => function ($query) {
                    $query->select('id', 'full_name', 'avatar', 'avatar_settings');
                },
                'reviews'
            ])
            ->inRandomOrder()
            ->limit(20)
            ->get();
    }

    private function getBundlesByTag($tag)
    {
        $ids = Tag::query()->where('title', 'like', "%{$tag}%")
            ->whereNotNull('bundle_id')
            ->pluck('bundle_id')
            ->toArray();

        return Bundle::query()->whereIn('id', $ids)
            ->where('status', 'active')
            ->with([
                'teacher' => function ($query) {
                    $query->select('id', 'full_name', 'avatar', 'avatar_settings');
                },
                'reviews'
            ])
            ->inRandomOrder()
            ->limit(20)
            ->get();
    }

    private function getUpcomingCourseByTag($tag)
    {
        $ids = Tag::query()->where('title', 'like', "%{$tag}%")
            ->whereNotNull('upcoming_course_id')
            ->pluck('upcoming_course_id')
            ->toArray();

        return UpcomingCourse::query()->whereIn('id', $ids)
            ->where('status', 'active')
            ->with([
                'teacher' => function ($query) {
                    $query->select('id', 'full_name', 'avatar', 'avatar_settings');
                }
            ])
            ->inRandomOrder()
            ->limit(20)
            ->get();
    }

}
