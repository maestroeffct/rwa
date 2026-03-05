<?php

namespace App\Http\Controllers\Panel;

use App\Enums\UploadSource;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Tag;
use App\Models\Translation\UpcomingCourseTranslation;
use App\Models\UpcomingCourse;
use App\Models\UpcomingCourseFilterOption;
use App\Models\UpcomingCourseFollower;
use App\Models\Webinar;
use App\Models\WebinarExtraDescription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpcomingCoursesController extends Controller
{

    public function __construct()
    {
        if (empty(getFeaturesSettings('upcoming_courses_status'))) {
            abort(404);
        }
    }

    public function index(Request $request)
    {
        $this->authorize("panel_upcoming_courses_lists");

        $user = auth()->user();

        $query = UpcomingCourse::query()
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            });

        $copyQuery = deepClone($query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }

        $totalCourses = deepClone($copyQuery)->count();
        $releasedCourses = deepClone($copyQuery)->whereNotNull('webinar_id')->count();
        $notReleased = deepClone($copyQuery)->whereNull('webinar_id')->count();
        $ids = deepClone($copyQuery)->pluck('id')->toArray();
        $followers = UpcomingCourseFollower::query()->whereIn('upcoming_course_id', $ids)->count();

        $data = [
            'pageTitle' => trans('update.my_upcoming_courses'),
            'totalCourses' => $totalCourses,
            'releasedCourses' => $releasedCourses,
            'notReleased' => $notReleased,
            'followers' => $followers,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.upcoming_courses.my_courses.index', $data);
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $upcomingCourses = $query
            ->withCount([
                'followers'
            ])
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
            $html .= (string)view()->make("design_1.panel.upcoming_courses.my_courses.grid_card", ['upcomingCourse' => $upcomingCourseRow]);
            $html .= '</div>';
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $upcomingCourses, $total, $count, true)
        ]);
    }

    public function create()
    {
        $this->authorize("panel_upcoming_courses_create");

        $user = auth()->user();
        $teachers = null;
        $isOrganization = $user->isOrganization();

        if ($isOrganization) {
            $teachers = $user->getOrganizationTeachers()->get();
        }

        $stepCount = empty(getGeneralOptionsSettings('direct_publication_of_upcoming_courses')) ? 4 : 3;

        $data = [
            'pageTitle' => trans('update.new_upcoming_course'),
            'currentStep' => 1,
            'userLanguages' => getUserLanguagesLists(),
            'isOrganization' => $isOrganization,
            'teachers' => $teachers,
            'stepCount' => $stepCount,
        ];

        return view('design_1.panel.upcoming_courses.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_upcoming_courses_create");

        $user = auth()->user();

        $rules = [
            'type' => 'required|in:webinar,course,text_lesson',
            'title' => 'required|max:255',
            'thumbnail' => 'required',
            'image_cover' => 'required',
            'description' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $upcomingCourse = UpcomingCourse::query()->create([
            'creator_id' => $user->id,
            'teacher_id' => $user->isTeacher() ? $user->id : (!empty($data['teacher_id']) ? $data['teacher_id'] : $user->id),
            'slug' => UpcomingCourse::makeSlug($data['title']),
            'type' => $data['type'],
            'status' => ((!empty($data['draft']) and $data['draft'] == 1) or (!empty($data['get_next']) and $data['get_next'] == 1)) ? UpcomingCourse::$isDraft : UpcomingCourse::$pending,
            'created_at' => time(),
        ]);

        if (!empty($upcomingCourse)) {
            // Handle Image and Video
            $upcomingCourse = $this->storeWebinarMedia($request, $upcomingCourse);

            UpcomingCourseTranslation::query()->updateOrCreate([
                'upcoming_course_id' => $upcomingCourse->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'seo_description' => $data['seo_description'],
                'summary' => $data['summary'],
            ]);

            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[item_title]' => $upcomingCourse->title,
            ];
            sendNotification("upcoming_course_submission", $notifyOptions, $user->id);
            sendNotification("upcoming_course_submission_for_admin", $notifyOptions, 1);


            $url = '/panel/upcoming_courses';
            if ($data['get_next'] == 1) {
                $url = '/panel/upcoming_courses/' . $upcomingCourse->id . '/step/2';
            }

            return redirect($url);
        }

        abort(500);
    }

    public function edit(Request $request, $id, $step = 1)
    {
        $this->authorize("panel_upcoming_courses_create");
        $stepCount = empty(getGeneralOptionsSettings('direct_publication_of_upcoming_courses')) ? 4 : 3;

        if ($step > $stepCount) {
            return redirect("/panel/upcoming_courses/{$id}/step/{$stepCount}");
        }


        $user = auth()->user();

        $isOrganization = $user->isOrganization();
        $locale = $request->get('locale', app()->getLocale());

        $data = [
            'currentStep' => $step,
            'stepCount' => $stepCount,
            'isOrganization' => $isOrganization,
            'userLanguages' => getUserLanguagesLists(),
            'locale' => mb_strtolower($locale),
            'defaultLocale' => getDefaultLocale(),
        ];

        $query = UpcomingCourse::query()->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            });

        if ($step == 1) {
            $data['teachers'] = $user->getOrganizationTeachers()->get();
        } elseif ($step == 2) {
            $query->with([
                'category' => function ($query) {
                    $query->with(['filters' => function ($query) {
                        $query->with('options');
                    }]);
                },
                'filterOptions',
                'tags',
            ]);

            $categories = Category::where('parent_id', null)
                ->with('subCategories')
                ->get();

            $data['categories'] = $categories;
        } elseif ($step == 3) {
            $query->with([
                'faqs' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'extraDescriptions' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            ]);
        }

        $upcomingCourse = $query->first();

        if (empty($upcomingCourse)) {
            abort(404);
        }

        $data['upcomingCourse'] = $upcomingCourse;
        $data['pageTitle'] = trans('public.edit') . ' | ' . $upcomingCourse->title;

        $definedLanguage = [];
        if ($upcomingCourse->translations) {
            $definedLanguage = $upcomingCourse->translations->pluck('locale')->toArray();
        }
        $data['definedLanguage'] = $definedLanguage;

        if ($step == 2) {
            $data['upcomingCourseTags'] = $upcomingCourse->tags->pluck('title')->toArray();

            $upcomingCourseCategoryFilters = !empty($upcomingCourse->category) ? $upcomingCourse->category->filters : [];

            if (empty($upcomingCourse->category) and !empty($request->old('category_id'))) {
                $category = Category::where('id', $request->old('category_id'))->first();

                if (!empty($category)) {
                    $upcomingCourseCategoryFilters = $category->filters;
                }
            }

            $data['upcomingCourseCategoryFilters'] = $upcomingCourseCategoryFilters;
        }

        return view('design_1.panel.upcoming_courses.create.index', $data);
    }

    public function update(Request $request, $id)
    {
        $this->authorize("panel_upcoming_courses_create");

        $user = auth()->user();

        $rules = [];
        $data = $request->all();
        $currentStep = $data['current_step'];
        $getStep = $data['get_step'];
        $getNextStep = (!empty($data['get_next']) and $data['get_next'] == 1);
        $isDraft = (!empty($data['draft']) and $data['draft'] == 1);
        $stepCount = empty(getGeneralOptionsSettings('direct_publication_of_upcoming_courses')) ? 4 : 3;

        $upcomingCourse = UpcomingCourse::query()->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (empty($upcomingCourse)) {
            abort(404);
        }


        if ($currentStep == 1) {
            $rules = [
                'type' => 'required|in:webinar,course,text_lesson',
                'title' => 'required|max:255',
                'description' => 'required',
            ];
        } else if ($currentStep == 2) {
            $rules = [
                'category_id' => 'required|exists:categories,id',
                'publish_date' => 'required',
                'capacity' => 'nullable|numeric',
                'price' => 'nullable|numeric',
                'duration' => 'nullable|numeric',
                'sections' => 'nullable|numeric',
                'parts' => 'nullable|numeric',
                'course_progress' => 'nullable|integer|between:0,100',
            ];
        }

        $this->validate($request, $rules);

        $directPublication = !empty(getGeneralOptionsSettings('direct_publication_of_upcoming_courses'));
        $upcomingCourseRulesRequired = false;

        if (!$directPublication and (($currentStep == $stepCount and !$getNextStep and !$isDraft) or (!$getNextStep and !$isDraft))) {
            $upcomingCourseRulesRequired = empty($data['rules']);
        }

        $status = ($isDraft or $upcomingCourseRulesRequired) ? UpcomingCourse::$isDraft : UpcomingCourse::$pending;

        if ($directPublication and !$getNextStep and !$isDraft) {
            $status = UpcomingCourse::$active;
        }

        $data['status'] = $status;

        if ($currentStep == 2) {
            $startDate = convertTimeToUTCzone($data['publish_date'], $data['timezone']);
            $data['publish_date'] = $startDate->getTimestamp();

            $data['support'] = (!empty($data['support']) and $data['support'] == "on");
            $data['certificate'] = (!empty($data['certificate']) and $data['certificate'] == "on");
            $data['include_quizzes'] = (!empty($data['include_quizzes']) and $data['include_quizzes'] == "on");
            $data['downloadable'] = (!empty($data['downloadable']) and $data['downloadable'] == "on");
            $data['forum'] = (!empty($data['forum']) and $data['forum'] == "on");
            $data['assignments'] = (!empty($data['assignments']) and $data['assignments'] == "on");
            $data['price'] = !empty($data['price']) ? convertPriceToDefaultCurrency($data['price']) : null;


            UpcomingCourseFilterOption::where('upcoming_course_id', $upcomingCourse->id)->delete();

            $filters = $request->get('filters', null);
            if (!empty($filters) and is_array($filters)) {
                foreach ($filters as $filter) {
                    UpcomingCourseFilterOption::create([
                        'upcoming_course_id' => $upcomingCourse->id,
                        'filter_option_id' => $filter
                    ]);
                }
            }

            Tag::where('upcoming_course_id', $upcomingCourse->id)->delete();
            if (!empty($request->get('tags'))) {
                $tags = explode(',', $request->get('tags'));

                foreach ($tags as $tag) {
                    Tag::create([
                        'upcoming_course_id' => $upcomingCourse->id,
                        'title' => $tag,
                    ]);
                }
            }
        } // .\ if $currentStep == 2

        if ($currentStep == 1) {

            UpcomingCourseTranslation::query()->updateOrCreate([
                'upcoming_course_id' => $upcomingCourse->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
                'seo_description' => $data['seo_description'],
                'summary' => $data['summary'],
            ]);
        }

        if ($currentStep == 3) {
            $webinarExtraDescriptionController = (new WebinarExtraDescriptionController());
            $webinarExtraDescriptionController->storeCompanyLogos($request, 'upcoming_course_id', $upcomingCourse->id, 'upcoming_courses');
        }

        unset($data['_token'],
            $data['current_step'],
            $data['draft'],
            $data['get_step'],
            $data['get_next'],
            $data['partners'],
            $data['tags'],
            $data['filters'],
            $data['ajax'],
            $data['title'],
            $data['description'],
            $data['seo_description'],
            $data['summary'],
            $data['companyLogos'],
        );

        if (empty($data['teacher_id']) and $user->isOrganization() and $upcomingCourse->creator_id == $user->id) {
            $data['teacher_id'] = $user->id;
        }

        $upcomingCourse->update($data);


        $url = '/panel/upcoming_courses';

        if ($getNextStep) {
            $nextStep = (!empty($getStep) and $getStep > 0) ? $getStep : $currentStep + 1;

            $url = '/panel/upcoming_courses/' . $upcomingCourse->id . '/step/' . (($nextStep <= $stepCount) ? $nextStep : $stepCount);
        }

        if ($upcomingCourseRulesRequired) {
            $url = '/panel/upcoming_courses/' . $upcomingCourse->id . '/step/4';

            return redirect($url)->withErrors(['rules' => trans('validation.required', ['attribute' => 'rules'])]);
        }

        return redirect($url);
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize("panel_upcoming_courses_delete");

        $user = auth()->user();

        $upcomingCourse = UpcomingCourse::where('id', $id)
            ->where('creator_id', $user->id)
            ->first();

        if (!$upcomingCourse) {
            abort(404);
        }

        $upcomingCourse->delete();

        return response()->json([
            'code' => 200,
            'redirect_to' => $request->get('redirect_to')
        ], 200);
    }

    protected function storeWebinarMedia(Request $request, $upcomingCourse)
    {
        $thumbnail = $upcomingCourse->thumbnail ?? null;
        $imageCover = $upcomingCourse->image_cover ?? null;
        $videoDemoSource = $upcomingCourse->video_demo_source ?? null;
        $videoDemo = $upcomingCourse->video_demo ?? null;


        if (!empty($request->file('thumbnail'))) {
            $thumbnail = $this->uploadFile($request->file('thumbnail'), "upcoming_courses/{$upcomingCourse->id}", 'thumbnail', $upcomingCourse->creator_id);
        }

        if (!empty($request->file('image_cover'))) {
            $imageCover = $this->uploadFile($request->file('image_cover'), "upcoming_courses/{$upcomingCourse->id}", 'image_cover', $upcomingCourse->creator_id);
        }


        if (in_array($request->get('video_demo_source'), UploadSource::urlPathItems) and !empty($request->get('demo_video_path'))) {
            $videoDemoSource = $request->get('video_demo_source');
            $videoDemo = $request->get('demo_video_path');
        } elseif ($request->get('video_demo_source') == UploadSource::UPLOAD and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::UPLOAD;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "upcoming_courses/{$upcomingCourse->id}", 'video', $upcomingCourse->creator_id);
        } elseif ($request->get('video_demo_source') == UploadSource::S3 and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::S3;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "upcoming_courses/{$upcomingCourse->id}", 'video', $upcomingCourse->creator_id, 'minio');
        } elseif ($request->get('video_demo_source') == UploadSource::SECURE_HOST and !empty($request->file('demo_video_local'))) {
            $videoDemoSource = UploadSource::SECURE_HOST;
            $videoDemo = $this->uploadFile($request->file('demo_video_local'), "upcoming_courses/{$upcomingCourse->id}", "upcoming_course_{$upcomingCourse->id}_video_demo", $upcomingCourse->creator_id, 'bunny');
        }

        $upcomingCourse->update([
            'thumbnail' => $thumbnail,
            'image_cover' => $imageCover,
            'video_demo_source' => $videoDemoSource,
            'video_demo' => $videoDemo,
        ]);

        return $upcomingCourse;
    }

    public function orderItems(Request $request)
    {
        $user = auth()->user();
        $data = $request->all();

        $validator = Validator::make($data, [
            'items' => 'required',
            'table' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $tableName = $data['table'];
        $itemIds = explode(',', $data['items']);

        if (!is_array($itemIds) and !empty($itemIds)) {
            $itemIds = [$itemIds];
        }

        if (!empty($itemIds) and is_array($itemIds) and count($itemIds)) {
            switch ($tableName) {
                case 'faqs':
                    foreach ($itemIds as $order => $id) {
                        Faq::where('id', $id)
                            ->where('creator_id', $user->id)
                            ->update(['order' => ($order + 1)]);
                    }
                    break;

                case 'webinar_extra_descriptions_learning_materials':
                    foreach ($itemIds as $order => $id) {
                        WebinarExtraDescription::where('id', $id)
                            ->where('creator_id', $user->id)
                            ->where('type', 'learning_materials')
                            ->update(['order' => ($order + 1)]);
                    }
                    break;

                case 'webinar_extra_descriptions_company_logos':
                    foreach ($itemIds as $order => $id) {
                        WebinarExtraDescription::where('id', $id)
                            ->where('creator_id', $user->id)
                            ->where('type', 'company_logos')
                            ->update(['order' => ($order + 1)]);
                    }
                    break;

                case 'webinar_extra_descriptions_requirements':
                    foreach ($itemIds as $order => $id) {
                        WebinarExtraDescription::where('id', $id)
                            ->where('creator_id', $user->id)
                            ->where('type', 'requirements')
                            ->update(['order' => ($order + 1)]);
                    }
                    break;
            }
        }

        return response()->json([
            'title' => trans('public.request_success'),
            'msg' => trans('update.items_sorted_successful')
        ]);
    }

    public function assignCourseModal($id)
    {
        $user = auth()->user();

        $upcomingCourse = UpcomingCourse::query()
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($upcomingCourse)) {
            $webinars = Webinar::query()
                ->select('id', 'creator_id', 'teacher_id')
                ->where('status', 'active')
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                })->get();

            $data = [
                'upcomingCourse' => $upcomingCourse,
                'webinars' => $webinars,
            ];

            $html = (string)view()->make('design_1.panel.upcoming_courses.my_courses.modals.assign_course_modal', $data);

            return response()->json([
                'code' => 200,
                'html' => $html
            ]);
        }

        abort(404);
    }

    public function storeAssignCourse(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->all();

        $validator = Validator::make($data, [
            'course' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $webinar = Webinar::query()
            ->where('id', $data['course'])
            ->where('status', 'active')
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (empty($webinar)) {
            return response([
                'code' => 422,
                'errors' => [
                    'course' => [trans('validation.required', ['attribute' => 'course'])]
                ],
            ], 422);
        }

        $upcomingCourse = UpcomingCourse::query()
            ->where('id', $id)
            ->whereNull('webinar_id')
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($upcomingCourse)) {

            $upcomingCourse->update([
                'webinar_id' => $webinar->id
            ]);


            $notifyOptions = [
                '[item_title]' => $upcomingCourse->title,
            ];
            sendNotification("upcoming_course_published", $notifyOptions, $upcomingCourse->teacher_id);

            foreach ($upcomingCourse->followers as $follower) {
                sendNotification("upcoming_course_published_for_followers", $notifyOptions, $follower->user_id);
            }


            return response()->json([
                'code' => 200,
                'msg' => trans('update.assign_published_course_successful')
            ]);
        }

        abort(404);
    }

    public function getContentItemByLocale(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'locale' => 'required',
            'relation' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();

        $upcomingCourse = UpcomingCourse::query()->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            })->first();

        if (!empty($upcomingCourse)) {

            $itemId = $data['item_id'];
            $locale = $data['locale'];
            $relation = $data['relation'];

            if (!empty($upcomingCourse->$relation)) {
                $item = $upcomingCourse->$relation->where('id', $itemId)->first();

                if (!empty($item)) {
                    foreach ($item->translatedAttributes as $attribute) {
                        try {
                            $item->$attribute = $item->translate(mb_strtolower($locale))->$attribute;
                        } catch (\Exception $e) {
                            $item->$attribute = null;
                        }
                    }

                    return response()->json([
                        'item' => $item
                    ], 200);
                }
            }
        }

        abort(403);
    }

}
