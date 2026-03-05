<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Panel\Traits\AssignmentTrait;
use App\Models\File;
use App\Models\Sale;
use App\Models\Translation\FileTranslation;
use App\Models\Translation\WebinarAssignmentTranslation;
use App\Models\Webinar;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentAttachment;
use App\Models\WebinarAssignmentHistory;
use App\Models\WebinarAssignmentHistoryMessage;
use App\Models\WebinarChapterItem;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    use AssignmentTrait;

    public function myAssignments(Request $request)
    {
        $this->authorize("panel_assignments_lists");

        if (!getFeaturesSettings('webinar_assignment_status')) {
            abort(403);
        }

        $user = auth()->user();

        $purchasedCoursesIds = $user->getPurchasedCoursesIds();

        $query = WebinarAssignment::whereIn('webinar_id', $purchasedCoursesIds)
            ->where('status', 'active');

        $copyQuery = deepClone($query);

        $query = $this->handleMyAssignmentsFilters($request, $query, $user);

        $getListData = $this->getMyAssignmentsListData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }

        $topStats = $this->getMyAssignmentsListTopStats($copyQuery, $user, $purchasedCoursesIds);

        $webinars = Webinar::select('id', 'creator_id', 'teacher_id')
            ->whereIn('id', $purchasedCoursesIds)
            ->where('status', 'active')
            ->get();

        $instructorsIds = $webinars->pluck('teacher_id')->toArray();
        $instructors = User::query()->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio')
            ->whereIn('id', $instructorsIds)
            ->get();

        $data = [
            'pageTitle' => trans('update.my_assignments'),
            'webinars' => $webinars,
            'instructors' => $instructors,
        ];
        $data = array_merge($data, $topStats);
        $data = array_merge($data, $getListData);

        return view('design_1.panel.assignments.my_assignments.index', $data);
    }

    public function myCoursesAssignments(Request $request)
    {
        $this->authorize("panel_assignments_my_courses_assignments");

        if (!getFeaturesSettings('webinar_assignment_status')) {
            abort(403);
        }

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $query = WebinarAssignment::query()->where('creator_id', $user->id);

        $getListData = $this->myCoursesAssignmentsListData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }

        $topStats = $this->myCoursesAssignmentsListTopStats($user);
        $mostActiveAssignments = $this->myCoursesMostActiveAssignments($user);

        $breadcrumbs = [
            ['text' => trans('update.platform'), 'url' => '/'],
            ['text' => trans('panel.dashboard'), 'url' => '/panel'],
            ['text' => trans('update.assignments'), 'url' => null],
        ];

        $data = [
            'pageTitle' => trans('update.my_courses_assignments'),
            'mostActiveAssignments' => $mostActiveAssignments,
            'breadcrumbs' => $breadcrumbs,
        ];
        $data = array_merge($data, $topStats);
        $data = array_merge($data, $getListData);

        return view('design_1.panel.assignments.my-courses-assignments.index', $data);
    }

    public function myCoursesAssignmentsAllHistories(Request $request)
    {
        $this->authorize("panel_assignments_my_courses_assignments");

        if (!getFeaturesSettings('webinar_assignment_status')) {
            abort(403);
        }

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $query = WebinarAssignmentHistory::query()->where('instructor_id', $user->id);

        $getListData = $this->myCoursesAssignmentsAllHistoriesListData($request, $query, $user);

        if ($request->ajax()) {
            return $getListData;
        }

        $topStats = $this->myCoursesAssignmentsAllHistoriesTopStats($user);
        $pendingReviewAssignments = $this->pendingReviewAssignments($user);

        $data = [
            'pageTitle' => trans('update.my_courses_assignments'),
            'pendingReviewAssignments' => $pendingReviewAssignments,
        ];
        $data = array_merge($data, $topStats);
        $data = array_merge($data, $getListData);

        return view('design_1.panel.assignments.histories.index', $data);
    }

    public function students(Request $request, $id)
    {
        $this->authorize("panel_assignments_students");

        if (!getFeaturesSettings('webinar_assignment_status')) {
            abort(403);
        }

        $user = auth()->user();

        if (!$user->isOrganization() and !$user->isTeacher()) {
            abort(404);
        }

        $assignment = WebinarAssignment::where('id', $id)
            ->where('creator_id', $user->id)
            ->with([
                'webinar',
            ])
            ->first();

        if (!empty($assignment)) {

            $query = WebinarAssignmentHistory::query()->where('instructor_id', $user->id)
                ->where('assignment_id', $assignment->id);

            $getListData = $this->assignmentStudentsListData($request, $query, $user);

            if ($request->ajax()) {
                return $getListData;
            }

            $topStats = $this->assignmentStudentsTopStats($assignment, $user);
            $pendingReviewAssignments = $this->pendingReviewAssignments($user, $assignment);

            $data = [
                'pageTitle' => trans('update.my_courses_assignments'),
                'pendingReviewAssignments' => $pendingReviewAssignments,
                'assignment' => $assignment,
            ];
            $data = array_merge($data, $topStats);
            $data = array_merge($data, $getListData);

            return view('design_1.panel.assignments.students.index', $data);
        }

        abort(404);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];

        $rules = [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:255',
            'description' => 'required',
            'grade' => 'required|integer',
            'pass_grade' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
                $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
                $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
            } else {
                $data['check_previous_parts'] = false;
                $data['access_after_day'] = null;
            }

            $assignment = WebinarAssignment::create([
                'creator_id' => $user->id,
                'webinar_id' => $data['webinar_id'],
                'chapter_id' => $data['chapter_id'],
                'grade' => $data['grade'] ?? null,
                'pass_grade' => $data['pass_grade'] ?? null,
                'deadline' => $data['deadline'] ?? null,
                'attempts' => $data['attempts'] ?? null,
                'check_previous_parts' => $data['check_previous_parts'],
                'access_after_day' => $data['access_after_day'],
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? File::$Active : File::$Inactive,
                'created_at' => time(),
            ]);

            if (!empty($assignment)) {
                $locale = $request->get('locale', getDefaultLocale());

                WebinarAssignmentTranslation::updateOrCreate([
                    'webinar_assignment_id' => $assignment->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                $this->handleAttachments($request, $webinar, $user->id, $assignment->id, 'new');

                WebinarChapterItem::makeItem($assignment->creator_id, $assignment->chapter_id, $assignment->id, WebinarChapterItem::$chapterAssignment);
            }

            $webinar->update([
                'updated_at' => time()
            ]);

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    private function handleAttachments(Request $request, $webinar, $creatorId, $assignmentId, $reqKey)
    {
        WebinarAssignmentAttachment::where('creator_id', $creatorId)
            ->where('assignment_id', $assignmentId)
            ->delete();

        if (!empty($request->get("ajax")[$reqKey]) and !empty($request->get("ajax")[$reqKey]['attachments'])) {
            $attachments = $request->get("ajax")[$reqKey]['attachments'];

            if (!empty($attachments) and count($attachments)) {
                foreach ($attachments as $key => $attachment) {

                    if (!empty($attachment['title'])) {
                        $attachPath = null;

                        if (!empty($attachment['attach_path'])) {
                            $attachPath = $attachment['attach_path'];
                        }

                        $reqFileKey = "ajax.{$reqKey}.attachments.{$key}.attach";

                        if (!empty($request->file($reqFileKey))) {
                            $attachPath = $this->uploadFile($request->file($reqFileKey), "webinars/{$webinar->id}/assignments", null, $webinar->creator_id);
                        }

                        if (!empty($attachPath)) {
                            WebinarAssignmentAttachment::create([
                                'creator_id' => $creatorId,
                                'assignment_id' => $assignmentId,
                                'title' => $attachment['title'],
                                'attach' => $attachPath,
                            ]);
                        }
                    }
                }
            }
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $rules = [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:255',
            'description' => 'required',
            'grade' => 'required|integer',
            'pass_grade' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {
            if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
                $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
                $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
            } else {
                $data['check_previous_parts'] = false;
                $data['access_after_day'] = null;
            }

            $assignment = WebinarAssignment::where('id', $id)
                ->where(function ($query) use ($user, $webinar) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('webinar_id', $webinar->id);
                })
                ->first();

            if (!empty($assignment)) {
                $changeChapter = ($data['chapter_id'] != $assignment->chapter_id);
                $oldChapterId = $assignment->chapter_id;

                $assignment->update([
                    'chapter_id' => $data['chapter_id'],
                    'grade' => $data['grade'] ?? null,
                    'pass_grade' => $data['pass_grade'] ?? null,
                    'deadline' => $data['deadline'] ?? null,
                    'attempts' => $data['attempts'] ?? null,
                    'check_previous_parts' => $data['check_previous_parts'],
                    'access_after_day' => $data['access_after_day'],
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? File::$Active : File::$Inactive,
                ]);

                if ($changeChapter) {
                    WebinarChapterItem::changeChapter($assignment->creator_id, $oldChapterId, $assignment->chapter_id, $assignment->id, WebinarChapterItem::$chapterAssignment);
                }

                $locale = $request->get('locale', getDefaultLocale());

                WebinarAssignmentTranslation::updateOrCreate([
                    'webinar_assignment_id' => $assignment->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                $this->handleAttachments($request, $webinar, $assignment->creator_id, $assignment->id, $id);

                $webinar->update([
                    'updated_at' => time()
                ]);

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        $assignments = WebinarAssignment::where('id', $id)->first();

        if (!empty($assignments)) {
            $webinar = Webinar::query()->find($assignments->webinar_id);

            if ($assignments->creator_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                WebinarChapterItem::where('user_id', $assignments->creator_id)
                    ->where('item_id', $assignments->id)
                    ->where('type', WebinarChapterItem::$chapterAssignment)
                    ->delete();

                $assignments->delete();
            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
