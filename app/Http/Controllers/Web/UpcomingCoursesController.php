<?php

namespace App\Http\Controllers\Web;

use App\Enums\MorphTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\CheckContentLimitationTrait;
use App\Mixins\Logs\VisitLogMixin;
use App\Models\AdvertisingBanner;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\UpcomingCourse;
use App\Models\UpcomingCourseFilterOption;
use App\Models\UpcomingCourseFollower;
use App\Models\UpcomingCourseReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpcomingCoursesController extends Controller
{
    use CheckContentLimitationTrait;

    public function index(Request $request)
    {
        $query = UpcomingCourse::query()->where('status', UpcomingCourse::$active);

        $filterMaxPrice = (deepClone($query)->max('price') + 10) * 10;

        $query = $this->handleFilters($request, $query);

        $getListData = $this->getListData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $categories = Category::getCategories();

        $seoSettings = getSeoMetas('upcoming_courses_lists');
        $pageTitle = $seoSettings['title'] ?? trans('update.upcoming_courses');
        $pageDescription = $seoSettings['description'] ?? '';
        $pageRobot = getPageRobot('upcoming_courses_lists');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'categoriesLists' => $categories,
            'seoSettings' => $seoSettings,
            'pageBasePath' => "/upcoming_courses",
            'filterMaxPrice' => ($filterMaxPrice > 1000) ? $filterMaxPrice : 1000,
        ];

        $data = array_merge($data, $getListData);

        return view('design_1.web.upcoming_courses.lists.index', $data);
    }

    private function handleFilters(Request $request, $query)
    {
        $free = $request->get('free_courses');
        $released = $request->get('released_courses');
        $sort = $request->get('sort');
        $type = $request->get('type');
        $moreOptions = $request->get('moreOptions');
        $categoryId = $request->get('category_id', null);
        $filterOption = $request->get('filter_option', null);
        $minPrice = $request->get('min_price', null);
        $maxPrice = $request->get('max_price', null);


        if (!empty($free)) {
            $query->where(function ($query) {
                $query->whereNull('price');
                $query->orWhere('price', '<', '1');
            });
        }

        if (!empty($released)) {
            $query->whereNotNull('webinar_id');
        }

        if (!empty($type) and count($type)) {
            $query->whereIn('type', $type);
        }

        if (!empty($minPrice)) {
            $query->where('price', '>', $minPrice);
        }

        if (!empty($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }

        if (!empty($moreOptions) and count($moreOptions)) {
            if (in_array('supported_courses', $moreOptions)) {
                $query->where('support', true);
            }

            if (in_array('quiz_included', $moreOptions)) {
                $query->where('include_quizzes', true);
            }

            if (in_array('certificate_included', $moreOptions)) {
                $query->where('certificate', true);
            }

            if (in_array('assignment_included', $moreOptions)) {
                $query->where('assignments', true);
            }

            if (in_array('course_forum_included', $moreOptions)) {
                $query->where('forum', true);
            }

            if (in_array('point_courses', $moreOptions)) {
                $query->where('points', true);
            }
        }


        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($filterOption) and is_array($filterOption)) {
            $upcomingIdsFilterOptions = UpcomingCourseFilterOption::whereIn('filter_option_id', $filterOption)
                ->pluck('upcoming_course_id')
                ->toArray();

            $upcomingIdsFilterOptions = array_unique($upcomingIdsFilterOptions);

            $query->whereIn('id', $upcomingIdsFilterOptions);
        }


        if (!empty($sort)) {
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'earliest_publish_date':
                    $query->orderBy('publish_date', 'asc');
                    break;
                case 'farthest_publish_date':
                    $query->orderBy('publish_date', 'desc');
                    break;
                case 'highest_price':
                    $query->orderBy('price', 'desc');
                    break;
                case 'lowest_price':
                    $query->orderBy('price', 'asc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListData(Request $request, $query)
    {
        $page = $request->get('page') ?? 1;
        $count = 9;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $upcomingCourses = $query->get();

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
        $html = (string)view()->make('design_1.web.upcoming_courses.components.cards.grids.index', [
            'upcomingCourses' => $upcomingCourses,
            'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-24",
            'withoutStyles' => true
        ]);

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $upcomingCourses, $total, $count, true)
        ]);
    }


    public function show(Request $request, $slug)
    {
        $user = null;

        if (auth()->check()) {
            $user = auth()->user();
        }


        $contentLimitation = $this->checkContentLimitation($user);
        if ($contentLimitation != "ok") {
            return $contentLimitation;
        }

        $upcomingCourse = UpcomingCourse::query()
            ->where('slug', $slug)
            ->where('status', UpcomingCourse::$active)
            ->with([
                'webinar' => function ($query) {
                    $query->where('status', 'active');
                },
                'tags',
                'followers',
                'faqs' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'extraDescriptions' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'teacher' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about', 'verified', 'cover_img', 'profile_secondary_image');
                },
                'comments' => function ($query) {
                    $query->where('status', 'active');
                    $query->whereNull('reply_id');
                    $query->with([
                        'user' => function ($query) {
                            $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
                        },
                        'replies' => function ($query) {
                            $query->where('status', 'active');
                            $query->with([
                                'user' => function ($query) {
                                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
                                }
                            ]);
                        }
                    ]);
                    $query->orderBy('created_at', 'desc');
                },
            ])
            ->first();

        if (!empty($upcomingCourse)) {
            $isFavorite = false;
            $followed = false;

            if (!empty($user)) {
                $isFavorite = Favorite::where('upcoming_course_id', $upcomingCourse->id)
                    ->where('user_id', $user->id)
                    ->first();

                $followed = UpcomingCourseFollower::query()
                    ->where('upcoming_course_id', $upcomingCourse->id)
                    ->where('user_id', $user->id)
                    ->first();
            }

            $followingUsersCount = UpcomingCourseFollower::query()->where('upcoming_course_id', $upcomingCourse->id)->count();
            $followingUsers = UpcomingCourseFollower::query()
                ->where('upcoming_course_id', $upcomingCourse->id)
                ->inRandomOrder()
                ->take(4)
                ->with([
                    'user' => function ($query) {
                        $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
                    }
                ])
                ->get();


            $advertisingBanners = AdvertisingBanner::where('published', true)
                ->whereIn('position', ['upcoming_course', 'upcoming_course_sidebar'])
                ->get();

            $commentController = new CommentController();
            $upcomingCourseComments = $commentController->getComments($request, 'upcoming_course', $upcomingCourse->id);

            // Visit Logs
            $visitLogMixin = new VisitLogMixin();
            $visitLogMixin->storeVisit($request, $upcomingCourse->creator_id, $upcomingCourse->id, MorphTypesEnum::UPCOMING_COURSE);

            $pageRobot = getPageRobot('upcoming_course_show'); // index

            $data = [
                'pageTitle' => $upcomingCourse->title,
                'pageDescription' => $upcomingCourse->seo_description,
                'pageRobot' => $pageRobot,
                'upcomingCourse' => $upcomingCourse,
                'isFavorite' => $isFavorite,
                'followed' => $followed,
                'advertisingBanners' => $advertisingBanners->where('position', 'upcoming_course'),
                'advertisingBannersSidebar' => $advertisingBanners->where('position', 'upcoming_course_sidebar'),
                'followingUsers' => $followingUsers,
                'followingUsersCount' => $followingUsersCount,
                'upcomingCourseComments' => $upcomingCourseComments,
            ];

            return view('design_1.web.upcoming_courses.show.index', $data);
        }

        abort(404);
    }

    public function toggleFollow(Request $request, $slug)
    {
        $user = auth()->user();
        $upcomingCourse = UpcomingCourse::query()
            ->where('slug', $slug)
            ->where('status', UpcomingCourse::$active)
            ->first();

        if (!empty($user) and !empty($upcomingCourse)) {

            if (in_array($user->id, [$upcomingCourse->teacher_id, $upcomingCourse->creator_id])) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.cant_follow_your_upcoming_course'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }

            $follow = UpcomingCourseFollower::query()
                ->where('upcoming_course_id', $upcomingCourse->id)
                ->where('user_id', $user->id)
                ->first();

            $add = false;

            if (!empty($follow)) {
                $follow->delete();
            } else {
                $add = true;

                UpcomingCourseFollower::query()->create([
                    'upcoming_course_id' => $upcomingCourse->id,
                    'user_id' => $user->id,
                    'created_at' => time()
                ]);

                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[item_title]' => $upcomingCourse->title,
                ];
                sendNotification("upcoming_course_followed", $notifyOptions, $upcomingCourse->teacher_id);
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => $add ? trans('update.the_course_has_been_added_to_your_follow_list') : trans('update.the_course_has_been_removed_from_your_follow_list')
            ]);
        }

        abort(422);
    }

    public function favorite(Request $request, $slug)
    {
        $user = auth()->user();
        $upcomingCourse = UpcomingCourse::query()
            ->where('slug', $slug)
            ->where('status', UpcomingCourse::$active)
            ->first();

        if (!empty($user) and !empty($upcomingCourse)) {
            $isFavorite = Favorite::where('upcoming_course_id', $upcomingCourse->id)
                ->where('user_id', $user->id)
                ->first();

            if (empty($isFavorite)) {
                Favorite::create([
                    'user_id' => $user->id,
                    'upcoming_course_id' => $upcomingCourse->id,
                    'created_at' => time()
                ]);
            } else {
                $isFavorite->delete();
            }

            return response()->json([
                'code' => 200,
            ]);
        }

        abort(422);
    }

    public function report(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'reason' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $upcomingCourse = UpcomingCourse::query()
            ->where('id', $id)
            ->where('status', UpcomingCourse::$active)
            ->first();

        if (!empty($user) and !empty($upcomingCourse)) {

            UpcomingCourseReport::create([
                'user_id' => $user->id,
                'upcoming_course_id' => $upcomingCourse->id,
                'reason' => $data['reason'],
                'message' => $data['message'],
                'created_at' => time()
            ]);

            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[content_type]' => trans('update.upcoming_course')
            ];
            sendNotification("new_report_item_for_admin", $notifyOptions, 1);

            return response()->json([
                'code' => 200
            ], 200);
        }

        abort(422);
    }

    public function getShareModal($slug)
    {
        $upcomingCourse = UpcomingCourse::query()->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($upcomingCourse)) {
            $data = [
                'upcomingCourse' => $upcomingCourse
            ];

            $html = (string)view("design_1.web.upcoming_courses.show.includes.modals.share_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 400);
    }

    public function getReportModal($slug)
    {
        $upcomingCourse = UpcomingCourse::query()->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($upcomingCourse)) {
            $data = [
                'upcomingCourse' => $upcomingCourse
            ];

            $html = (string)view("design_1.web.upcoming_courses.show.includes.modals.report_modal", $data)->render();

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 400);
    }
}
