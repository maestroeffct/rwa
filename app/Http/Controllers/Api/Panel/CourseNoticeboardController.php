<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\CourseNoticeboardResource;
use App\Models\Api\Webinar;
use App\Models\CourseNoticeboard;
use Illuminate\Http\Request;

class CourseNoticeboardController extends Controller
{
    public function index($webinar_id)
    {
        $webinar = Webinar::find($webinar_id);
        abort_unless($webinar, 404);
        $user = apiAuth();
        // noticeboards

        $noticeboards = $webinar
            ->noticeboards;
        //  dd($noticeboards) ;
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), CourseNoticeboardResource::collection($noticeboards));

    }
}
