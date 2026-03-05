<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Noticeboard;
use App\Models\NoticeboardStatus;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoticeboardController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_noticeboard_history");

        $user = auth()->user();

        if ($user->isOrganization() || $user->isTeacher()) {

            $query = Noticeboard::query()->where(function ($query) use ($user) {
                $query->where('organ_id', $user->id)
                    ->orWhere('instructor_id', $user->id);
            });

            $copyQuery = deepClone($query);
            $query = $this->handleFilters($request, $query);
            $getListData = $this->getListsData($request, $query);

            if ($request->ajax()) {
                return $getListData;
            }

            $totalNoticeboards = deepClone($copyQuery)->count();
            $totalCourseNotices = deepClone($copyQuery)
                ->whereNotNull('webinar_id')
                ->count();
            $totalGeneralNotices = $totalNoticeboards - $totalCourseNotices;

            $webinars = Webinar::query()->select('id')
                ->where('status', Webinar::$active)
                ->where(function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('teacher_id', $user->id);
                })
                ->get();

            $data = [
                'pageTitle' => trans('panel.noticeboards'),
                'webinars' => $webinars,
                'totalNoticeboards' => $totalNoticeboards,
                'totalCourseNotices' => $totalCourseNotices,
                'totalGeneralNotices' => $totalGeneralNotices,
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.noticeboard.lists.index', $data);
        }

        abort(404);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $webinarId = $request->get('webinar_id');
        $student_id = $request->get('student_id');
        $search = $request->get('search');
        $type = $request->get('type');
        $sort = $request->get('sort');

        // $from and $to
        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($webinarId)) {
            $query->where('webinar_id', $webinarId);
        }

        if (!empty($student_id)) {
            $query->where('user_id', $student_id);
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
            $html .= (string)view()->make('design_1.panel.noticeboard.lists.table_items', ['noticeboard' => $noticeboardRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $noticeboards, $total, $count, true)
        ]);
    }

    public function create()
    {
        $this->authorize("panel_noticeboard_create");

        $user = auth()->user();

        if ($user->isOrganization() || $user->isTeacher()) {

            if ($user->isTeacher()) {
                $webinars = Webinar::select('id')
                    ->where('status', Webinar::$active)
                    ->where(function ($query) use ($user) {
                        $query->where('creator_id', $user->id);
                        $query->orWhere('teacher_id', $user->id);
                    })
                    ->get();
            }

            $data = [
                'pageTitle' => trans('panel.new_noticeboard'),
                'webinars' => $webinars ?? null,
            ];

            return view('design_1.panel.noticeboard.create.index', $data);
        }

        abort(404);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_noticeboard_create");

        $user = auth()->user();

        if ($user->isOrganization() || $user->isTeacher()) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'title' => 'required|string|max:255',
                'type' => 'required',
                'message' => 'required',
                'webinar_id' => 'required_if:type,course'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validator->errors()
                ], 422);
            }

            $storeData = [
                'type' => $data['type'],
                'sender' => $user->full_name,
                'sender_id' => $user->id,
                'sender_type' => 'instructor',
                'title' => $data['title'],
                'message' => $data['message'],
                'created_at' => time()
            ];

            if ($user->isOrganization()) {
                $storeData['organ_id'] = $user->id;
            } else {
                $storeData['type'] = 'students';
                $storeData['instructor_id'] = $user->id;
                $storeData['webinar_id'] = $data['webinar_id'] ?? null;
            }

            Noticeboard::create($storeData);

            return response()->json([
                'code' => 200,
            ]);
        }

        abort(404);
    }

    public function edit($noticeboard_id)
    {
        $this->authorize("panel_noticeboard_create");

        $user = auth()->user();

        if ($user->isOrganization() || $user->isTeacher()) {
            $noticeboard = Noticeboard::where(function ($query) use ($user) {
                $query->where('organ_id', $user->id)
                    ->orWhere('instructor_id', $user->id);
            })->where('id', $noticeboard_id)
                ->first();

            if (!empty($noticeboard)) {

                if ($user->isTeacher()) {
                    $webinars = Webinar::select('id')
                        ->where('status', Webinar::$active)
                        ->where(function ($query) use ($user) {
                            $query->where('creator_id', $user->id);
                            $query->orWhere('teacher_id', $user->id);
                        })
                        ->get();
                }

                $data = [
                    'pageTitle' => trans('panel.noticeboards'),
                    'noticeboard' => $noticeboard,
                    'webinars' => $webinars ?? null,
                ];

                return view('design_1.panel.noticeboard.create.index', $data);
            }
        }

        abort(404);
    }

    public function update(Request $request, $noticeboard_id)
    {
        $this->authorize("panel_noticeboard_create");

        $user = auth()->user();

        if ($user->isOrganization() || $user->isTeacher()) {
            $noticeboard = Noticeboard::where(function ($query) use ($user) {
                $query->where('organ_id', $user->id)
                    ->orWhere('instructor_id', $user->id);
            })->where('id', $noticeboard_id)
                ->first();

            if (!empty($noticeboard)) {
                $data = $request->all();

                $validator = Validator::make($data, [
                    'title' => 'required|string|max:255',
                    'type' => 'required',
                    'message' => 'required',
                    'webinar_id' => 'required_if:type,course'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'code' => 422,
                        'errors' => $validator->errors()
                    ], 422);
                }

                $updateData = [
                    'type' => $data['type'],
                    'title' => $data['title'],
                    'message' => $data['message'],
                    'created_at' => time()
                ];

                if ($user->isTeacher()) {
                    $updateData['type'] = 'students';
                    $updateData['instructor_id'] = $user->id;
                    $updateData['webinar_id'] = $data['webinar_id'] ?? null;
                }

                $noticeboard->update($updateData);

                NoticeboardStatus::where('noticeboard_id', $noticeboard->id)->delete();

                return response()->json([
                    'code' => 200,
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function delete($noticeboard_id)
    {
        $this->authorize("panel_noticeboard_delete");

        $user = auth()->user();

        if ($user->isOrganization() || $user->isTeacher()) {
            $noticeboard = Noticeboard::where(function ($query) use ($user) {
                $query->where('organ_id', $user->id)
                    ->orWhere('instructor_id', $user->id);
            })->where('id', $noticeboard_id)
                ->first();

            if (!empty($noticeboard)) {
                $noticeboard->delete();

                return response()->json([
                    'code' => 200,
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function saveStatus($noticeboard_id)
    {
        $user = auth()->user();

        $status = NoticeboardStatus::where('user_id', $user->id)
            ->where('noticeboard_id', $noticeboard_id)
            ->first();

        if (empty($status)) {
            NoticeboardStatus::create([
                'user_id' => $user->id,
                'noticeboard_id' => $noticeboard_id,
                'seen_at' => time()
            ]);
        }

        return response()->json([], 200);
    }

}
