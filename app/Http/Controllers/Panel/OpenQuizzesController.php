<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OpenQuizzesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $webinarIds = $user->getPurchasedCoursesIds();

        $query = Quiz::query()->whereIn('webinar_id', $webinarIds)
            ->where('status', 'active')
            ->whereDoesntHave('quizResults', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        $query = $this->handleFilters($request, $query);
        $getListData = $this->getListsData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }

        $allQuizzesLists = Quiz::query()->select('id', 'webinar_id')
            ->whereIn('webinar_id', $webinarIds)
            ->get();

        $allInstructors = User::query()->select('id', 'full_name')
            ->whereHas('webinars', function (Builder $query) use ($webinarIds) {
                $query->whereIn('id', $webinarIds);
            })->get();

        $data = [
            'pageTitle' => trans('quiz.my_results'),
            'allQuizzesLists' => $allQuizzesLists,
            'allInstructors' => $allInstructors,
            ...$getListData,
        ];

        return view('design_1.panel.quizzes.opens.index', $data);
    }

    private function handleFilters(Request $request, Builder $query): Builder
    {
        $quiz_id = $request->get('quiz_id', null);
        $instructor_id = $request->get('instructor_id', null);
        $sort = $request->get('sort', null);

        if (!empty($quiz_id) and $quiz_id != 'all') {
            $query->where('id', $quiz_id);
        }

        if ($instructor_id) {
            $query->where('creator_id', $instructor_id);
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

        $quizzes = $query->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $quizzes, $total, $count);
        }

        return [
            'quizzes' => $quizzes,
            'pagination' => $this->makePagination($request, $quizzes, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $quizzes, $total, $count)
    {
        $html = "";

        foreach ($quizzes as $quizRow) {
            $html .= (string)view()->make('design_1.panel.quizzes.opens.table_items', ['quiz' => $quizRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $quizzes, $total, $count, true)
        ]);
    }

}
