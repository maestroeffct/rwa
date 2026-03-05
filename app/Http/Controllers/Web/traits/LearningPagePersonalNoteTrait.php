<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\CoursePersonalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait LearningPagePersonalNoteTrait
{

    public function getPersonalNoteForm(Request $request, $courseSlug)
    {
        $user = auth()->user();
        $course = $this->getCourse($courseSlug);

        if ($course == 'not_access') {
            abort(404);
        }

        $itemId = $request->get('item_id');
        $itemType = $request->get('item_type');

        $personalNote = CoursePersonalNote::query()->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('targetable_id', $itemId)
            ->where('targetable_type', $itemType)
            ->first();

        $data = [
            'personalNote' => $personalNote,
            'course' => $course,
            'itemId' => $itemId,
            'itemType' => $itemType,
        ];
        $html = (string)view()->make('design_1.web.courses.learning_page.includes.modals.personal_notes.form_modal', $data);

        return response()->json([
            'code' => 200,
            'html' => $html,
        ]);
    }

    public function getPersonalNoteDetails(Request $request, $courseSlug)
    {
        $user = auth()->user();
        $course = $this->getCourse($courseSlug);

        if ($course == 'not_access') {
            abort(404);
        }

        $itemId = $request->get('item_id');
        $itemType = $request->get('item_type');

        $personalNote = CoursePersonalNote::query()->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('targetable_id', $itemId)
            ->where('targetable_type', $itemType)
            ->first();

        if (!empty($personalNote)) {
            $data = [
                'personalNote' => $personalNote,
                'course' => $course,
                'itemId' => $itemId,
                'itemType' => $itemType,
            ];
            $html = (string)view()->make('design_1.web.courses.learning_page.includes.modals.personal_notes.details_modal', $data);

            return response()->json([
                'code' => 200,
                'html' => $html,
                'submitted_on' => dateTimeFormat($personalNote->created_at, 'j M Y H:i'),
                'note_id' => $personalNote->id,
                'item_id' => $itemId,
                'item_type' => $itemType,
            ]);
        }

        return response()->json([], 404);
    }

    public function storePersonalNote(Request $request, $courseSlug)
    {
        $user = auth()->user();
        $course = $this->getCourse($courseSlug);

        if ($course == 'not_access') {
            return response()->json([], 403);
        }

        $data = $request->all();
        $validator = Validator::make($data, [
            'details' => 'required|string',
            'item_id' => 'required',
            'item_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $itemId = $data['item_id'];
        $itemType = $data['item_type'];


        $personalNote = CoursePersonalNote::query()->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('targetable_id', $itemId)
            ->where('targetable_type', $itemType)
            ->first();

        $attachmentPath = null;
        if (!empty($personalNote)) {
            $attachmentPath = $personalNote->attachment;
        }

        $attachmentFile = $request->file('attachment');
        if (!empty($attachmentFile)) {
            $destination = "personal_notes";
            $filename = "note_{$itemId}_" . time();

            $attachmentPath = $this->uploadFile($attachmentFile, $destination, $filename, $user->id);
        }

        CoursePersonalNote::query()->updateOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'targetable_id' => $itemId,
            'targetable_type' => $itemType,
        ], [
            'note' => $data['details'] ?? null,
            'attachment' => $attachmentPath,
            'created_at' => time()
        ]);

        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('update.personal_note_stored_successfully'),
            'dont_reload' => false,
        ]);
    }
}
