<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\Certificate\MakeCertificate;
use App\Models\Quiz;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesResult;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizResultsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->isUser()) {
            $query = QuizzesResult::query()
                ->whereHas('quiz', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                    $query->where('status', 'active');
                });

            $copyQuery = deepClone($query);
            $query = $this->handleFilters($request, $query);
            $getListData = $this->getListsData($request, $query, $user);

            if ($request->ajax()) {
                return $getListData;
            }

            $topStats = $this->getTopStats($copyQuery, $user);
            $pendingReviewQuizzesResults = $this->pendingReviewQuizzesResults($copyQuery, $user);

            $allQuizzesLists = Quiz::select('id', 'webinar_id')
                ->where('creator_id', $user->id)
                ->where('status', 'active')
                ->get();

            $webinarIds = $allQuizzesLists->pluck('webinar_id')->toArray();
            $allCoursesLists = Webinar::query()->select('id')
                ->whereIn('id', $webinarIds)
                ->get();

            $allStudentsIds = deepClone($copyQuery)->pluck('user_id')->toArray();
            $allStudents = User::query()->select('id', 'full_name')
                ->whereIn('id', $allStudentsIds)
                ->get();

            $data = [
                'pageTitle' => trans('update.students_quiz_results'),
                'pendingReviewQuizzesResults' => $pendingReviewQuizzesResults,
                'allQuizzesLists' => $allQuizzesLists,
                'allCoursesLists' => $allCoursesLists,
                'allStudents' => $allStudents,
                ...$topStats,
                ...$getListData,
            ];

            return view('design_1.panel.quizzes.results.index', $data);
        }

        abort(404);
    }

    private function pendingReviewQuizzesResults(Builder $quizzesResultQuery, $user)
    {
        $query = deepClone($quizzesResultQuery);
        $query->where('status', QuizzesResult::$waiting);
        $query->orderBy('created_at', 'desc');
        $query->limit(4);
        $query->with([
            'quiz' => function ($query) {
                $query->with([
                    'webinar',
                ]);
            },
            'user' => function ($query) {
                $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
            },
        ]);

        return $query->get();
    }

    private function getTopStats(Builder $query, $user)
    {
        $totalResultsCount = deepClone($query)->count();
        $totalPassedResults = deepClone($query)->where('status', QuizzesResult::$passed)->count();
        $totalFailedResults = deepClone($query)->where('status', QuizzesResult::$failed)->count();
        $totalPendingReviewResults = deepClone($query)->where('status', QuizzesResult::$waiting)->count();

        return [
            'totalResultsCount' => $totalResultsCount,
            'totalPassedResults' => $totalPassedResults,
            'totalFailedResults' => $totalFailedResults,
            'totalPendingReviewResults' => $totalPendingReviewResults,
        ];
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $quiz_id = $request->get('quiz_id', null);
        $course_id = $request->get('course_id', null);
        $student_id = $request->get('student_id', null);
        $status = $request->get('status', null);
        $sort = $request->get('sort', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($quiz_id) and $quiz_id != 'all') {
            $query->where('quiz_id', $quiz_id);
        }

        if (!empty($course_id) and $course_id != 'all') {
            $query->whereHas('quiz', function ($query) use ($course_id) {
                $query->where('webinar_id', $course_id);
            });
        }

        if (!empty($student_id) and $student_id != 'all') {
            $query->where('user_id', $student_id);
        }

        if ($status and $status != 'all') {
            $query->where('status', strtolower($status));
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'grade_asc':
                    $query->orderBy('user_grade', 'asc');
                    break;
                case 'grade_desc':
                    $query->orderBy('user_grade', 'desc');
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

    private function getListsData(Request $request, $query, $user)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $quizzesResults = $query
            ->with([
                'quiz' => function ($query) {
                    $query->with([
                        'quizQuestions',
                        'webinar',
                    ]);
                },
                'user' => function ($query) {
                    $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio');
                },
            ])
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $quizzesResults, $total, $count);
        }

        return [
            'quizzesResults' => $quizzesResults,
            'pagination' => $this->makePagination($request, $quizzesResults, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $quizzesResults, $total, $count)
    {
        $html = "";

        foreach ($quizzesResults as $quizResult) {
            $html .= (string)view()->make('design_1.panel.quizzes.results.item_table', ['quizResult' => $quizResult]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $quizzesResults, $total, $count, true)
        ]);
    }

    public function show($quizResultId)
    {
        $user = auth()->user();

        $quizzesIds = Quiz::query()
            ->where(function (Builder $query) use ($user) {
                $query->where('creator_id', $user->id);

                $query->orWhereHas('webinar', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('teacher_id', $user->id);
                });
            })
            ->pluck('id')
            ->toArray();

        $quizResult = QuizzesResult::where('id', $quizResultId)
            ->where(function ($query) use ($user, $quizzesIds) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('quiz_id', $quizzesIds);
            })
            ->with([
                'quiz' => function ($query) {
                    $query->with(['quizQuestions', 'webinar']);
                }
            ])->first();

        if (!empty($quizResult)) {
            $quiz = $quizResult->quiz;

            $numberOfAttempt = QuizzesResult::where('quiz_id', $quiz->id)
                ->where('user_id', $quizResult->user_id)
                ->count();

            $quizQuestions = $quizResult->getQuestions();


            $data = [
                'pageTitle' => trans('quiz.result'),
                'quiz' => $quiz,
                'webinar' => $quiz->webinar,
                'quizResult' => $quizResult,
                'userAnswers' => json_decode($quizResult->results, true),
                'numberOfAttempt' => $numberOfAttempt,
                'questionsSumGrade' => $quizQuestions->sum('grade'),
                'quizQuestions' => $quizQuestions,
            ];

            return view('design_1.panel.quizzes.holding.result.index', $data);
        }

        abort(404);
    }

    public function edit($quizResultId)
    {
        $user = auth()->user();

        $quizResult = QuizzesResult::query()->where('id', $quizResultId)
            ->whereHas('quiz', function ($query) use ($user) {
                $query->where(function (Builder $query) use ($user) {
                    $query->where('creator_id', $user->id);

                    $query->orWhereHas('webinar', function ($query) use ($user) {
                        $query->where('creator_id', $user->id);
                        $query->orWhere('teacher_id', $user->id);
                    });
                });
            })
            ->with([
                'quiz' => function ($query) {
                    $query->with([
                        'quizQuestions' => function ($query) {
                            $query->orderBy('type', 'desc');
                        },
                        'webinar'
                    ]);
                }
            ])->first();

        if (!empty($quizResult)) {
            $numberOfAttempt = QuizzesResult::where('quiz_id', $quizResult->quiz->id)
                ->where('user_id', $quizResult->user_id)
                ->count();

            $quiz = $quizResult->quiz;
            $quizQuestions = $quizResult->getQuestions();

            $data = [
                'pageTitle' => trans('quiz.result'),
                'teacherReviews' => true,
                'quiz' => $quiz,
                'webinar' => $quiz->webinar,
                'quizResult' => $quizResult,
                'newQuizStart' => $quizResult,
                'userAnswers' => json_decode($quizResult->results, true),
                'numberOfAttempt' => $numberOfAttempt,
                'questionsSumGrade' => $quizQuestions->sum('grade'),
                'quizQuestions' => $quizQuestions
            ];

            return view('design_1.panel.quizzes.holding.result.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $quizResultId)
    {
        $user = auth()->user();
        $quizResult = QuizzesResult::query()->where('id', $quizResultId)
            ->with([
                'quiz'
            ])
            ->first();

        if (!empty($quizResult)) {
            $quiz = $quizResult->quiz;

            if (!empty($quiz) and $quiz->canAccessToEdit($user)) {
                $reviews = $request->get('question');

                $oldResults = json_decode($quizResult->results, true);
                $totalMark = 0;
                $status = '';
                $user_grade = $quizResult->user_grade;

                if (!empty($oldResults) and count($oldResults)) {
                    foreach ($oldResults as $question_id => $result) {
                        foreach ($reviews as $question_id2 => $review) {
                            if ($question_id2 == $question_id) {
                                $question = QuizzesQuestion::where('id', $question_id)
                                    ->where('creator_id', $user->id)
                                    ->first();

                                if ($question->type == 'descriptive') {
                                    if (!empty($result['status']) and $result['status']) {
                                        $user_grade = $user_grade - (isset($result['grade']) ? (int)$result['grade'] : 0);
                                        $user_grade = $user_grade + (isset($review['grade']) ? (int)$review['grade'] : (int)$question->grade);
                                    } else if (isset($result['status']) and !$result['status']) {
                                        $user_grade = $user_grade + (isset($review['grade']) ? (int)$review['grade'] : (int)$question->grade);
                                        $oldResults[$question_id]['grade'] = isset($review['grade']) ? $review['grade'] : $question->grade;
                                    }

                                    $oldResults[$question_id]['status'] = true;
                                }
                            }
                        }
                    }
                } elseif (!empty($reviews) and count($reviews)) {
                    foreach ($reviews as $questionId => $review) {

                        if (!is_array($review)) {
                            unset($reviews[$questionId]);
                        } else {
                            $question = QuizzesQuestion::where('id', $questionId)
                                ->where('quiz_id', $quiz->id)
                                ->first();

                            if ($question and $question->type == 'descriptive') {
                                $user_grade += (isset($review['grade']) ? (int)$review['grade'] : 0);
                            }
                        }
                    }

                    $oldResults = $reviews;
                }

                $quizResult->user_grade = $user_grade;
                $passMark = $quiz->pass_mark;

                if ($quizResult->user_grade >= $passMark) {
                    $quizResult->status = QuizzesResult::$passed;
                } else {
                    $quizResult->status = QuizzesResult::$failed;
                }

                $quizResult->results = json_encode($oldResults);

                $quizResult->save();

                $notifyOptions = [
                    '[c.title]' => $quiz->webinar ? $quiz->webinar->title : '-',
                    '[q.title]' => $quiz->title,
                    '[q.result]' => $quizResult->status,
                ];
                sendNotification('waiting_quiz_result', $notifyOptions, $quizResult->user_id);

                if ($quizResult->status == QuizzesResult::$passed) {
                    $passTheQuizReward = RewardAccounting::calculateScore(Reward::PASS_THE_QUIZ);
                    RewardAccounting::makeRewardAccounting($quizResult->user_id, $passTheQuizReward, Reward::PASS_THE_QUIZ, $quizResult->id, true);

                    if ($quiz->certificate) {
                        $certificateReward = RewardAccounting::calculateScore(Reward::CERTIFICATE);
                        RewardAccounting::makeRewardAccounting($quizResult->user_id, $certificateReward, Reward::CERTIFICATE, $quiz->id, true);
                    }
                }

                return redirect('panel/quizzes/results');
            }
        }

        abort(404);
    }

    public function delete($quizResultId)
    {
        $user = auth()->user();

        $quizzesIds = Quiz::query()
            ->where(function (Builder $query) use ($user) {
                $query->where('creator_id', $user->id);

                $query->orWhereHas('webinar', function ($query) use ($user) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('teacher_id', $user->id);
                });
            })
            ->pluck('id')
            ->toArray();

        $quizResult = QuizzesResult::query()->where('id', $quizResultId)
            ->whereIn('quiz_id', $quizzesIds)
            ->first();

        if (!empty($quizResult)) {
            $quizResult->delete();

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }

    public function makeCertificate($quizResultId)
    {
        $user = auth()->user();

        $makeCertificate = new MakeCertificate();

        $quizResult = QuizzesResult::where('id', $quizResultId)
            ->where('user_id', $user->id)
            ->where('status', QuizzesResult::$passed)
            ->with(['quiz' => function ($query) {
                $query->with(['webinar']);
            }])
            ->first();

        if (!empty($quizResult)) {
            return $makeCertificate->makeQuizCertificate($quizResult);
        }

        abort(404);
    }

}
