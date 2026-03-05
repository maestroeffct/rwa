<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\Sale;
use App\Models\WebinarAssignment;
use App\Models\WebinarAssignmentAttachment;
use App\Models\WebinarAssignmentHistory;
use App\Models\WebinarAssignmentHistoryMessage;
use App\User;
use Illuminate\Http\Request;

trait LearningPageAssignmentTrait
{
    public function downloadAssignment($assignmentId, $id)
    {
        $assignment = WebinarAssignment::where('id', $assignmentId)
            ->first();

        if (!empty($assignment)) {
            $checkSequenceContent = !empty($assignment) ? $assignment->checkSequenceContent() : null;
            $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

            if ($this->checkCourseAccess($assignment->webinar) and !$sequenceContentHasError) {
                $attach = WebinarAssignmentAttachment::where('id', $id)
                    ->where('assignment_id', $assignmentId)
                    ->first();

                if (!empty($attach)) {
                    $filePath = public_path($attach->attach);

                    if (file_exists($filePath)) {
                        $fileInfo = pathinfo($filePath);
                        $type = (!empty($fileInfo) and !empty($fileInfo['extension'])) ? $fileInfo['extension'] : '';

                        $fileName = str_replace(' ', '-', $attach->title);
                        $fileName = str_replace('.', '-', $fileName);
                        $fileName .= '.' . $type;

                        $headers = array(
                            'Content-Type: application/' . $type,
                        );

                        return response()->download($filePath, $fileName, $headers);
                    }
                }
            }

            abort(403);
        }

        abort(404);
    }

    private function getAssignmentInfo(Request $request, $course)
    {
        $user = auth()->user();
        $id = $request->get('id');
        $studentId = $request->get('student');

        $assignment = WebinarAssignment::where('id', $id)
            ->where('webinar_id', $course->id)
            ->first();

        if (!empty($assignment) and !empty($course) and $course->checkUserHasBought($user)) {

            $studentId = (!empty($studentId) and $course->isOwner($user->id)) ? $studentId : null;

            if ($course->isOwner($user->id) and empty($studentId)) {
                // Show Instructor Content
                return $this->getLearningPageInstructorContent($course, $assignment, $user);
            } else {
                // Show Student Page

                $assignmentHistory = $this->getAssignmentHistory($course, $assignment, $user, $studentId);

                if (!empty($assignmentHistory)) {
                    $checkHasAttempts = $this->checkHasAttempts($assignment, $assignmentHistory, $user);
                    $submissionTimes = $assignmentHistory->messages
                        ->where('sender_id', !empty($studentId) ? $studentId : $user->id)
                        //->whereNotNull('file_path')
                        ->count();

                    $student = null;
                    if (!empty($studentId)) {
                        $student = User::find($studentId);
                    }

                    $deadline = $this->getAssignmentDeadline($assignment, !empty($student) ? $student : $user);

                    $data = [
                        'assignment' => $assignment,
                        'assignmentHistory' => $assignmentHistory,
                        'checkHasAttempts' => $checkHasAttempts,
                        'submissionTimes' => $submissionTimes,
                        'assignmentDeadline' => $deadline,
                        'assignmentStudentId' => $studentId,
                        'user' => $user,
                    ];

                    $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.assignment", $data);

                    return response()->json([
                        'code' => 200,
                        'type' => 'assignment',
                        'html' => $html,
                    ]);
                }
            }
        }

        return response()->json([], 403);
    }

    private function getLearningPageInstructorContent($course, $assignment, $user)
    {
        $instructorAssignmentHistories = $assignment->instructorAssignmentHistories()->where('instructor_id', $user->id)->get();

        $grades = $instructorAssignmentHistories->filter(function ($item) {
            return !is_null($item->grade);
        });

        $historyIds = $instructorAssignmentHistories->pluck('id')->toArray();

        $assignment->average_grade = count($grades) ? $grades->avg('grade') : null;

        $assignment->submissions = WebinarAssignmentHistoryMessage::whereIn('assignment_history_id', $historyIds)
            ->where('sender_id', '!=', $user->id)
            ->count();

        $assignment->pendingCount = $instructorAssignmentHistories->where('status', WebinarAssignmentHistory::$pending)->count();
        $assignment->passedCount = $instructorAssignmentHistories->where('status', WebinarAssignmentHistory::$passed)->count();
        $assignment->failedCount = $instructorAssignmentHistories->where('status', WebinarAssignmentHistory::$notPassed)->count();

        $data = [
            'assignment' => $assignment,
            'course' => $course,
        ];
        $html = (string)view()->make("design_1.web.courses.learning_page.includes.contents.assignment_instructor", $data);

        return response()->json([
            'code' => 200,
            'type' => 'assignment',
            'html' => $html,
        ]);
    }

    private function getAssignmentHistory($course, $assignment, $user, $studentId = null)
    {
        $assignmentHistory = WebinarAssignmentHistory::where('instructor_id', $assignment->creator_id)
            ->where(function ($query) use ($user, $studentId) {
                if (!empty($studentId)) {
                    $query->where('student_id', $studentId);
                } else {
                    $query->where('student_id', $user->id);
                }
            })
            ->where('assignment_id', $assignment->id)
            ->with([
                'messages' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                    $query->whereHas('sender');
                    $query->with([
                        'sender'
                    ]);
                }
            ])->first();

        if (empty($assignmentHistory) and !$course->isOwner($user->id) and !$user->isAdmin() and !$course->isPartnerTeacher($user->id)) {
            $assignmentHistory = WebinarAssignmentHistory::create([
                'instructor_id' => $assignment->creator_id,
                'student_id' => $user->id,
                'assignment_id' => $assignment->id,
                'status' => WebinarAssignmentHistory::$notSubmitted,
                'created_at' => time(),
            ]);
        }

        return $assignmentHistory;
    }

    private function getAssignmentDeadline($assignment, $user)
    {
        $deadline = true; // default can access

        if (!empty($assignment->deadline)) {
            $conditionDay = $assignment->getDeadlineTimestamp($user);

            if (time() > $conditionDay) {
                $deadline = false;
            } else {
                $deadline = round(($conditionDay - time()) / (60 * 60 * 24), 1);
            }
        }

        return $deadline;
    }

    private function checkHasAttempts($assignment, $assignmentHistory, $user)
    {
        $result = true;

        if (!empty($assignment->attempts) and $user->id != $assignment->creator_id) {
            $submissionTimes = $assignmentHistory->messages
                ->where('sender_id', $user->id)
                //->whereNotNull('file_path')
                ->count();

            $result = ($submissionTimes < $assignment->attempts);
        }

        return $result;
    }
}
