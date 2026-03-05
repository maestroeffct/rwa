<?php

namespace App\Http\Controllers\Web\traits;

trait CheckContentLimitationTrait
{
    public function checkContentLimitation($user = null, $coursePage = false)
    {
        if (!empty($user) and !$user->access_content) {
            $data = [
                'pageTitle' => trans('update.not_access_to_content'),
                'pageRobot' => getPageRobotNoIndex(),
                'userNotAccess' => true
            ];

            return view('design_1.web.courses.private_mode.index', $data);
        } elseif (empty($user) and getFeaturesSettings('webinar_private_content_status') and $coursePage) { // user not login
            $data = [
                'pageTitle' => trans('update.private_content'),
                'pageRobot' => getPageRobotNoIndex(),
            ];

            return view('design_1.web.courses.private_mode.index', $data);
        }


        return "ok";
    }
}
