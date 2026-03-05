@php
    $courseCertificatesCount = $course->quizzes->where('certificate', true)->count();

    if ($course->certificate) {
        $courseCertificatesCount += 1;
    }

    $userPassedCourseCertificate = !empty($authUser) ? $course->getUserPassedCourseCertificate($authUser) : null;
@endphp

@if($courseCertificatesCount > 0)
    <div id="allCertificatesAccordion">
        <div class="accordion p-12 rounded-12 border-gray-200 bg-white mt-16">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center cursor-pointer" href="#collapseCertificatesAccordion" data-parent="#allCertificatesAccordion" role="button" data-toggle="collapse">
                    <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                        <x-iconsax-bul-award class="icons text-primary" width="24px" height="24px"/>
                    </div>
                    <div class="ml-8">
                        <div class="font-14 font-weight-bold">{{ trans('panel.certificates') }}</div>
                        <div class="d-flex align-items-center mt-4 font-12 text-gray-500">{{ $courseCertificatesCount }} {{ trans('public.parts') }}</div>
                    </div>
                </div>

                <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseCertificatesAccordion" data-parent="#allCertificatesAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                </div>
            </div>

            <div id="collapseCertificatesAccordion" class="accordion__collapse border-0 " role="tabpanel">
                @foreach($course->quizzes as $quiz2)
                    @if(!empty($quiz2->certificate))
                        <section class="{{ $loop->first ? '' : 'mt-16' }}" id="certificateAccordion">
                            @include('design_1.web.courses.show.tabs.contents.quiz_certificate' , ['quiz' => $quiz2, 'accordionParent' => 'certificateAccordion'])
                        </section>
                    @endif
                @endforeach

                @if($course->certificate)
                    <section class="{{ ($courseCertificatesCount > 1) ? 'mt-16' : '0' }}" id="courseCertificateAccordion">
                        @include('design_1.web.courses.show.tabs.contents.course_certificate' , ['certificate' => $userPassedCourseCertificate, 'accordionParent' => 'courseCertificateAccordion'])
                    </section>
                @endif
            </div>
        </div>
    </div>
@endif
