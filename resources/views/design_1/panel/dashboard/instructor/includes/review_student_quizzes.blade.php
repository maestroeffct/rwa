<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark">{{ trans('update.review_student_quizzes') }}</h4>

    @if(!empty($reviewStudentQuizzes) and !empty($reviewStudentQuizzes['total']))

        <a href="/panel/quizzes/results" class="d-flex align-items-center justify-content-between p-12 rounded-16 bg-gray-100 mt-16">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                    <x-iconsax-bul-video class="icons text-primary" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <span class="d-block font-weight-bold text-dark">{{ $reviewStudentQuizzes['total'] }}</span>
                    <span class="d-block font-12 text-gray-500 mt-4">{{ trans('update.pending_review_quizzes') }}</span>
                </div>
            </div>

            <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
        </a>

        {{-- Card --}}
        @foreach($reviewStudentQuizzes['quizResults'] as $quizResult)
            <a href="/panel/quizzes/{{ $quizResult->id }}/edit-result" target="_blank" class="">
                <div class="bg-gray-100 p-16 mt-16 rounded-16">
                    <div class="bg-white rounded-12 py-16">
                        <div class="d-flex align-items-center px-16">
                            @if(!empty($quizResult->quiz->icon))
                                <div class="size-48 rounded-8 bg-gray-100">
                                    <img src="{{ $quizResult->quiz->icon }}" alt="" class="img-cover rounded-8">
                                </div>
                            @else
                                <div class="d-flex-center size-48 rounded-8 bg-success-30">
                                    <x-iconsax-bul-clipboard-tick class="icons text-success" width="32px" height="32px"/>
                                </div>
                            @endif

                            <div class="ml-8">
                                <div class="text-dark">{{ truncate($quizResult->quiz->title, 32) }}</div>
                                <span class="d-block font-12 text-gray-500 mt-4">{{ truncate($quizResult->quiz->webinar->title, 32) }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-40 mt-16 pt-24 border-top-gray-100 px-16">
                            <div class="d-flex align-items-center">
                                <x-iconsax-bul-message-question class="icons text-gray-500" width="20px" height="20px"/>
                                <span class="font-12 text-gray-500 ml-4">{{ $quizResult->quiz->getQuestionsCount() }}</span>
                            </div>

                            <div class="d-flex align-items-center">
                                <x-iconsax-bul-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                                <span class="font-12 text-gray-500 ml-4">{{ dateTimeFormat($quizResult->created_at, 'j M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-16">
                        <div class="d-flex align-items-center">
                            <div class="size-40 rounded-circle bg-gray-200">
                                <img src="{{ $quizResult->user->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-12 text-gray-500">{{ trans('update.submitted_by') }}</span>
                                <span class="d-block font-14 font-weight-bold text-dark">{{ $quizResult->user->full_name }}</span>
                            </div>
                        </div>

                        <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                </div>
            </a>
        @endforeach
    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column text-center mt-20 border-dashed border-gray-200 bg-gray-100 p-32 rounded-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_quiz_results!') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.instructor_dashboard_no_quiz_results_hint') }}</div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16">
            <div class="">
                <h6 class="font-14 text-dark">{{ trans('update.student_results') }}</h6>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.check_your_student_results') }}</p>
            </div>

            <a href="/panel/quizzes/results" target="_blank" class="d-flex-center size-40 bg-white border-gray-200 rounded-circle bg-hover-gray-100">
                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
            </a>
        </div>
    @endif
</div>
