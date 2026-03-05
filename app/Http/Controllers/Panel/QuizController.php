<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\Translation\QuizTranslation;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\User;
use App\Models\Webinar;
use App\Models\QuizzesResult;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesQuestionsAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("panel_quizzes_lists");

        $user = auth()->user();

        $query = Quiz::where('quizzes.creator_id', $user->id);
        $query = $this->filters($request, $query);

        $getListData = $this->getPageListData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }

        $topStats = $this->getTopStats($user);

        $allQuizzesLists = Quiz::select('id', 'webinar_id')
            ->where('creator_id', $user->id)
            ->where('status', 'active')
            ->get();


        $data = [
            'pageTitle' => trans('quiz.quizzes_list_page_title'),
            'allQuizzesLists' => $allQuizzesLists
        ];
        $data = array_merge($data, $getListData);
        $data = array_merge($data, $topStats);

        return view('design_1.panel.quizzes.lists.index', $data);
    }

    private function getTopStats($user)
    {
        $query = Quiz::where('creator_id', $user->id);
        $quizIds = $query->pluck('id')->toArray();

        $quizzesCount = deepClone($query)->count();
        $questionsCount = QuizzesQuestion::query()->whereIn('quiz_id', $quizIds)->count();
        $userCount = QuizzesResult::query()->whereIn('quiz_id', $quizIds)->count();


        return [
            'quizzesCount' => $quizzesCount,
            'questionsCount' => $questionsCount,
            'userCount' => $userCount,
        ];
    }

    public function filters(Request $request, $query)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $quiz_id = $request->get('quiz_id');
        $total_mark = $request->get('total_mark');
        $status = $request->get('status');
        $active_quizzes = $request->get('active_quizzes');
        $questions_type = $request->get('questions_type');
        $sort = $request->get('sort');


        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($quiz_id) and $quiz_id != 'all') {
            $query->where('id', $quiz_id);
        }

        if ($status and $status !== 'all') {
            $query->where('status', strtolower($status));
        }

        if ($questions_type and $questions_type !== 'all') {
            if ($questions_type == "multiple") {
                $query->whereHas('quizQuestions', function ($query) {
                    $query->where('type', 'multiple');
                });
            } else if ($questions_type == "descriptive") {
                $query->whereHas('quizQuestions', function ($query) {
                    $query->where('type', 'descriptive');
                });
            }
        }

        if (!empty($active_quizzes)) {
            $query->where('status', 'active');
        }

        if ($total_mark) {
            $query->where('total_mark', '>=', $total_mark);
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'questions_asc':
                    $query->join('quizzes_questions', 'quizzes_questions.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_questions.quiz_id', DB::raw('count(quizzes_questions.quiz_id) as questions_count'))
                        ->groupBy('quizzes_questions.quiz_id')
                        ->orderBy('questions_count', 'asc');
                    break;
                case 'questions_desc':
                    $query->join('quizzes_questions', 'quizzes_questions.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_questions.quiz_id', DB::raw('count(quizzes_questions.quiz_id) as questions_count'))
                        ->groupBy('quizzes_questions.quiz_id')
                        ->orderBy('questions_count', 'desc');
                    break;
                case 'time_asc':
                    $query->orderBy('time', 'asc');
                    break;
                case 'time_desc':
                    $query->orderBy('time', 'desc');
                    break;
                case 'pass_mark_asc':
                    $query->orderBy('pass_mark', 'asc');
                    break;
                case 'pass_mark_desc':
                    $query->orderBy('pass_mark', 'desc');
                    break;
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

    private function getPageListData(Request $request, $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $cloneQuery = deepClone($query);
        $total = DB::table(DB::raw("({$cloneQuery->toSql()}) as sub"))
            ->mergeBindings($cloneQuery->getQuery()) // bind parameters
            ->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $quizzes = $query
            ->with([
                'webinar',
                'quizQuestions',
                'quizResults',
            ])
            ->get();

        foreach ($quizzes as $quiz) {
            $countSuccess = $quiz->quizResults
                ->where('status', \App\Models\QuizzesResult::$passed)
                ->pluck('user_id')
                ->count();

            $rate = 0;
            if ($countSuccess) {
                $rate = round($countSuccess / $quiz->quizResults->count() * 100);
            }

            $quiz->userSuccessRate = $rate;
        }

        if ($request->ajax()) {
            return $this->getPageAjaxResponse($request, $quizzes, $total, $count);
        }

        return [
            'quizzes' => $quizzes,
            'pagination' => $this->makePagination($request, $quizzes, $total, $count, true),
        ];
    }

    private function getPageAjaxResponse(Request $request, $quizzes, $total, $count)
    {
        $html = "";

        foreach ($quizzes as $quiz) {
            $html .= (string)view()->make('design_1.panel.quizzes.lists.table_items', ['quiz' => $quiz]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $quizzes, $total, $count, true)
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize("panel_quizzes_create");

        $user = auth()->user();
        $webinars = Webinar::where(function ($query) use ($user) {
            $query->where('teacher_id', $user->id)
                ->orWhere('creator_id', $user->id)
                ->orWhereHas('webinarPartnerTeacher', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                });
        })->get();

        $locale = $request->get('locale', app()->getLocale());

        $data = [
            'pageTitle' => trans('quiz.new_quiz_page_title'),
            'webinars' => $webinars,
            'userLanguages' => getUserLanguagesLists(),
            'locale' => mb_strtolower($locale),
            'defaultLocale' => getDefaultLocale(),
        ];

        return view('design_1.panel.quizzes.create.index', $data);
    }

    public function store(Request $request)
    {
        $this->authorize("panel_quizzes_create");

        $data = $request->get('ajax')['new'];
        $locale = $request->get('locale', getDefaultLocale());

        $validate = Validator::make($data, [
            'title' => 'required|max:255',
            'webinar_id' => 'required|exists:webinars,id',
            'chapter_id' => 'required|exists:webinar_chapters,id',
            'pass_mark' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validate->errors()
            ], 422);
        }

        $user = auth()->user();

        $webinar = null;
        $chapter = null;

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::query()->where('id', $data['webinar_id'])
                ->where(function ($query) use ($user) {
                    $query->where('teacher_id', $user->id)
                        ->orWhere('creator_id', $user->id)
                        ->orWhereHas('webinarPartnerTeacher', function ($query) use ($user) {
                            $query->where('teacher_id', $user->id);
                        });
                })->first();

            if (!empty($webinar) and !empty($data['chapter_id'])) {
                $chapter = WebinarChapter::where('id', $data['chapter_id'])
                    ->where('webinar_id', $webinar->id)
                    ->first();
            }
        }

        $quiz = Quiz::create([
            'webinar_id' => !empty($webinar) ? $webinar->id : null,
            'chapter_id' => !empty($chapter) ? $chapter->id : null,
            'creator_id' => $user->id,
            'attempt' => $data['attempt'] ?? null,
            'pass_mark' => $data['pass_mark'],
            'time' => $data['time'] ?? null,
            'status' => (!empty($data['status']) and $data['status'] == 'on') ? Quiz::ACTIVE : Quiz::INACTIVE,
            'certificate' => (!empty($data['certificate']) and $data['certificate'] == 'on'),
            'display_questions_randomly' => (!empty($data['display_questions_randomly']) and $data['display_questions_randomly'] == 'on'),
            'expiry_days' => (!empty($data['expiry_days']) and $data['expiry_days'] > 0) ? $data['expiry_days'] : null,
            'created_at' => time(),
        ]);

        if (!empty($quiz)) {
            QuizTranslation::updateOrCreate([
                'quiz_id' => $quiz->id,
                'locale' => mb_strtolower($locale),
            ], [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            if (!empty($quiz->chapter_id)) {
                WebinarChapterItem::makeItem($quiz->creator_id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
            }

            $this->handleIcon($request, $quiz);
        }

        // Send Notification To All Students
        if (!empty($webinar)) {
            $webinar->sendNotificationToAllStudentsForNewQuizPublished($quiz);
        }

        if (!empty($webinar)) {
            unset($webinar->title, $webinar->locale);
            $webinar->update([
                'updated_at' => time()
            ]);
        }

        if ($request->ajax()) {

            $redirectUrl = '';

            if (empty($data['is_webinar_page'])) {
                $redirectUrl = '/panel/quizzes/' . $quiz->id . '/edit';
            }

            return response()->json([
                'code' => 200,
                'redirect_to' => $redirectUrl
            ]);
        } else {
            return redirect()->route('panel_edit_quiz', ['id' => $quiz->id]);
        }
    }

    public function edit(Request $request, $id)
    {
        $this->authorize("panel_quizzes_create");

        $user = auth()->user();
        $webinars = Webinar::where(function ($query) use ($user) {
            $query->where('teacher_id', $user->id)
                ->orWhere('creator_id', $user->id)
                ->orWhereHas('webinarPartnerTeacher', function ($query) use ($user) {
                    $query->where('teacher_id', $user->id);
                });
        })->get();

        $webinarIds = $webinars->pluck('id')->toArray();

        $quiz = Quiz::where('id', $id)
            ->where('creator_id', $user->id)
            ->where(function ($query) use ($user, $webinarIds) {
                $query->where('creator_id', $user->id);
                $query->orWhereIn('webinar_id', $webinarIds);
            })
            ->with([
                'quizQuestions' => function ($query) {
                    $query->orderBy('order', 'asc');
                    $query->with('quizzesQuestionsAnswers');
                },
            ])->first();

        if (!empty($quiz)) {
            $chapters = collect();

            if (!empty($quiz->webinar)) {
                $chapters = $quiz->webinar->chapters;
            }

            $locale = $request->get('locale', app()->getLocale());

            $data = [
                'pageTitle' => trans('public.edit') . ' ' . $quiz->title,
                'webinars' => $webinars,
                'quiz' => $quiz,
                'quizQuestions' => $quiz->quizQuestions,
                'chapters' => $chapters,
                'userLanguages' => getUserLanguagesLists(),
                'locale' => mb_strtolower($locale),
                'defaultLocale' => getDefaultLocale(),
            ];

            return view('design_1.panel.quizzes.create.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->authorize("panel_quizzes_create");

        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $webinar = null;
        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::where('id', $data['webinar_id'])
                ->where(function ($query) use ($user) {
                    $query->where('teacher_id', $user->id)
                        ->orWhere('creator_id', $user->id)
                        ->orWhereHas('webinarPartnerTeacher', function ($query) use ($user) {
                            $query->where('teacher_id', $user->id);
                        });
                })->first();
        }


        $quiz = Quiz::query()->where('id', $id)
            ->where(function ($query) use ($user, $webinar) {
                $query->where('creator_id', $user->id);

                if (!empty($webinar)) {
                    $query->orWhere('webinar_id', $webinar->id);
                }
            })
            ->first();

        if (!empty($quiz)) {
            $quizQuestionsCount = $quiz->quizQuestions->count();

            $locale = $request->get('locale', getDefaultLocale());

            $rules = [
                'title' => 'required|max:255',
                'webinar_id' => 'required|exists:webinars,id',
                'chapter_id' => 'required|exists:webinar_chapters,id',
                'pass_mark' => 'required',
                'display_number_of_questions' => 'required_if:display_limited_questions,on|nullable|between:1,' . $quizQuestionsCount
            ];

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validate->errors()
                ], 422);
            }


            if (!empty($webinar) and !empty($data['chapter_id'])) {
                $chapter = WebinarChapter::where('id', $data['chapter_id'])
                    ->where('webinar_id', $webinar->id)
                    ->first();
            }

            $quiz->update([
                'webinar_id' => !empty($webinar) ? $webinar->id : null,
                'chapter_id' => !empty($chapter) ? $chapter->id : null,
                'attempt' => $data['attempt'] ?? null,
                'pass_mark' => $data['pass_mark'],
                'time' => $data['time'] ?? null,
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? Quiz::ACTIVE : Quiz::INACTIVE,
                'certificate' => (!empty($data['certificate']) and $data['certificate'] == 'on'),
                'display_limited_questions' => (!empty($data['display_limited_questions']) and $data['display_limited_questions'] == 'on'),
                'display_number_of_questions' => (!empty($data['display_limited_questions']) and $data['display_limited_questions'] == 'on' and !empty($data['display_number_of_questions'])) ? $data['display_number_of_questions'] : null,
                'display_questions_randomly' => (!empty($data['display_questions_randomly']) and $data['display_questions_randomly'] == 'on'),
                'expiry_days' => (!empty($data['expiry_days']) and $data['expiry_days'] > 0) ? $data['expiry_days'] : null,
                'updated_at' => time(),
            ]);


            $checkChapterItem = WebinarChapterItem::query()
                ->where('item_id', $quiz->id)
                ->where('type', WebinarChapterItem::$chapterQuiz)
                ->first();

            if (!empty($quiz->chapter_id)) {
                if (empty($checkChapterItem)) {
                    WebinarChapterItem::makeItem($quiz->creator_id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
                } elseif ($checkChapterItem->chapter_id != $quiz->chapter_id) {
                    $checkChapterItem->delete(); // remove quiz from old chapter and assign it to new chapter

                    WebinarChapterItem::makeItem($quiz->creator_id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
                }
            } else if (!empty($checkChapterItem)) {
                $checkChapterItem->delete();
            }

            QuizTranslation::updateOrCreate([
                'quiz_id' => $quiz->id,
                'locale' => mb_strtolower($locale),
            ], [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            $this->handleIcon($request, $quiz);

            if (!empty($webinar)) {
                unset($webinar->title, $webinar->locale);
                $webinar->update([
                    'updated_at' => time()
                ]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'code' => 200
                ]);
            } else {
                return redirect('panel/quizzes');
            }
        }

        abort(404);
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize("panel_quizzes_delete");

        $user = auth()->user();
        $quiz = Quiz::where('id', $id)
            ->first();

        if (!empty($quiz)) {

            $webinar = null;
            if (!empty($quiz->webinar_id)) {
                $webinar = Webinar::query()->find($quiz->webinar_id);
            }

            if ($quiz->creator_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {
                if ($quiz->delete()) {
                    $checkChapterItem = WebinarChapterItem::where('user_id', $user->id)
                        ->where('item_id', $id)
                        ->where('type', WebinarChapterItem::$chapterQuiz)
                        ->first();

                    if (!empty($checkChapterItem)) {
                        $checkChapterItem->delete();
                    }

                    return response()->json([
                        'code' => 200
                    ], 200);
                }
            }
        }

        return response()->json([], 422);
    }

    private function handleIcon(Request $request, $quiz)
    {
        $iconPath = $quiz->icon ?? null;

        if (!empty($request->file('icon'))) {
            if (!empty($iconPath)) {
                $this->removeFile($iconPath);
            }

            $iconPath = $this->uploadFile($request->file('icon'), "quizzes/{$quiz->id}", 'icon', $quiz->creator_id);
        }

        $quiz->update([
            'icon' => $iconPath
        ]);

        return $quiz;
    }

    public function overview(Request $request, $id)
    {
        $user = auth()->user();
        $quiz = Quiz::where('id', $id)
            ->where('status', 'active')
            ->with([
                'creator',
                'webinar'
            ])
            ->first();

        if (!empty($quiz)) {
            if (!empty($quiz->webinar_id)) {
                $webinar = $quiz->webinar;

                $checkUserHasBought = $webinar->checkUserHasBought($user);

                if (!$checkUserHasBought) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('cart.you_not_purchased_this_course'),
                        'status' => 'error'
                    ];
                    return redirect()->back()->with(['toast' => $toastData]);
                }
            }

            $data = [
                'pageTitle' => trans('update.quiz_overview'),
                'quiz' => $quiz,
                'webinar' => $webinar,
            ];
            $data = array_merge($data, $this->getQuizStats($quiz, $user));

            return view('design_1.panel.quizzes.holding.overview', $data);
        }

        abort(404);
    }

    private function getQuizStats($quiz, $user, $isStartPage = false)
    {
        $userQuizDone = QuizzesResult::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->get();

        $quizQuestionsQuery = QuizzesQuestion::query()
            ->where('quiz_id', $quiz->id)
            ->with('quizzesQuestionsAnswers');

        if ($quiz->display_questions_randomly) {
            $quizQuestionsQuery->inRandomOrder();
        } else {
            $quizQuestionsQuery->orderBy('order', 'asc');
        }

        if (($quiz->display_limited_questions and !empty($quiz->display_number_of_questions))) {
            $totalQuestionsCount = $quiz->display_number_of_questions;

            $quizQuestions = $quizQuestionsQuery->take($totalQuestionsCount)->get();
        } else {
            $quizQuestions = $quizQuestionsQuery->get();
            $totalQuestionsCount = $quizQuestions->count();
        }

        $attemptCount = $userQuizDone->count();

        if ($isStartPage) {
            $attemptCount = $attemptCount + 1;
        }

        return [
            'attemptCount' => $attemptCount,
            'totalQuestionsCount' => $totalQuestionsCount,
            'quizQuestions' => $quizQuestions,
        ];
    }

    public function start(Request $request, $id)
    {
        $quiz = Quiz::where('id', $id)->first();

        $user = auth()->user();

        if ($quiz) {

            if (!empty($quiz->webinar_id)) {
                $webinar = $quiz->webinar;

                $checkUserHasBought = $webinar->checkUserHasBought($user);

                if (!$checkUserHasBought) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('cart.you_not_purchased_this_course'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                if (!empty($quiz->expiry_days)) {
                    $hasAccess = $quiz->checkCanAccessByExpireDays($user);

                    if (!$hasAccess) {
                        $toastData = [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('update.your_access_to_this_quiz_has_been_expired'),
                            'status' => 'error'
                        ];
                        return back()->with(['toast' => $toastData]);
                    }
                }
            }

            $checkUserCanStartByAttempt = $quiz->checkUserCanStartByAttempt($user);

            if ($checkUserCanStartByAttempt) {
                $newQuizStart = QuizzesResult::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $user->id,
                    'results' => '',
                    'user_grade' => 0,
                    'status' => 'waiting',
                    'created_at' => time()
                ]);

                $data = [
                    'pageTitle' => trans('quiz.quiz_start'),
                    'quiz' => $quiz,
                    'webinar' => $quiz->webinar,
                    'newQuizStart' => $newQuizStart,
                ];

                $data = array_merge($data, $this->getQuizStats($quiz, $user, true));

                return view('design_1.panel.quizzes.holding.start.index', $data);
            } else {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('quiz.cant_start_quiz'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }
        }
        abort(404);
    }

    public function quizzesStoreResult(Request $request, $id)
    {
        $user = auth()->user();
        $quiz = Quiz::where('id', $id)->first();

        if ($quiz) {
            $results = $request->get('question');
            $quizResultId = $request->get('quiz_result_id');

            if (!empty($quizResultId)) {

                $quizResult = QuizzesResult::where('id', $quizResultId)
                    ->where('user_id', $user->id)
                    ->first();

                if (!empty($quizResult)) {

                    $passMark = $quiz->pass_mark;
                    $totalMark = 0;
                    $status = '';

                    if (!empty($results)) {
                        foreach ($results as $questionId => $result) {

                            if (!is_array($result)) {
                                unset($results[$questionId]);

                            } else {

                                $question = QuizzesQuestion::where('id', $questionId)
                                    ->where('quiz_id', $quiz->id)
                                    ->first();

                                if ($question and !empty($result['answer'])) {
                                    $answer = QuizzesQuestionsAnswer::where('id', $result['answer'])
                                        ->where('question_id', $question->id)
                                        ->where('creator_id', $quiz->creator_id)
                                        ->first();

                                    $results[$questionId]['status'] = false;
                                    $results[$questionId]['grade'] = $question->grade;
                                    $results[$questionId]['negative_grade'] = $question->negative_grade ?? null;

                                    if ($answer && $answer->correct) {
                                        $results[$questionId]['status'] = true;
                                        $totalMark += (int) $question->grade;
                                    } else {
                                        // Apply negative marking only for multiple-choice if defined
                                        if ($question->type === 'multiple' && !empty($question->negative_grade)) {
                                            $totalMark -= (int) $question->negative_grade;
                                        }
                                    }

                                    if ($question->type == 'descriptive') {
                                        $status = 'waiting';
                                    }
                                }
                            }
                        }
                    }

                    if (empty($status)) {
                        $status = ($totalMark >= $passMark) ? QuizzesResult::$passed : QuizzesResult::$failed;
                    }

                    $results["attempt_number"] = $request->get('attempt_number');

                    $quizResult->update([
                        'results' => json_encode($results),
                        'user_grade' => $totalMark,
                        'status' => $status,
                        'created_at' => time()
                    ]);

                    if ($quizResult->status == QuizzesResult::$waiting) {
                        $notifyOptions = [
                            '[c.title]' => $quiz->webinar ? $quiz->webinar->title : '-',
                            '[student.name]' => $user->full_name,
                            '[q.title]' => $quiz->title,
                        ];
                        sendNotification('waiting_quiz', $notifyOptions, $quiz->creator_id);
                    }

                    if ($quizResult->status == QuizzesResult::$passed) {
                        $passTheQuizReward = RewardAccounting::calculateScore(Reward::PASS_THE_QUIZ);
                        RewardAccounting::makeRewardAccounting($quizResult->user_id, $passTheQuizReward, Reward::PASS_THE_QUIZ, $quiz->id, true);

                        if ($quiz->certificate) {
                            $certificateReward = RewardAccounting::calculateScore(Reward::CERTIFICATE);
                            RewardAccounting::makeRewardAccounting($quizResult->user_id, $certificateReward, Reward::CERTIFICATE, $quiz->id, true);
                        }
                    }

                    return redirect()->route('quiz_status', ['quizResultId' => $quizResult]);
                }
            }
        }
        abort(404);
    }

    public function status($quizResultId)
    {
        $user = auth()->user();

        $quizResult = QuizzesResult::where('id', $quizResultId)
            ->where('user_id', $user->id)
            ->with(['quiz' => function ($query) {
                $query->with(['quizQuestions']);
            }])
            ->first();

        if ($quizResult) {
            $quiz = $quizResult->quiz;
            $attemptCount = $quiz->attempt;

            $userQuizDone = QuizzesResult::where('quiz_id', $quiz->id)
                ->where('user_id', $user->id)
                ->count();

            $canTryAgain = false;
            $remainingTryAgain = 0;

            if (empty($attemptCount) or $userQuizDone < $attemptCount) {
                $canTryAgain = true;

                if (empty($attemptCount)) {
                    $remainingTryAgain = 'unlimited';
                } else {
                    $remainingTryAgain = $attemptCount - $userQuizDone;
                }
            }

            $quizQuestions = $quizResult->getQuestions();
            $totalQuestionsCount = $quizQuestions->count();

            $data = [
                'pageTitle' => trans('quiz.quiz_status'),
                'quizResult' => $quizResult,
                'quiz' => $quiz,
                'webinar' => $quiz->webinar,
                'quizQuestions' => $quizQuestions,
                'attemptCount' => $userQuizDone,
                'canTryAgain' => $canTryAgain,
                'totalQuestionsCount' => $totalQuestionsCount,
                'remainingTryAgain' => $remainingTryAgain,
            ];

            return view('design_1.panel.quizzes.holding.status.index', $data);
        }
        abort(404);
    }

    public function orderItems(Request $request, $quizId)
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

        $quiz = Quiz::query()->where('id', $quizId)
            ->where('creator_id', $user->id)
            ->first();

        if (!empty($quiz)) {
            $tableName = $data['table'];
            $itemIds = explode(',', $data['items']);

            if (!is_array($itemIds) and !empty($itemIds)) {
                $itemIds = [$itemIds];
            }

            if (!empty($itemIds) and is_array($itemIds) and count($itemIds)) {
                switch ($tableName) {
                    case 'quizzes_questions':
                        foreach ($itemIds as $order => $id) {
                            QuizzesQuestion::where('id', $id)
                                ->where('quiz_id', $quiz->id)
                                ->update(['order' => ($order + 1)]);
                        }
                        break;
                }
            }
        }

        return response()->json([
            'title' => trans('public.request_success'),
            'msg' => trans('update.items_sorted_successful')
        ]);
    }

}
