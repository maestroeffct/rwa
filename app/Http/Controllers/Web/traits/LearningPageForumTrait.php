<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\CourseForum;
use App\Models\CourseForumAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

trait LearningPageForumTrait
{
    public function forum(Request $request, $slug)
    {
        $user = auth()->user();


        $course = $this->getCourse($slug, $user);

        if ($course == 'not_access') {
            abort(404);
        }

        $query = CourseForum::where('webinar_id', $course->id);


        $forums = $this->handleForumFilters($request, $query)->get();

        foreach ($forums as $forum) {
            $forum->answer_count = $forum->answers->count();

            $usersAvatars = [];

            if ($forum->answer_count > 0) {
                foreach ($forum->answers as $answer) {
                    if (!empty($answer->user) and count($usersAvatars) < 3 and empty($usersAvatars[$answer->user->id])) {
                        $usersAvatars[$answer->user->id] = $answer->user;
                    }
                }
            }

            $forum->usersAvatars = $usersAvatars;
            $forum->lastAnswer = $forum->answers->last();

            $forum->resolved = $forum->answers->where('resolved', true)->first();
        }

        $questionsCount = $course->forums->count();
        $resolvedCount = $course->forums()->whereHas('answers', function ($query) {
            $query->where('resolved', true);
        })->count();
        $openQuestionsCount = $course->forums()->whereDoesntHave('answers', function ($query) {
            $query->where('resolved', true);
        })->count();

        $courseForumsIds = CourseForum::where('webinar_id', $course->id)->pluck('id')->toArray();
        $commentsCount = CourseForumAnswer::whereIn('forum_id', $courseForumsIds)->count();
        $activeUsersCount = CourseForumAnswer::whereIn('forum_id', $courseForumsIds)->select(DB::raw('count(distinct user_id) as count'))->first()->count;


        $data = [
            'pageTitle' => $course->title,
            'pageDescription' => $course->seo_description,
            'course' => $course,
            'forums' => $forums,
            'isForumPage' => true,
            'dontAllowLoadFirstContent' => true,
            'user' => $user,
            'questionsCount' => $questionsCount,
            'resolvedCount' => $resolvedCount,
            'openQuestionsCount' => $openQuestionsCount,
            'commentsCount' => $commentsCount,
            'activeUsersCount' => $activeUsersCount,
            "userIsCourseTeacher" => ($course->creator_id == $user->id or $course->teacher_id == $user->id or $user->isAdmin()),
        ];

        return view('design_1.web.courses.learning_page.index', $data);
    }

    private function handleForumFilters(Request $request, $query)
    {
        $search = $request->get('search');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
                $query->orWhere('description', 'like', "%$search%");
                $query->orWhereHas('answers', function ($query) use ($search) {
                    $query->where('description', 'like', "%$search%");
                });
            });
        }

        return $query;
    }

    public function getAskQuestionModal(Request $request, $slug)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, null);

        if ($course == 'not_access') {
            abort(404);
        }

        $data = [];
        $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.forum.includes.ask_question_modal", $data);

        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);
    }

    public function forumStoreNewQuestion(Request $request, $slug)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, null);

        if ($course == 'not_access') {
            abort(404);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $forum = CourseForum::create([
            'webinar_id' => $course->id,
            'user_id' => $user->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'pin' => false,
            'created_at' => time(),
        ]);

        // Attachment
        $this->handleForumAttachment($request, $forum, $user->id);

        if ($user->id != $course->creator_id and $user->id != $course->teacher_id) {
            $notifyOptions = [
                '[u.name]' => $user->full_name,
                '[c.title]' => $course->title,
            ];

            sendNotification('new_question_in_forum', $notifyOptions, $course->creator_id);
            sendNotification('new_question_in_forum', $notifyOptions, $course->teacher_id);
        }

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.your_question_in_course_forum_stored_successful'),
        ]);
    }

    public function getForumForEdit($slug, $forumId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, 'forums');

        if ($course == 'not_access') {
            abort(404);
        }

        $courseForum = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($courseForum)) {
            $data = [
                'course' => $course,
                'forum' => $courseForum,
            ];
            $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.forum.includes.ask_question_modal", $data);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

    public function updateForum(Request $request, $slug, $forumId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, null);

        if ($course == 'not_access') {
            abort(404);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $courseForum = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($courseForum)) {
            $courseForum->update([
                'title' => $data['title'],
                'description' => $data['description'],
            ]);

            // Attachment
            $this->handleForumAttachment($request, $courseForum, $user->id);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.your_question_updated_successful'),
            ]);
        }

        return response()->json([], 422);
    }

    private function handleForumAttachment(Request $request, $forum, $userId)
    {
        $path = $forum->attach ?? null;
        $file = $request->file('attachment');

        if (!empty($file)) {
            $destination = "webinars/forums/{$forum->id}";
            $path = $this->uploadFile($file, $destination, "attachment", $userId);
        }

        $forum->update([
            'attach' => $path,
        ]);
    }

    public function forumPinToggle(Request $request, $slug, $forumId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, null);

        if ($course == 'not_access' or !$course->isOwner($user->id)) {
            return response()->json([], 422);
        }

        $courseForum = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id)
            ->first();

        if (!empty($courseForum)) {
            $pin = !$courseForum->pin;

            $courseForum->update([
                'pin' => $pin,
            ]);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => $pin ? trans('update.learning_page_course_forum_pinned') : trans('update.learning_page_course_forum_unpin'),
            ]);
        }

        return response()->json([], 422);
    }

    public function forumDownloadAttach($slug, $forumId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, null);

        if ($course == 'not_access') {
            return response()->json([], 422);
        }

        $courseForum = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id)
            ->first();

        if (!empty($courseForum) and !empty($courseForum->attach)) {
            $filePath = public_path($courseForum->attach);

            if (file_exists($filePath)) {
                $fileInfo = pathinfo($filePath);
                $type = (!empty($fileInfo) and !empty($fileInfo['extension'])) ? $fileInfo['extension'] : '';

                $fileName = str_replace(' ', '-', $courseForum->title);
                $fileName = str_replace('.', '-', $fileName);
                $fileName .= '.' . $type;

                $headers = array(
                    'Content-Type: application/' . $type,
                );

                return response()->download($filePath, $fileName, $headers);
            }
        }

        abort(404);
    }

    public function getForumAnswers($slug, $forumId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, 'forums');

        if ($course == 'not_access') {
            abort(404);
        }

        $courseForum = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                },
                'answers' => function ($query) {
                    $query->with([
                        'user' => function ($query) {
                            $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                        }
                    ]);
                    $query->orderBy('pin', 'desc');
                    $query->orderBy('created_at', 'asc');
                }
            ])
            ->first();

        if (!empty($courseForum)) {
            $relatedForums = CourseForum::query()->where('id', '!=', $courseForum->id)
                ->where('webinar_id', $course->id)
                ->with([
                    'user' => function ($query) {
                        $query->select('id', 'full_name', 'avatar', 'avatar_settings', 'role_id', 'role_name');
                    }
                ])
                ->withCount([
                    'answers'
                ])
                ->inRandomOrder()
                ->limit(4)
                ->get();

            $data = [
                'pageTitle' => $course->title,
                'pageDescription' => $course->seo_description,
                'course' => $course,
                'isForumAnswersPage' => true,
                'dontAllowLoadFirstContent' => true,
                'courseForum' => $courseForum,
                'user' => $user,
                'relatedForums' => $relatedForums,
                "userIsCourseTeacher" => ($course->creator_id == $user->id or $course->teacher_id == $user->id or $user->isAdmin()),
            ];

            return view('design_1.web.courses.learning_page.index', $data);
        }

        abort(404);
    }

    private function getRelatedForums()
    {

    }

    public function storeForumAnswers(Request $request, $slug, $forumId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, 'forums');

        if ($course == 'not_access') {
            return response()->json([], 422);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $courseForum = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id)
            ->first();

        if (!empty($courseForum)) {
            CourseForumAnswer::create([
                'forum_id' => $courseForum->id,
                'user_id' => $user->id,
                'description' => $data['description'],
                'pin' => false,
                'resolved' => false,
                'created_at' => time(),
            ]);

            if ($user->id != $courseForum->user_id) {
                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[c.title]' => $course->title,
                ];

                sendNotification('new_answer_in_forum', $notifyOptions, $courseForum->user_id);
            }

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'msg' => trans('update.your_reply_stored_successful')
            ]);
        }

        return response()->json([], 422);
    }

    public function answerEdit(Request $request, $slug, $forumId, $answerId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, 'forums');

        $courseForumQuery = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id);

        if (!$course->isOwner($user->id)) {
            $courseForumQuery = $courseForumQuery->where('user_id', $user->id);
        }

        $courseForum = $courseForumQuery->first();

        if (!empty($courseForum)) {
            $answer = CourseForumAnswer::where('forum_id', $courseForum->id)
                ->where('user_id', $user->id)
                ->where('id', $answerId)
                ->first();

            if (!empty($answer)) {
                $data = [
                    'course' => $course,
                    'courseForum' => $courseForum,
                    'answer' => $answer,
                ];
                $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.forum.includes.edit_answer_modal", $data);

                return response()->json([
                    'code' => 200,
                    'html' => $html,
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function answerUpdate(Request $request, $slug, $forumId, $answerId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, 'forums');

        if ($course == 'not_access') {
            return response()->json([], 422);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $courseForum = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id)
            ->first();

        if (!empty($courseForum)) {
            $answer = CourseForumAnswer::where('forum_id', $courseForum->id)
                ->where('user_id', $user->id)
                ->where('id', $answerId)
                ->first();

            if (!empty($answer)) {

                $answer->update([
                    'description' => $data['description']
                ]);

                return response()->json([
                    'code' => 200
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function answerMarkAsResolvedModal($slug, $forumId, $answerId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, 'forums');

        $courseForumQuery = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id);

        if (!$course->isOwner($user->id)) {
            $courseForumQuery = $courseForumQuery->where('user_id', $user->id);
        }

        $courseForum = $courseForumQuery->first();


        if (!empty($courseForum) and ($course->isOwner($user->id) or $user->id == $courseForum->user_id)) {
            $data = [];
            $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.forum.includes.mark_as_resolved_modal", $data);

            return response()->json([
                'code' => 200,
                'html' => $html,
            ]);
        }

        return response()->json([], 422);
    }

    public function answerMarkAsResolved($slug, $forumId, $answerId)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, 'forums');

        $courseForumQuery = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id);

        if (!$course->isOwner($user->id)) {
            $courseForumQuery = $courseForumQuery->where('user_id', $user->id);
        }

        $courseForum = $courseForumQuery->first();

        if (!empty($courseForum) and ($course->isOwner($user->id) or $user->id == $courseForum->user_id)) {
            $answer = CourseForumAnswer::where('forum_id', $courseForum->id)
                ->where('id', $answerId)
                ->first();

            if (!empty($answer)) {
                $answer->update([
                    'resolved' => true,
                ]);

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => trans('update.answer_mark_as_resolved_successfully'),
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function answerTogglePinOrResolved(Request $request, $slug, $forumId, $answerId, $togglePinOrResolved)
    {
        $user = auth()->user();

        $course = $this->getCourse($slug, $user, 'forums');

        if ($course == 'not_access' or (in_array($togglePinOrResolved, ['pin', 'un_pin']) and !$course->isOwner($user->id))) {
            return response()->json([], 422);
        }

        $courseForumQuery = CourseForum::where('id', $forumId)
            ->where('webinar_id', $course->id);

        if (!$course->isOwner($user->id)) {
            $courseForumQuery = $courseForumQuery->where('user_id', $user->id);
        }

        $courseForum = $courseForumQuery->first();

        if (!empty($courseForum)) {
            $answer = CourseForumAnswer::where('forum_id', $courseForum->id)
                ->where('id', $answerId)
                ->first();

            if (!empty($answer)) {
                $updateData = [];
                $msg = null;

                if ($togglePinOrResolved == 'pin') {
                    $updateData['pin'] = true;
                    $msg = trans('update.post_pined_successfully');
                } else if ($togglePinOrResolved == 'un_pin') {
                    $updateData['pin'] = false;
                    $msg = trans('update.post_unpined_successfully');
                } else if ($togglePinOrResolved == 'mark_as_not_resolved') {
                    $updateData['resolved'] = false;
                    $msg = trans('update.answer_mark_as_not_resolved_successfully');
                } else if ($togglePinOrResolved == 'mark_as_resolved') {
                    $updateData['resolved'] = true;
                    $msg = trans('update.answer_mark_as_resolved_successfully');
                }


                if (!empty($updateData)) {
                    $answer->update($updateData);
                }

                return response()->json([
                    'code' => 200,
                    'title' => trans('public.request_success'),
                    'msg' => $msg,
                ]);
            }
        }

        return response()->json([], 422);
    }
}
