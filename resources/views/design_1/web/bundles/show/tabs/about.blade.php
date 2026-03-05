

{{-- Installments --}}
@if(!empty($installments) and count($installments) and getInstallmentsSettings('installment_plans_position') == 'top_of_page')
    @foreach($installments as $installmentRow)
        @include('design_1.web.installments.includes.card',[
               'installment' => $installmentRow,
               'itemPrice' => $bundle->getPrice(),
               'itemId' => $bundle->id,
               'itemType' => 'bundles',
               'className' => '',
           ])
    @endforeach
@endif

<div class="bg-white py-16 rounded-24">

    {{-- About course --}}
    @if($bundle->description)
        <div class="px-16">
            <h2 class="font-16 font-weight-bold">{{ trans('update.about_this_bundle') }}</h2>

            <div class="course-description mt-12 text-gray-500">
                {!! nl2br($bundle->description) !!}
            </div>
        </div>
    @endif

    {{-- course FAQ --}}
    @if(!empty($bundle->faqs) and $bundle->faqs->count() > 0)
        <div id="bundleFAQParent" class="px-16 mt-32">
            <div class="">
                <h2 class="font-16 font-weight-bold">{{ trans('public.faq') }}</h2>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.check_frequently_asked_questions_about_this_bundle') }}</p>
            </div>

            @foreach($bundle->faqs as $faq)
                <div class="accordion p-20 rounded-16 border-gray-200 bg-white {{ $loop->first ? 'mt-16' : 'mt-20' }}">
                    <div class="accordion__title d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center cursor-pointer" href="#bundleFAQ_{{ $faq->id }}" data-parent="#bundleFAQParent" role="button" data-toggle="collapse">
                            <div class="size-24">
                                <x-iconsax-lin-message-question class="icons text-primary" width="24px" height="24px"/>
                            </div>

                            <div class="font-14 font-weight-bold ml-8">
                                {{ clean($faq->title,'title') }}
                            </div>
                        </div>

                        <div class="collapse-arrow-icon d-flex cursor-pointer" href="#bundleFAQ_{{ $faq->id }}" data-parent="#bundleFAQParent" role="button" data-toggle="collapse">
                            <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                        </div>
                    </div>

                    <div id="bundleFAQ_{{ $faq->id }}" class="accordion__collapse pt-0 mt-20 border-0 " role="tabpanel">
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
                    <img src="{{ $bundle->teacher->getAvatar(80) }}" alt="{{ $bundle->teacher->full_name }}" class="img-cover rounded-12">
                </div>

                <div class="ml-12 flex-1">
                    <a href="{{ $bundle->teacher->getProfileUrl() }}" target="_blank" class="">
                        <h6 class="font-14 font-weight-bold text-dark">{{ $bundle->teacher->full_name }}</h6>
                    </a>

                    @php
                        $bundleInstructorRates = $bundle->teacher->rates(true);
                    @endphp

                    @include('design_1.web.components.rate', [
                        'rate' => $bundleInstructorRates['rate'],
                        'rateCount' => $bundleInstructorRates['count'],
                        'rateClassName' => 'mt-4',
                    ])

                    <div class="d-flex align-items-center gap-12 mt-8">
                        <div class="d-flex align-items-center p-8 rounded-16 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <x-iconsax-lin-video-play class="icons text-gray-400" width="16px" height="16px"/>
                            <span class="mx-4 font-weight-bold">{{ $bundle->teacher->getTeacherCoursesCount() }}</span>
                            <span class="">{{ trans('update.courses') }}</span>
                        </div>

                        <div class="d-flex align-items-center p-8 rounded-16 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <x-iconsax-lin-teacher class="icons text-gray-400" width="16px" height="16px"/>
                            <span class="mx-4 font-weight-bold">{{ $bundle->teacher->getTeacherStudentsCount() }}</span>
                            <span class="">{{ trans('quiz.students') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-gray-500">{!! truncate($bundle->teacher->about, 716) !!}</div>
        </div>

        <div class="course-about-instructor-card__secondary-img position-relative">
            <img src="{{ $bundle->teacher->getProfileSecondaryImage() }}" alt="{{ $bundle->teacher->full_name }}" class="img-cover">
        </div>
    </div>
</div>

{{-- Related Courses --}}
@if(!empty($bundle->relatedCourses) and $bundle->relatedCourses->count() > 0)
    @php
        $relatedCourses = [];

        foreach($bundle->relatedCourses as $relatedCourse) {
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

{{-- Installments --}}
@if(!empty($installments) and count($installments) and getInstallmentsSettings('installment_plans_position') == 'bottom_of_page')
    @foreach($installments as $installmentRow)
        @include('design_1.web.installments.includes.card',[
               'installment' => $installmentRow,
               'itemPrice' => $bundle->getPrice(),
               'itemId' => $bundle->id,
               'itemType' => 'bundles',
               'className' => '',
           ])
    @endforeach
@endif
