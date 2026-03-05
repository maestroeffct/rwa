<div class="course-right-side-section position-relative">
    <div class="course-right-side-section__mask"></div>

    <div class="position-relative bg-white rounded-24 pb-24 z-index-2">

        {{-- Thumbnail --}}
        <div class="course-right-side__thumbnail position-relative bg-gray-200">
            <img src="{{ $upcomingCourse->getImage() }}" class="img-cover" alt="{{ $upcomingCourse->title }}">

            @if($upcomingCourse->video_demo)
                <div id="webinarDemoVideoBtn" class="has-video-icon d-flex-center size-64 rounded-circle"
                     data-video-path="{{ $upcomingCourse->video_demo_source == 'upload' ?  url($upcomingCourse->video_demo) : $upcomingCourse->video_demo }}"
                     data-video-source="{{ $upcomingCourse->video_demo_source }}"
                >
                    <x-iconsax-bol-play class="icons text-white" width="24px" height="24px"/>
                </div>
            @endif
        </div>

        <div class="px-16 mt-16">
            @if(!empty($upcomingCourse->webinar))
                <a href="{{ $upcomingCourse->webinar->getUrl() }}" class="btn btn-lg btn-primary btn-block">{{ trans('update.view_course') }}</a>
            @else
                @if(!empty($authUser))
                    <button type="button" class="js-follow-upcoming-course btn btn-lg btn-primary btn-block" data-path="/upcoming_courses/{{ $upcomingCourse->slug }}/toggleFollow">
                        {{ $followed ? trans('update.unfollow_course') : trans('update.follow_course') }}
                    </button>
                @else
                    <a href="/login" class="btn btn-lg btn-primary btn-block">{{ trans('update.follow_course') }}</a>
                @endif
            @endif
        </div>

        <div class="d-flex-center text-gray-500 mt-12">
            <x-iconsax-lin-notification class="icons text-gray-500" width="20px" height="20px"/>
            <span class="ml-4 font-12">{{ trans('update.youll_get_notified_about_course_publish') }}</span>
        </div>


        {{-- This course includes --}}
        <div class="mt-16 px-16">
            <h4 class="font-12 font-weight-bold">{{ trans('update.this_course_includes') }}</h4>

            @if($upcomingCourse->downloadable)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-document-download class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('webinars.downloadable_content') }}</span>
                </div>
            @endif

            @if($upcomingCourse->include_quizzes)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-clipboard-tick class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('quiz.quizzes') }}</span>
                </div>
            @endif

            @if($upcomingCourse->certificate)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-medal class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('public.certificate') }}</span>
                </div>
            @endif

            @if($upcomingCourse->assignments)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-bookmark class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.assignments') }}</span>
                </div>
            @endif

            @if($upcomingCourse->support)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-message-question class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('webinars.instructor_support') }}</span>
                </div>
            @endif

            @if($upcomingCourse->forum)
                <div class="d-flex align-items-center mt-12 font-12 text-gray-500">
                    <x-iconsax-lin-messages class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="ml-4">{{ trans('update.course_forum') }}</span>
                </div>
            @endif

            <div class="d-flex align-items-center justify-content-around mt-16 p-12 rounded-12 border-dashed border-gray-200">
                @if(!empty($upcomingCourse->publish_date))
                    <a href="{{ $upcomingCourse->addToCalendarLink() }}" target="_blank" class="d-flex-center flex-column text-gray-500 font-12">
                        <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                        <span class="mt-2">{{ trans('public.reminder') }}</span>
                    </a>
                @endif

                <a @if(auth()->guest()) href="/login" @else href="/upcoming_courses/{{ $upcomingCourse->slug }}/favorite" id="favoriteToggle" @endif class="d-flex-center flex-column font-12 {{ !empty($isFavorite) ? 'text-danger' : 'text-gray-500' }}">
                    <x-iconsax-lin-heart class="icons {{ !empty($isFavorite) ? 'text-danger' : 'text-gray-500' }}" width="20px" height="20px"/>
                    <span class="mt-2">{{ trans('panel.favorite') }}</span>
                </a>

                <div class="js-share-course d-flex-center flex-column text-gray-500 font-12 cursor-pointer" data-path="/upcoming_courses/{{ $upcomingCourse->slug }}/share-modal">
                    <x-iconsax-lin-share class="icons text-gray-500" width="20px" height="20px"/>
                    <span class="mt-2">{{ trans('public.share') }}</span>
                </div>
            </div>

            <div class="mt-24 text-center">
                <button type="button" class="js-report-course font-12 text-gray-500 btn-transparent" data-path="/upcoming_courses/{{ $upcomingCourse->slug }}/report-modal">{{ trans('update.report_abuse') }}</button>
            </div>

        </div>

    </div>
</div>

{{-- Course Specifications --}}
@include("design_1.web.upcoming_courses.show.includes.rightSide.course_specifications")

{{-- teacher --}}
@include("design_1.web.courses.show.includes.rightSide.teacher", ['userRow' => $upcomingCourse->teacher])

{{-- organization --}}
@if($upcomingCourse->creator_id != $upcomingCourse->teacher_id)
    @include("design_1.web.courses.show.includes.rightSide.teacher", ['userRow' => $upcomingCourse->creator])
@endif

{{-- tags --}}
@if($upcomingCourse->tags->count() > 0)
    <div class="course-right-side-section position-relative mt-28">
        <div class="course-right-side-section__mask"></div>

        <div class="position-relative card-before-line bg-white rounded-24 p-16 z-index-2">
            <h4 class="font-14 font-weight-bold">{{ trans('public.tags') }}</h4>

            <div class="d-flex gap-12 flex-wrap mt-16">
                @foreach($upcomingCourse->tags as $tag)
                    <a href="/tags/upcoming-courses/{{ urlencode($tag->title) }}" target="_blank" class="d-flex-center p-10 rounded-8 bg-gray-100 font-12 text-gray-500 text-center">{{ $tag->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endif
