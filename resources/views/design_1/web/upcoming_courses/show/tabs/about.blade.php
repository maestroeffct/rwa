@php
    $learningMaterialsExtraDescription = !empty($upcomingCourse->extraDescriptions) ? $upcomingCourse->extraDescriptions->where('type','learning_materials') : null;
    $companyLogosExtraDescription = !empty($upcomingCourse->extraDescriptions) ? $upcomingCourse->extraDescriptions->where('type','company_logos') : null;
    $requirementsExtraDescription = !empty($upcomingCourse->extraDescriptions) ? $upcomingCourse->extraDescriptions->where('type','requirements') : null;
@endphp

@if(!empty($upcomingCourse->webinar_id) and !empty($upcomingCourse->webinar))
    <div class="position-relative pl-8">
        <div class="d-flex align-items-center p-12 rounded-12 bg-success-20 mb-16">
            <div class="alert-left-20 d-flex-center size-48 bg-success rounded-12">
                <x-iconsax-bol-tick-circle class="icons text-white" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h6 class="font-14 text-success">{{ trans('update.course_published') }}</h6>
                <p class="font-12 text-success opacity-75">{{ trans('update.the_course_published_and_you_can_check_the_main_course') }}</p>
            </div>
        </div>
    </div>

    @include('design_1.web.courses.components.cards.rows.index',['courses' => [$upcomingCourse->webinar], 'rowCardClassName' => "mb-20"])
@endif

<div class="bg-white py-16 rounded-24">

    {{-- What will you learn --}}
    @if(!empty($learningMaterialsExtraDescription) and count($learningMaterialsExtraDescription))
        <div class="mb-32 px-16">
            <div class="course-extra-card bg-gray-100 p-12 rounded-12 mt-40">
                <div class="course-extra-card__title p-16 rounded-12 border-dashed border-gray-200 bg-white">
                    <h3 class="font-16 font-weight-bold">{{ trans('update.what_you_will_learn') }}</h3>
                </div>

                <div class="d-grid grid-columns-auto grid-lg-columns-2 gap-12 mt-12">
                    @foreach($learningMaterialsExtraDescription as $learningMaterial)
                        <div class="d-flex align-items-center p-16 rounded-8 bg-white">
                            <div class="size-16">
                                <x-tick-icon class="icons text-primary" width="16px" height="16px"/>
                            </div>

                            <span class="flex-1 ml-4 font-14 text-gray-500">{{ $learningMaterial->value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- About course --}}
    @if($upcomingCourse->description)
        <div class="px-16">
            <h2 class="font-16 font-weight-bold">{{ trans('update.about_this_course') }}</h2>

            <div class="course-description mt-12 text-gray-500">
                {!! nl2br($upcomingCourse->description) !!}
            </div>
        </div>
    @endif

    {{-- Requirements --}}
    @if(!empty($requirementsExtraDescription) and count($requirementsExtraDescription))
        <div class="px-16 pb-28">
            <div class="course-extra-card bg-gray-100 p-12 pb-28 rounded-12 mt-32">
                <div class="course-extra-card__title d-flex align-items-center justify-content-between p-16 rounded-12 border-dashed border-gray-200 bg-white">
                    <h3 class="font-16 font-weight-bold">{{ trans('update.requirements') }}</h3>

                    <div class="size-24">
                        <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
                    </div>
                </div>

                <div class="">
                    @foreach($requirementsExtraDescription as $requirementExtraDescription)
                        <div class="d-flex align-items-center {{ $loop->first ? 'mt-20' : 'mt-16' }}">
                            <div class="size-16">
                                <x-tick-icon class="icons text-primary" width="16px" height="16px"/>
                            </div>

                            <span class="flex-1 ml-4 font-14 text-gray-500">{{ $requirementExtraDescription->value }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="course-extra-card__float-img">
                    <img src="/assets/design_1/img/courses/requirements.svg" alt="{{ trans('update.requirements') }}" class="img-fluid">
                </div>
            </div>
        </div>
    @endif

    @if(!empty($companyLogosExtraDescription) and count($companyLogosExtraDescription))
        <div class="mt-32 p-16 pb-28 border-top-gray-200 border-bottom-gray-200">
            <div class="">
                <h2 class="font-16 font-weight-bold">{{ trans('update.trusted_companies') }}</h2>
                <p class="mt-4 font-12 text-gray-500">+3200 Companies trusted our courses for their staff tutoring</p>
            </div>

            <div class="position-relative mt-16">
                <div class="swiper-container js-make-swiper course-trusted-companies-slider pb-0"
                     data-item="course-trusted-companies-slider"
                     data-autoplay="true"
                     data-loop="true"
                     data-breakpoints="1440:5.5,769:4.2,320:1.4"
                >
                    <div class="swiper-wrapper py-0 mx-16 mx-md-32">
                        @foreach($companyLogosExtraDescription as $companyLogo)
                            <div class="swiper-slide course-company-logos d-flex-center">
                                <img src="{{ $companyLogo->value }}" class="img-fluid" alt="{{ trans('update.company_logos') }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- course FAQ --}}
    @if(!empty($upcomingCourse->faqs) and $upcomingCourse->faqs->count() > 0)
        <div id="courseFAQParent" class="px-16 mt-32">
            <div class="">
                <h2 class="font-16 font-weight-bold">{{ trans('public.faq') }}</h2>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.check_frequently_asked_questions_about_this_course') }}</p>
            </div>

            @foreach($upcomingCourse->faqs as $faq)
                <div class="accordion p-20 rounded-16 border-gray-200 bg-white {{ $loop->first ? 'mt-16' : 'mt-20' }}">
                    <div class="accordion__title d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center cursor-pointer" href="#courseFAQ_{{ $faq->id }}" data-parent="#courseFAQParent" role="button" data-toggle="collapse">
                            <div class="size-24">
                                <x-iconsax-lin-message-question class="icons text-primary" width="24px" height="24px"/>
                            </div>

                            <div class="font-14 font-weight-bold ml-8">
                                {{ clean($faq->title,'title') }}
                            </div>
                        </div>

                        <div class="collapse-arrow-icon d-flex cursor-pointer" href="#courseFAQ_{{ $faq->id }}" data-parent="#courseFAQParent" role="button" data-toggle="collapse">
                            <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                        </div>
                    </div>

                    <div id="courseFAQ_{{ $faq->id }}" class="accordion__collapse pt-0 mt-20 border-0 " role="tabpanel">
                        <div class="p-16 rounded-8 border-gray-200 text-gray-500 mt-8">
                            {{ clean($faq->answer,'answer') }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @endif

</div>

{{-- About Instructor --}}
<div class="course-about-instructor-card position-relative mt-32 mt-lg-60">
    <div class="course-about-instructor-card__mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-start gap-24 bg-white px-16 rounded-24 z-index-3">
        <div class="course-about-instructor-card__details flex-1 py-16">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-80 rounded-12 bg-gray-200">
                    <img src="{{ $upcomingCourse->teacher->getAvatar(80) }}" alt="{{ $upcomingCourse->teacher->full_name }}" class="img-cover rounded-12">
                </div>
                <div class="ml-12 flex-1">
                    <a href="{{ $upcomingCourse->teacher->getProfileUrl() }}" target="_blank" class="">
                        <h6 class="font-14 font-weight-bold text-dark">{{ $upcomingCourse->teacher->full_name }}</h6>
                    </a>

                    @php
                        $upcomingCourseInstructorRates = $upcomingCourse->teacher->rates(true);
                    @endphp

                    @include('design_1.web.components.rate', [
                        'rate' => $upcomingCourseInstructorRates['rate'],
                        'rateCount' => $upcomingCourseInstructorRates['count'],
                        'rateClassName' => 'mt-4',
                    ])

                    <div class="d-flex align-items-center gap-12 mt-8">
                        <div class="d-flex align-items-center p-8 rounded-16 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <x-iconsax-lin-video-play class="icons text-gray-400" width="16px" height="16px"/>
                            <span class="mx-4 font-weight-bold">{{ $upcomingCourse->teacher->getTeacherCoursesCount() }}</span>
                            <span class="">{{ trans('update.courses') }}</span>
                        </div>

                        <div class="d-flex align-items-center p-8 rounded-16 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <x-iconsax-lin-teacher class="icons text-gray-400" width="16px" height="16px"/>
                            <span class="mx-4 font-weight-bold">{{ $upcomingCourse->teacher->getTeacherStudentsCount() }}</span>
                            <span class="">{{ trans('quiz.students') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-gray-500">{!! truncate($upcomingCourse->teacher->about, 716) !!}</div>
        </div>

        <div class="course-about-instructor-card__secondary-img position-relative">
            <img src="{{ $upcomingCourse->teacher->getProfileSecondaryImage() }}" alt="{{ $upcomingCourse->teacher->full_name }}" class="img-cover">
        </div>
    </div>
</div>

{{-- Related Courses --}}
@if(!empty($upcomingCourse->relatedCourses) and $upcomingCourse->relatedCourses->count() > 0)
    @php
        $relatedCourses = [];

        foreach($upcomingCourse->relatedCourses as $relatedCourse) {
            if(!empty($relatedCourse->course)) {
                $relatedCourses[] = $relatedCourse->course;
            }
        }
    @endphp

    @if(count($relatedCourses))
        <div class="mt-48">
            <div class="">
                <h2 class="font-16 font-weight-bold">{{ trans('update.related_courses') }}</h2>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.explore_courses_we_published_currently_and_enjoy_updated_information') }}</p>
            </div>

            <div class="row">
                @include('design_1.web.courses.components.cards.grids.index',['courses' => $relatedCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-16"])
            </div>
        </div>
    @endif
@endif
