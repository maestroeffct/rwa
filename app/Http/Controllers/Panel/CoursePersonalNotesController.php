<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\CoursePersonalNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CoursePersonalNotesController extends Controller
{
    public function index(Request $request)
    {
        if (!empty(getFeaturesSettings('course_notes_status'))) {
            $user = auth()->user();

            $query = CoursePersonalNote::query()->where('user_id', $user->id);

            $getListData = $this->getListsData($request, $query);

            if ($request->ajax()) {
                return $getListData;
            }

            $data = [
                'pageTitle' => trans('update.course_notes'),
            ];
            $data = array_merge($data, $getListData);

            return view('design_1.panel.webinars.personal_notes.index', $data);
        }

        abort(404);
    }


    private function getListsData(Request $request, Builder $query)
    {
        $page = $request->get('page') ?? 1;
        $count = $this->perPage;

        $total = $query->count();

        $query->limit($count);
        $query->offset(($page - 1) * $count);

        $personalNotes = $query
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return $this->getAjaxResponse($request, $personalNotes, $total, $count);
        }

        return [
            'personalNotes' => $personalNotes,
            'pagination' => $this->makePagination($request, $personalNotes, $total, $count, true),
        ];
    }

    private function getAjaxResponse(Request $request, $personalNotes, $total, $count)
    {
        $html = "";

        foreach ($personalNotes as $personalNoteRow) {
            $html .= (string)view()->make('design_1.panel.webinars.personal_notes.table_items', ['personalNote' => $personalNoteRow]);
        }

        return response()->json([
            'data' => $html,
            'pagination' => $this->makePagination($request, $personalNotes, $total, $count, true)
        ]);
    }

    public function delete($id)
    {
        if (!empty(getFeaturesSettings('course_notes_status'))) {
            $user = auth()->user();

            $personalNote = CoursePersonalNote::query()->where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!empty($personalNote)) {
                $personalNote->delete();

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(404);
    }
}
