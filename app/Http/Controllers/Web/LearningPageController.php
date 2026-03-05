<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\LearningPageAssignmentTrait;
use App\Http\Controllers\Web\traits\LearningPageForumTrait;
use App\Http\Controllers\Web\traits\LearningPageItemInfoTrait;
use App\Http\Controllers\Web\traits\LearningPageMixinsTrait;
use App\Http\Controllers\Web\traits\LearningPageNoticeboardsTrait;
use App\Http\Controllers\Web\traits\LearningPagePersonalNoteTrait;
use App\Models\Certificate;
use App\Models\CourseLearningLastView;
use App\Models\CourseNoticeboard;
use Illuminate\Http\Request;

class LearningPageController extends Controller
{
    use LearningPageMixinsTrait, LearningPageAssignmentTrait, LearningPageItemInfoTrait,
        LearningPageNoticeboardsTrait, LearningPageForumTrait, LearningPagePersonalNoteTrait;

    public function index(Request $request, $slug)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            $this->authorize("panel_webinars_learning_page");
        }

        $requestData = $request->all();

        $webinarController = new WebinarController();

        $data = $webinarController->course($request, $slug, true);

        $course = $data['course'];
        $user = $data['user'];

        /* Check Not Active */
        if ($course->status != "active" and (empty($user) or (!$user->isAdmin() and !$course->canAccess($user)))) {
            $data = [
                'pageTitle' => trans('update.access_denied'),
                'pageRobot' => getPageRobotNoIndex(),
            ];
            return view('design_1.web.courses.not_access.index', $data);
        }

        $installmentLimitation = $webinarController->installmentContentLimitation($user, $course->id, 'webinar_id');
        if ($installmentLimitation != "ok") {
            return $installmentLimitation;
        }


        if (!$data or (!$data['hasBought'] and empty($course->getInstallmentOrder()))) {
            abort(403);
        }


        if ($course->certificate) {
            $data["courseCertificate"] = Certificate::where('type', 'course')
                ->where('student_id', $user->id)
                ->where('webinar_id', $course->id)
                ->first();
        }

        $data["userIsCourseTeacher"] = $this->checkUserIsInstructor($user, $course);

        $data['userLearningLastView'] = CourseLearningLastView::query()
            ->where('user_id', $user->id)
            ->where('webinar_id', $course->id)
            ->first();

        $siteTitle = getGeneralSettings("site_name") ?? trans('update.platform');

        $data['breadcrumbs'] = [
            ['text' => $siteTitle, 'url' => '/'],
            ['text' => trans('update.course'), 'url' => $course->getUrl()],
            ['text' => trans('update.learning_page'), 'url' => null],
        ];

        $data['saleItem'] = $course->getSaleItem();

        // Handle Start Tracking Time
        $this->handleStartTrackingTime($course->id, $user->id);

        return view('design_1.web.courses.learning_page.index', $data);
    }
}
