<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\Webinar;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QuizMyResultsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = QuizzesResult::query()->where('user_id', $user->id);

        $copyQuery = deepClone($query);
        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }

        $webinarIds = $user->getPurchasedCoursesIds();

        $topStats = $this->getTopStats($copyQuery, $user);
        $pendingQuizzes = $this->getPendingQuizzes($user, $webinarIds);

        $allQuizzesLists = Quiz::query()->select('id', 'webinar_id')
            ->whereIn('webinar_id', $webinarIds)
            ->get();

        $allCoursesLists = Webinar::query()->select('id')
            ->whereIn('id', $webinarIds)
            ->get();

        $allInstructors = User::query()->select('id', 'full_name')
            ->whereHas('webinars', function (Builder $query) use ($webinarIds) {
                $query->whereIn('id', $webinarIds);
            })->get();

        $data = [
            'pageTitle' => trans('quiz.my_results'),
            'pendingQuizzes' => $pendingQuizzes,
            'allQuizzesLists' => $allQuizzesLists,
            'allCoursesLists' => $allCoursesLists,
            'allInstructors' => $allInstructors,
            ...$topStats,
            ...$getListData,
        ];

        return view('design_1.panel.quizzes.my_results.index', $data);
    }

    private function getPendingQuizzes($user, $webinarIds)
    {
        $pendingQuizzes = Quiz::query()->whereIn('webinar_id', $webinarIds)
            ->where('status', 'active')
            ->whereDoesntHave('quizResults', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->orderBy('created_at', 'desc')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        foreach ($pendingQuizzes as $pendingQuiz) {
            $pendingQuiz->expiry_timestamp = null;

            if (!empty($pendingQuiz->expiry_days)) {
                $webinar = $pendingQuiz->webinar;

                if (!empty($webinar)) {
                    $sale = $webinar->getSaleItem($user);

                    if (!empty($sale)) {
                        $purchaseDate = $sale->created_at;
                        $gift = $sale->gift;

                        if (!empty($gift) and !empty($gift->date)) {
                            $purchaseDate = $gift->date;
                        }

                        $pendingQuiz->expiry_timestamp = strtotime("+{$pendingQuiz->expiry_days} days", $purchaseDate);
                    }
                }
            }
        }

        return $pendingQuizzes;
    }

    private function getTopStats(Builder $query, $user)
    {
        $quizResultsCount = deepClone($query)->count();
        $passedCount = deepClone($query)->where('status', \App\Models\QuizzesResult::$passed)->count();
        $failedCount = deepClone($query)->where('status', \App\Models\QuizzesResult::$failed)->count();
        $waitingCount = deepClone($query)->where('status', \App\Models\QuizzesResult::$waiting)->count();

        return [
            'quizzesResultsCount' => $quizResultsCount,
            'passedCount' => $passedCount,
            'failedCount' => $failedCount,
            'waitingCount' => $waitingCount,
        ];
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $quiz_id = $request->get('quiz_id', null);
        $course_id = $request->get('course_id', null);
        $instructor_id = $request->get('instructor_id', null);
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

        if (!empty($user_id) and $user_id != 'all') {
            $query->where('user_id', $user_id);
        }

        if ($instructor_id) {
            $query->where('creator_id', $instructor_id);
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
                        'creator',
                        'webinar'
                    ]);
                }
            ])
            ->get();


        foreach ($quizzesResults->groupBy('quiz_id') as $quiz_id => $quizResult) {
            $canTryAgainQuiz = false;

            $result = $quizResult->first();
            $quiz = $result->quiz;

            if (!isset($quiz->attempt) or (count($quizResult) < $quiz->attempt and $result->status !== 'passed')) {
                $canTryAgainQuiz = true;
            }

            foreach ($quizResult as $item) {
                $item->can_try = $canTryAgainQuiz;
                if ($canTryAgainQuiz and isset($quiz->attempt)) {
                    $item->count_can_try = $quiz->attempt - count($quizResult);
                }
            }
        }

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
            $html .= (string)view()->make('design_1.panel.quizzes.my_results.table_items', ['quizResult' => $quizResult]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $quizzesResults, $total, $count, true)
        ]);
    }

}
