<?php

namespace App\Http\Controllers\Admin;

use App\Exports\QuizResultsExport;
use App\Http\Controllers\Controller;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesResult;
use App\Models\Reward;
use App\Models\RewardAccounting;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class QuizResultsController extends Controller
{
    public function index($quizId)
    {
        $this->authorize('admin_quizzes_results');

        $quizzesResults = QuizzesResult::where('quiz_id', $quizId)
            ->with([
                'quiz' => function ($query) {
                    $query->with(['teacher']);
                },
                'user'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/quizResults.quiz_result_list_page_title'),
            'quizzesResults' => $quizzesResults,
            'quiz_id' => $quizId
        ];

        return view('admin.quizzes.results', $data);
    }

    public function review($quizId, $resultId)
    {
        $this->authorize("admin_quiz_result_review");

        $quizResult = QuizzesResult::where('id', $resultId)
            ->where('quiz_id', $quizId)
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
                'quizResult' => $quizResult,
                'newQuizStart' => $quizResult,
                'userAnswers' => json_decode($quizResult->results, true),
                'numberOfAttempt' => $numberOfAttempt,
                'questionsSumGrade' => $quizQuestions->sum('grade'),
                'quizQuestions' => $quizQuestions,
                'canEditResult' => true,
                'formActionUrl' => getAdminPanelUrl("/quizzes/{$quizId}/results/{$resultId}/update"),
            ];

            return view('design_1.panel.quizzes.holding.result.index', $data);
        }

        abort(404);
    }

    public function update(Request $request, $quizId, $resultId)
    {
        $this->authorize("admin_quiz_result_review");

        $quizResult = QuizzesResult::where('id', $resultId)
            ->where('quiz_id', $quizId)
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
            $quiz = $quizResult->quiz;
            $user = $quiz->creator;

            $reviews = $request->get('question', []);
            $quizResultId = $request->get('quiz_result_id');

            if (!empty($quizResultId)) {

                $oldResults = json_decode($quizResult->results, true);
                $totalMark = 0;
                $status = '';
                $user_grade = $quizResult->user_grade;

                if (!empty($oldResults) and count($oldResults) and !empty($reviews)) {
                    foreach ($oldResults as $question_id => $result) {
                        foreach ($reviews as $question_id2 => $review) {
                            if ($question_id2 == $question_id) {
                                $question = QuizzesQuestion::where('id', $question_id)
                                    ->where('quiz_id', $quiz->id)
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

                return redirect(getAdminPanelUrl("/quizzes/{$quizId}/results"));
            }
        }

        abort(404);
    }

    public function exportExcel($quizId)
    {
        $this->authorize('admin_quiz_result_export_excel');

        $quizzesResults = QuizzesResult::where('quiz_id', $quizId)
            ->with([
                'quiz' => function ($query) {
                    $query->with(['teacher']);
                },
                'user'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $export = new QuizResultsExport($quizzesResults);

        return Excel::download($export, 'quiz_result.xlsx');
    }

    public function delete($quizId, $result_id)
    {
        $this->authorize('admin_quizzes_results_delete');

        $quizzesResults = QuizzesResult::where('id', $result_id)->first();

        if (!empty($quizzesResults)) {
            $quizzesResults->delete();
        }

        return redirect()->back();
    }

}
