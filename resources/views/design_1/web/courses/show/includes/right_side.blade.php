<div class="course-right-side-section position-relative">
    <div class="course-right-side-section__mask"></div>

    <div class="position-relative bg-white rounded-24 pb-24 z-index-2">

        {{-- Thumbnail --}}
        <div class="course-right-side__thumbnail position-relative bg-gray-200">
            <img src="{{ $course->getImage() }}" class="img-cover" alt="{{ $course->title }}">

            @if($course->video_demo)
                <div id="webinarDemoVideoBtn" class="has-video-icon d-flex-center size-64 rounded-circle cursor-pointer"
                     data-video-path="{{ $course->video_demo_source == 'upload' ?  url($course->video_demo) : $course->video_demo }}"
                     data-video-source="{{ $course->video_demo_source }}"
                     data-thumbnail="{{ $course->getImage() }}"
                >
                    <x-iconsax-bol-play class="icons text-white" width="24px" height="24px"/>
                </div>
            @endif
        </div>

        <form action="/cart/store" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="item_id" value="{{ $course->id }}">
            <input type="hidden" name="item_name" value="webinar_id">

            {{-- Price --}}
            @include("design_1.web.courses.show.includes.rightSide.price")

            {{-- Enroll Form --}}
            @include("design_1.web.courses.show.includes.rightSide.enroll_form")

        </form>


        @if(!empty(getOthersPersonalizationSettings('show_guarantee_text')) and !empty(getGuarantyTextSettings("course_guaranty_text")))
            <div class="mt-14 d-flex align-items-center justify-content-center text-gray-500">
                <x-iconsax-lin-shield-tick class="icons text-gray-500" width="20px" height="20px"/>
                <span class="ml-4 font-12">{{ getGuarantyTextSettings("course_guaranty_text") }}</span>
            </div>
        @endif

        {{-- This course includes --}}
        <div class="mt-16 px-16">
            <h4 class="font-12 font-weight-bold">{{ trans('update.this_course_includes') }}</h4>

            @if($course->isDownloadable())
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-document-download class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('webinars.downloadable_content') }}</span>
                </div>
            @endif

            @if($course->quizzes->where('status', \App\models\Quiz::ACTIVE)->count() > 0)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-clipboard-tick class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('webinars.online_quizzes_count',['quiz_count' => $course->quizzes->where('status', \App\models\Quiz::ACTIVE)->count()]) }}</span>
                </div>
            @endif

            @if($course->certificate or ($course->quizzes->where('certificate', 1)->count() > 0))
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-medal class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('webinars.official_certificate') }}</span>
                </div>
            @endif

            @if($course->assignments->count() > 0)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-bookmark class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.n_assignments', ['count' => $course->assignments->count()]) }}</span>
                </div>
            @endif

            @if($course->support)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-message-question class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('webinars.instructor_support') }}</span>
                </div>
            @endif

            @if($course->forum)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-messages class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.course_forum') }}</span>
                </div>
            @endif

            <div class="d-flex align-items-center justify-content-around mt-16 p-12 rounded-12 border-dashed border-gray-200">
                @if($course->isWebinar())
                    <a href="{{ $course->addToCalendarLink() }}" target="_blank" class="d-flex-center flex-column text-gray-500 font-12">
                        <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                        <span class="mt-2">{{ trans('public.reminder') }}</span>
                    </a>
                @endif

                <a @if(auth()->guest()) href="/login" @else href="/favorites/{{ $course->slug }}/toggle" id="favoriteToggle" @endif class="d-flex-center flex-column font-12 {{ !empty($isFavorite) ? 'text-danger' : 'text-gray-500' }}">
                    <x-iconsax-lin-heart class="icons {{ !empty($isFavorite) ? 'text-danger' : 'text-gray-500' }}" width="20px" height="20px"/>
                    <span class="mt-2">{{ trans('panel.favorite') }}</span>
                </a>

                <div class="js-share-course d-flex-center flex-column text-gray-500 font-12 cursor-pointer" data-path="/course/{{ $course->slug }}/share-modal">
                    <x-iconsax-lin-share class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="mt-2">{{ trans('public.share') }}</span>
                </div>
            </div>

            <div class="mt-24 text-center">
                @if(auth()->guest())
                    <a href="/login" class="font-12 text-gray-500">{{ trans('update.report_abuse') }}</a>
                @else
                    <button type="button" class="js-report-course font-12 text-gray-500 btn-transparent" data-path="/course/{{ $course->slug }}/report-modal">{{ trans('update.report_abuse') }}</button>
                @endif
            </div>

        </div>

    </div>
</div>

{{-- Course Specifications --}}
@include("design_1.web.courses.show.includes.rightSide.course_specifications")

{{-- teacher --}}
@include("design_1.web.courses.show.includes.rightSide.teacher", ['userRow' => $course->teacher])

{{-- organization --}}
@if($course->creator_id != $course->teacher_id)
    @include("design_1.web.courses.show.includes.rightSide.teacher", ['userRow' => $course->creator])
@endif

{{-- Invited --}}
@if($course->webinarPartnerTeacher->count() > 0)
    @foreach($course->webinarPartnerTeacher as $webinarPartnerTeacher)
        @include("design_1.web.courses.show.includes.rightSide.teacher", ['userRow' => $webinarPartnerTeacher->teacher])
    @endforeach
@endif

{{-- Cashback --}}
@include('design_1.web.cashback.alert_card', [
    'cashbackRules' => $cashbackRules,
    'itemPrice' => $course->price,
    'cashbackRulesCardClassName' => "mt-28"
])

{{-- Send as Gift --}}
@include('design_1.web.courses.show.includes.rightSide.send_gift')

{{-- tags --}}
@if($course->tags->count() > 0)
    <div class="course-right-side-section position-relative mt-28">
        <div class="course-right-side-section__mask"></div>

        <div class="position-relative card-before-line bg-white rounded-24 p-16 z-index-2">
            <h4 class="font-14 font-weight-bold">{{ trans('public.tags') }}</h4>

            <div class="d-flex gap-12 flex-wrap mt-16">
                @foreach($course->tags as $tag)
                    <a href="/tags/courses/{{ urlencode($tag->title) }}" target="_blank" class="d-flex-center p-10 rounded-8 bg-gray-100 font-12 text-gray-500 text-center">{{ $tag->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endif
