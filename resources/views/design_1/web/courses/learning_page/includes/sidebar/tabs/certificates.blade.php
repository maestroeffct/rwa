@if($course->certificate)
    <div
        class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-20 bg-gray-100"
    >
        <div class="d-flex align-items-center cursor-pointer js-content-tab-item"
             data-type="course_certificate"
             data-id="{{ !empty($courseCertificate) ? $courseCertificate->id : '' }}"
             data-passed-error=""
             data-access-days-error=""
        >
            <div class="position-relative d-flex-center size-48 rounded-12 bg-primary-20">
                <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <div class="font-weight-bold font-14 text-dark">{{ trans('update.course_certificate') }}</div>

                @if(!empty($courseCertificate))
                    <span class="font-12 text-gray-500 mt-4">{{ dateTimeFormat($courseCertificate->created_at, 'j M Y') }}</span>
                @else
                    <span class="font-12 text-danger mt-4">{{ trans("update.not_achieve") }}</span>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center">
            @if(!empty($courseCertificate))
                <a href="/panel/certificates/webinars/{{ $courseCertificate->id }}/show" target="_blank" class="d-flex-center size-32 rounded-circle bg-white">
                    <x-iconsax-lin-import class="icons text-gray-500" width="16px" height="16px"/>
                </a>
            @endif
        </div>
    </div>
@endif

@if(!empty($course->quizzes) and count($course->quizzes))
    @foreach($course->quizzes as $courseQuiz)
        @if($courseQuiz->certificate)
            @php
                $courseQuizResult = $courseQuiz->result;
            @endphp
            <div
                class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-20 bg-gray-100"
            >
                <div class="d-flex align-items-center cursor-pointer js-content-tab-item"
                     data-type="quiz_certificate"
                     data-id="{{ !empty($courseQuizResult) ? $courseQuizResult->id : '' }}"
                     data-extra-key="quiz_id"
                     data-extra-value="{{ $courseQuiz->id }}"
                     data-passed-error=""
                     data-access-days-error=""
                >
                    <div class="position-relative d-flex-center size-48 rounded-12 bg-primary-20">
                        <x-iconsax-bul-award class="icons text-primary" width="24px" height="24px"/>
                    </div>

                    <div class="ml-8">
                        <div class="font-weight-bold font-14 text-dark">{{ truncate($courseQuiz->title, 27) }}</div>

                        @if(!empty($courseQuizResult))
                            <span class="font-12 text-gray-500 mt-4">{{ dateTimeFormat($courseQuizResult->created_at, 'j M Y') }}</span>
                        @else
                            <span class="font-12 text-danger mt-4">{{ trans("update.not_achieve") }}</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    @if(!empty($courseQuizResult))
                        <a href="/panel/quizzes/results/{{ $courseQuizResult->id }}/showCertificate" target="_blank" class="d-flex-center size-32 rounded-circle bg-white">
                            <x-iconsax-lin-import-2 class="icons text-gray-500" width="16px" height="16px"/>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    @endforeach
@endif
