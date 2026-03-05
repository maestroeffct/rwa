<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\CoursePersonalNote;
use Illuminate\Http\Request;

class CoursePersonalNotesController extends Controller
{
    public function show(Request $request)
    {
        if (!empty(getFeaturesSettings('course_notes_status'))) {
            $data = $request->all();
            validateParam($data, [
                'item' => 'required',
                'type' => 'required|in:session,file,quiz,text_lesson,assignment',
            ]);

            $user = apiAuth();
            $type = $this->getMorphByItemType($data['type']);
            $itemId = $data['item'];

            $personalNote = CoursePersonalNote::query()
                ->where("user_id", $user->id)
                ->where('targetable_id', $itemId)
                ->where('targetable_type', $type)
                ->first();

            if (!empty($personalNote)) {
                if (!empty($personalNote->attachment)) {
                    $personalNote->attachment = url($personalNote->attachment);
                }

                return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $personalNote);
            }
        }

        return apiResponse2(0, 'not_found', trans('api.not_found'));
    }

    public function destroy($id)
    {
        if (!empty(getFeaturesSettings('course_notes_status'))) {

            $personalNote = CoursePersonalNote::query()
                ->where('id', $id)
                ->first();

            if (!empty($personalNote)) {
                $personalNote->delete();

                return apiResponse2(1, 'retrieved', trans('api.public.retrieved'));
            }
        }

        return apiResponse2(0, 'error', trans('api.public.error'));
    }

    public function store(Request $request)
    {
        $user = apiAuth();

        $data = $request->all();
        validateParam($data, [
            'item_type' => 'required|in:session,file,quiz,text_lesson,assignment',
            'item_id' => 'required',
            'course_id' => 'required',
            'note' => 'required',
        ]);


        $type = $this->getMorphByItemType($data['item_type']);


        $note = CoursePersonalNote::query()->updateOrCreate([
            'user_id' => $user->id,
            'course_id' => $data['course_id'],
            'targetable_id' => $data['item_id'],
            'targetable_type' => $type,
        ], [
            'note' => $data['note'] ?? null,
            'created_at' => time()
        ]);

        // Handle Attachment
        $this->handleUploadAttachment($request, $note, $user);

        if (!empty($note->attachment)) {
            $note->attachment = url($note->attachment);
        }

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $note);
    }

    private function handleUploadAttachment(Request $request, &$coursePersonalNote, $user)
    {
        $path = $coursePersonalNote->attachment ?? null;

        $file = $request->file('attachment');

        if (!empty($file)) {
            $destination = "webinars/personal_notes/{$coursePersonalNote->id}";
            $path = $this->uploadFile($file, $destination, 'attachment', $user->id);
        }

        $coursePersonalNote->update([
            'attachment' => $path
        ]);
    }

    private function getMorphByItemType($itemType)
    {
        $type = "";

        switch ($itemType) {
            case "session":
                $type = "App\Models\Session";
                break;

            case "file":
                $type = "App\Models\File";
                break;

            case "quiz":
                $type = "App\Models\Quiz";
                break;

            case "text_lesson":
                $type = "App\Models\TextLesson";
                break;

            case "assignment":
                $type = "App\Models\WebinarAssignment";
                break;
        }

        return $type;
    }
}
