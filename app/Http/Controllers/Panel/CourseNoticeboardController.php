<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CourseNoticeboard;
use App\Models\CourseNoticeboardStatus;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseNoticeboardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_noticeboard_course_notices");

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $query = CourseNoticeboard::query()->where('creator_id', $user->id);

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query);

        if ($request->ajax()) {
            return $getListData;
        }


        $webinars = Webinar::select('id', 'category_id')
            ->where('status', Webinar::$active)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })
            ->get();

        $categoriesIds = $webinars->pluck('category_id')->toArray();
        $categories = Category::query()->whereIn('id', $categoriesIds)->get();


        $data = [
            'pageTitle' => trans('panel.noticeboards'),
            'isCourseNotice' => true,
            'webinars' => $webinars,
            'categories' => $categories,
        ];
        $data = array_merge($data, $getListData);

        return view('design_1.panel.noticeboard.lists.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $webinarId = $request->get('webinar_id');
        $color = $request->get('color');
        $category_id = $request->get('category_id');
        $search = $request->get('search');
        $type = $request->get('type');
        $sort = $request->get('sort');

        // $from and $to
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($webinarId)) {
            $query->where('webinar_id', $webinarId);
        }

        if (!empty($category_id)) {
            $query->whereHas('webinar', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }

        if (!empty($color)) {
            $query->where('color', $color);
        }

        if (!empty($search)) {
            $query->where('title', 'like', "%$search%");
        }

        if (!empty($type)) {
            if ($type == 'course') {
                $query->whereNotNull('webinar_id');
            } else {
                $query->where('type', $type);
            }
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'create_date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'create_date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $noticeboards = $query
            ->with([
                'webinar'
            ])
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $noticeboards, $total, $count);
        }

        return [
            'noticeboards' => $noticeboards,
            'pagination' => $this->makePagination($request, $noticeboards, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $noticeboards, $total, $count)
    {
        $html = "";

        foreach ($noticeboards as $noticeboardRow) {
            $html .= (string)view()->make('design_1.panel.noticeboard.lists.table_items', [
                'noticeboard' => $noticeboardRow,
                'isCourseNotice' => true,
            ]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $noticeboards, $total, $count, true)
        ]);
    }


    public function create()
    {
        $this->authorize("panel_noticeboard_course_notices_create");

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $webinars = Webinar::select('id')
            ->where('status', Webinar::$active)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })
            ->get();

        $data = [
            'pageTitle' => trans('panel.new_noticeboard'),
            'isCourseNotice' => true,
            'webinars' => $webinars,
        ];

        return view('design_1.panel.noticeboard.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_noticeboard_course_notices_create");

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'webinar_id' => 'required',
            'color' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $webinar = Webinar::where('id', $data['webinar_id'])->first();

        if (empty($webinar) or ($webinar->teacher_id != $user->id and $webinar->creator_id != $user->id)) {
            return response()->json([
                'code' => 422,
                'errors' => [
                    'webinar_id' => [trans('cart.course_not_found')]
                ]
            ], 422);
        }

        CourseNoticeboard::create([
            'creator_id' => $user->id,
            'webinar_id' => $webinar->id,
            'color' => $data['color'],
            'title' => $data['title'],
            'message' => $data['message'],
            'created_at' => time()
        ]);

        $studentsIds = $webinar->getStudentsIds();
        if (count($studentsIds)) {
            $notifyOptions = [
                '[c.title]' => $webinar->title,
                '[item_title]' => $data['title'],
                '[time.date]' => dateTimeFormat(time(), 'j M Y H:i')
            ];

            foreach ($studentsIds as $studentId) {
                sendNotification("new_course_notice", $notifyOptions, $studentId);
            }
        }

        return response()->json([
            'code' => 200,
            'redirectTo' => '/panel/course-noticeboard'
        ]);
    }

    public function edit($noticeboard_id)
    {
        $this->authorize("panel_noticeboard_course_notices_create");

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $noticeboard = CourseNoticeboard::where('creator_id', $user->id)
            ->where('id', $noticeboard_id)
            ->first();

        $webinars = Webinar::select('id')
            ->where('status', Webinar::$active)
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id);
                $query->orWhere('teacher_id', $user->id);
            })
            ->get();

        if (!empty($noticeboard)) {
            $data = [
                'pageTitle' => trans('panel.noticeboards'),
                'noticeboard' => $noticeboard,
                'webinars' => $webinars,
                'isCourseNotice' => true,
            ];

            return view('design_1.panel.noticeboard.create.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $noticeboard_id)
    {
        $this->authorize("panel_noticeboard_course_notices_create");

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $noticeboard = CourseNoticeboard::where('creator_id', $user->id)
            ->where('id', $noticeboard_id)
            ->first();

        if (!empty($noticeboard)) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required|string|max:255',
                'webinar_id' => 'required',
                'color' => 'required',
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validator->errors()
                ], 422);
            }

            $webinar = Webinar::where('id', $data['webinar_id'])->first();

            if (empty($webinar) or ($webinar->teacher_id != $user->id and $webinar->creator_id != $user->id)) {
                return response()->json([
                    'code' => 422,
                    'errors' => [
                        'webinar_id' => [trans('cart.course_not_found')]
                    ]
                ], 422);
            }

            $noticeboard->update([
                'webinar_id' => $webinar->id,
                'color' => $data['color'],
                'title' => $data['title'],
                'message' => $data['message'],
                'created_at' => time()
            ]);

            CourseNoticeboardStatus::where('noticeboard_id', $noticeboard->id)->delete();

            return response()->json([
                'code' => 200,
                'redirectTo' => '/panel/course-noticeboard'
            ]);
        }


        return response()->json([], 422);
    }

    public function delete($noticeboard_id)
    {
        $this->authorize("panel_noticeboard_delete");

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $noticeboard = CourseNoticeboard::where('creator_id', $user->id)
            ->where('id', $noticeboard_id)
            ->first();

        if (!empty($noticeboard)) {
            $noticeboard->delete();

            return response()->json([
                'code' => 200,
            ]);
        }

        return response()->json([], 422);
    }
}
