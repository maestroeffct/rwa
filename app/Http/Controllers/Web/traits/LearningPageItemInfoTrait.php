<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\Certificate;
use App\Models\CourseLearningLastView;
use App\Models\File;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\Session;
use App\Models\TextLesson;
use App\Models\Webinar;
use App\Models\WebinarAssignment;
use App\Models\WebinarChapter;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait LearningPageItemInfoTrait
{
    public function getItemInfo(Request $request, $courseSlug)
    {
        $user = auth()->user();
        $data = $request->all();

        $validator = Validator::make($data, [
            'type' => 'required|in:file,session,text_lesson,quiz,assignment,course_certificate,quiz_certificate',
            'id' => 'required_if:type,file,session,text_lesson,quiz',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $course = Webinar::where('slug', $courseSlug)->first();

        if (empty($course) or (!$course->checkUserHasBought($user) and !$course->canAccess($user) and !$user->isAdmin())) {
            return response()->json([], 404);
        }

        $type = $data['type'];
        $id = $data['id'];
        $quizId = !empty($data['quiz_id']) ? $data['quiz_id'] : null;

        switch ($type) {
            case 'file':
                return $this->getFileInfo($id, $course);
            case 'text_lesson':
                return $this->getTextLessonInfo($id, $course);
            case 'session':
                return $this->getSessionInfo($id, $course);
            case 'quiz':
                return $this->getQuizInfo($id, $course);
            case 'assignment':
                return $this->getAssignmentInfo($request, $course);
            case 'quiz_certificate':
            case 'course_certificate':
                return $this->getCertificateInfo($type, $id, $quizId, $course);
        }
    }

    private function checkCourseAccess($course): bool
    {
        $user = auth()->user();

        return (!empty($course) and ($course->checkUserHasBought($user) or !empty($course->getInstallmentOrder())));
    }

    private function checkUserIsInstructor($user, $course): bool
    {
        $result = $course->isOwner($user->id);

        if (!$result) {
            $result = $course->isPartnerTeacher($user->id);
        }

        if (!$result) {
            $result = $user->isAdmin();
        }

        return $result;
    }

    private function getFileInfo($id, $course)
    {
        $user = auth()->user();

        $file = File::select('id', 'downloadable', 'webinar_id', 'chapter_id', 'storage', 'online_viewer', 'file', 'file_type')
            ->where('id', $id)
            ->where('webinar_id', $course->id)
            ->where('status', WebinarChapter::$chapterActive)
            ->with([
                'personalNote' => function ($query) use ($user) {
                    $query->where('user_id', !empty($user) ? $user->id : null);
                }
            ])
            ->first();

        $checkSequenceContent = !empty($file) ? $file->checkSequenceContent() : null;
        $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

        if (!empty($file) and ($file->accessibility == 'free' or $this->checkCourseAccess($course)) and !$sequenceContentHasError) {

            $filePath = url($file->file);

            if (in_array($file->storage, ['s3', 'external_link', 'secure_host'])) {
                $filePath = $file->file;
            }

            // Learning Last View
            $this->storeCourseLearningLastView($file->webinar_id, $file->id, 'file');

            $filePersonalNote = $file->personalNote()->where('user_id', $user->id)->first();
            $hasPersonalNote = (!empty($filePersonalNote) and !empty($filePersonalNote->note));

            $data = [
                'course' => $course,
                'file' => $file,
                'filePath' => $filePath,
                'hasPersonalNote' => $hasPersonalNote,
            ];
            $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.file", $data);

            return response()->json([
                'code' => 200,
                'type' => 'file',
                'html' => $html,
            ]);
        }

        abort(403);
    }

    private function getSessionInfo($id, $course)
    {
        $user = auth()->user();

        $session = Session::where('id', $id)
            ->where('webinar_id', $course->id)
            ->where('status', WebinarChapter::$chapterActive)
            ->with([
                'personalNote' => function ($query) use ($user) {
                    $query->where('user_id', !empty($user) ? $user->id : null);
                }
            ])
            ->first();

        $checkSequenceContent = !empty($session) ? $session->checkSequenceContent() : null;
        $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

        if (!empty($session) and $this->checkCourseAccess($course) and !$sequenceContentHasError) {

            // Learning Last View
            $this->storeCourseLearningLastView($session->webinar_id, $session->id, 'session');

            $isFinished = $session->isFinished();
            $isStarted = (time() > $session->date);

            $sessionPersonalNote = $session->personalNote()->where('user_id', $user->id)->first();
            $hasPersonalNote = (!empty($sessionPersonalNote) and !empty($sessionPersonalNote->note));

            $data = [
                'course' => $course,
                'session' => $session,
                'isFinished' => $isFinished,
                'isStarted' => $isStarted,
                'authUser' => $user,
                'userIsInstructor' => $this->checkUserIsInstructor($user, $course),
                'hasPersonalNote' => $hasPersonalNote,
            ];
            $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.session", $data);

            return response()->json([
                'code' => 200,
                'type' => 'session',
                'html' => $html,
            ]);
        }

        abort(403);
    }

    private function checkQuizResult($quiz)
    {
        $user = auth()->user();

        $userQuizDone = QuizzesResult::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $canDownloadCertificate = false;
        $canTryAgainQuiz = false;
        $remainingTryAgain = 0;
        $notParticipated = ($userQuizDone->count() < 1);

        $quiz->result_count = 0;

        if (count($userQuizDone)) {
            $quiz->user_grade = $userQuizDone->first()->user_grade;
            $quiz->result_count = $userQuizDone->count();
            $quiz->result = $userQuizDone->first();

            $status_pass = false;
            foreach ($userQuizDone as $result) {
                if ($result->status == QuizzesResult::$passed) {
                    $status_pass = true;
                }
            }

            $quiz->result_status = $status_pass ? QuizzesResult::$passed : $userQuizDone->first()->status;

            if ($quiz->certificate and $quiz->result_status == QuizzesResult::$passed) {
                $canDownloadCertificate = true;
            }
        }

        if (!isset($quiz->attempt) or (count($userQuizDone) < $quiz->attempt and $quiz->result_status !== QuizzesResult::$passed)) {
            $canTryAgainQuiz = true;

            if (empty($quiz->attempt)) {
                $remainingTryAgain = 'unlimited';
            } else {
                $remainingTryAgain = $quiz->attempt - count($userQuizDone);
            }
        }

        $quiz->remaining_try_again = $remainingTryAgain;
        $quiz->can_try = $canTryAgainQuiz;
        $quiz->can_download_certificate = $canDownloadCertificate;
        $quiz->not_participated = $notParticipated;
        $quiz->participated_students = $quiz->quizResults()->groupBy('user_id')->count();
        $quiz->passed_students = $quiz->quizResults()->groupBy('user_id')->where('status', 'passed')->count();
        $quiz->failed_students = $quiz->quizResults()->groupBy('user_id')->where('status', 'failed')->count();

        $showWaiting = false;
        if ($quiz->hasDescriptiveQuestion()) {
            $showWaiting = true;
            $quiz->waiting_students = $quiz->quizResults()->groupBy('user_id')->where('status', 'waiting')->count();
        }
        $quiz->show_waiting = $showWaiting;

        $quiz->average_grade = $quiz->quizResults()->whereIn('status', ['passed', 'failed'])->avg('user_grade');

        $quiz->questions_grade = $quiz->quizQuestions->sum('grade');

        return $quiz;
    }

    private function getQuizInfo($id, $course)
    {
        $user = auth()->user();
        $quiz = Quiz::query()->where('id', $id)
            ->where('webinar_id', $course->id)
            ->where('status', WebinarChapter::$chapterActive)
            ->with([
                'personalNote' => function ($query) use ($user) {
                    $query->where('user_id', !empty($user) ? $user->id : null);
                },
                'quizResults' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
            ->withCount([
                'quizQuestions'
            ])
            ->first();

        if (!empty($quiz) and $this->checkCourseAccess($course)) {
            $quiz = $this->checkQuizResult($quiz);

            $canStart = true;

            if (!$quiz->checkCanAccessByExpireDays()) {
                $canStart = false;
            }

            if (!$quiz->checkUserCanStartByAttempt()) {
                $canStart = false;
            }

            $quiz->can_start = $canStart;

            // Learning Last View
            $this->storeCourseLearningLastView($quiz->webinar_id, $quiz->id, 'quiz');

            $hasPersonalNote = (!empty($quiz->personalNote) and !empty($quiz->personalNote->note));

            $expireTimestamp = $quiz->getExpireTimestamp();

            $data = [
                'course' => $course,
                'quiz' => $quiz,
                'authUser' => $user,
                'userIsInstructor' => $this->checkUserIsInstructor($user, $course),
                'hasPersonalNote' => $hasPersonalNote,
                'expireTimestamp' => $expireTimestamp,
            ];
            $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.quiz", $data);

            return response()->json([
                'code' => 200,
                'type' => 'quiz',
                'html' => $html,
            ]);
        }

        abort(403);
    }

    private function getTextLessonInfo($id, $course)
    {
        $user = auth()->user();

        $textLesson = TextLesson::query()->where('id', $id)
            ->where('webinar_id', $course->id)
            ->where('status', WebinarChapter::$chapterActive)
            ->with([
                'attachments' => function ($query) {
                    $query->with('file');
                },
                'learningStatus' => function ($query) use ($user) {
                    $query->where('user_id', !empty($user) ? $user->id : null);
                },
                'personalNote' => function ($query) use ($user) {
                    $query->where('user_id', !empty($user) ? $user->id : null);
                }
            ])
            ->first();

        $checkSequenceContent = !empty($textLesson) ? $textLesson->checkSequenceContent() : null;
        $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

        if (!empty($textLesson) and ($textLesson->accessibility == 'free' or $this->checkCourseAccess($course)) and !$sequenceContentHasError) {

            // Learning Last View
            $this->storeCourseLearningLastView($textLesson->webinar_id, $textLesson->id, 'text_lesson');

            $hasPersonalNote = (!empty($textLesson->personalNote) and !empty($textLesson->personalNote->note));

            $data = [
                'course' => $course,
                'textLesson' => $textLesson,
                'hasPersonalNote' => $hasPersonalNote,
            ];

            $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.text_lesson", $data);

            return response()->json([
                'code' => 200,
                'type' => 'text_lesson',
                'html' => $html,
            ]);
        }

        abort(403);
    }

    private function storeCourseLearningLastView($courseId, $itemId, $itemType)
    {

        CourseLearningLastView::query()->updateOrCreate([
            'user_id' => auth()->id(),
            'webinar_id' => $courseId,
        ], [
            'item_id' => $itemId,
            'item_type' => $itemType,
            'visited_at' => time()
        ]);
    }

    private function getCertificateInfo($type, $id, $quizId, $course)
    {
        $user = auth()->user();

        if ($this->checkCourseAccess($course)) {
            $userIsInstructor = $this->checkUserIsInstructor($user, $course);

            $data = [
                'type' => $type,
                'userIsInstructor' => $userIsInstructor,
            ];

            $participatedUsersIds = [];
            $participatedUsers = [];

            if ($type == "course_certificate") {
                $courseCertificate = Certificate::query()->where('type', 'course')
                    ->where('student_id', $user->id)
                    ->where('webinar_id', $course->id)
                    ->first();

                $data['courseCertificate'] = $courseCertificate;

                if ($userIsInstructor) {
                    $participatedUsersIds = Certificate::query()->where('type', 'course')
                        ->where('webinar_id', $course->id)
                        ->pluck('student_id')
                        ->toArray();
                }

            } else if ($type == "quiz_certificate") {
                $quizResult = QuizzesResult::query()->where('user_id', $user->id)
                    ->where('id', $id)
                    ->first();

                $quiz = null;

                if (!empty($quizId)) {
                    $quiz = Quiz::query()
                        ->where('webinar_id', $course->id)
                        ->where('id', $quizId)
                        ->first();
                }

                if (empty($quiz) and !empty($quizResult)) {
                    $quiz = $quizResult->quiz;
                }

                $data['quizResult'] = $quizResult;
                $data['quiz'] = $quiz;

                if (!empty($quiz) and $userIsInstructor) {
                    $participatedUsersIds = QuizzesResult::query()->where('quiz_id', $quiz->id)
                        ->where('status', QuizzesResult::$passed)
                        ->pluck('user_id')
                        ->toArray();
                }
            }

            if (!empty($participatedUsersIds) and $userIsInstructor) {
                $participatedUsersIds = array_unique($participatedUsersIds);

                $participatedUsers = User::query()->select('id', 'full_name', 'avatar', 'avatar_settings')
                    ->whereIn('id', $participatedUsersIds)
                    ->limit(4)
                    ->inRandomOrder()
                    ->get();
            }

            $data['participatedUsersIds'] = $participatedUsersIds;
            $data['participatedUsers'] = $participatedUsers;

            $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.certificate", $data);

            return response()->json([
                'code' => 200,
                'type' => 'certificate',
                'html' => $html,
            ]);
        }

        return response()->json([], 403);
    }

    public function getItemSequenceContentInfo(Request $request, $courseSlug)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'type' => 'required|in:session,file,text_lesson,quiz,assignment',
            'item' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $course = Webinar::where('slug', $courseSlug)
            ->where('status', 'active')
            ->first();

        if (empty($course)) {
            return response()->json([], 404);
        }

        if ($this->checkCourseAccess($course)) {
            $user = auth()->user();

            $type = $requestData['type'];
            $id = $requestData['item'];

            $item = $this->getItemByTypeAndId($type, $id, $course->id);

            if (!empty($item)) {
                $checkSequenceContent = $item->checkSequenceContent();
                $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

                if ($sequenceContentHasError) {
                    $data = [
                        'item' => $item,
                        'checkSequenceContent' => $checkSequenceContent,
                    ];
                    $html = (string)view()->make("design_1.web.courses.learning_page.includes.modals.access_denied.index", $data);

                    return response()->json([
                        'code' => 200,
                        'html' => $html,
                    ]);
                }
            }
        }

        return response()->json([], 403);
    }

    private function getItemByTypeAndId($type, $id, $courseId)
    {
        $itemQuery = null;

        if ($type == "session") {
            $itemQuery = Session::query();
        } else if ($type == "file") {
            $itemQuery = File::query();
        } else if ($type == "text_lesson") {
            $itemQuery = TextLesson::query();
        } else if ($type == "quiz") {
            $itemQuery = Quiz::query();
        } else if ($type == "assignment") {
            $itemQuery = WebinarAssignment::query();
        }

        if (!empty($itemQuery)) {
            return $itemQuery->where('id', $id)
                ->where('webinar_id', $courseId)
                ->where('status', WebinarChapter::$chapterActive)
                ->first();
        }

        return null;
    }
}
