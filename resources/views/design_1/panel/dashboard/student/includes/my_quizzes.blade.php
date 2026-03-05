{{-- If Have Data --}}
<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark">{{ trans('quiz.my_quizzes') }}</h4>

    @if(!empty($myQuizzes['quizzes']) and count($myQuizzes['quizzes']))

        <div class="d-grid grid-columns-2 gap-16 mt-16">
            {{-- Not Participated --}}
            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-16 p-16">
                <div class="">
                    <span class="d-block font-16 font-weight-bold text-dark">{{ $myQuizzes['notParticipatedCount'] }}</span>
                    <span class="d-block font-12 text-gray-500 mt-8">{{ trans('update.not_participated') }}</span>
                </div>

                <x-iconsax-bul-message-notif class="icons text-warning" width="24px" height="24px"/>
            </div>

            {{-- Pending Review --}}
            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-16 p-16">
                <div class="">
                    <span class="d-block font-16 font-weight-bold text-dark">{{ $myQuizzes['pendingReviewCount'] }}</span>
                    <span class="d-block font-12 text-gray-500 mt-8">{{ trans('update.pending_review') }}</span>
                </div>

                <x-iconsax-bul-messages class="icons text-primary" width="24px" height="24px"/>
            </div>
        </div>

        @foreach($myQuizzes['quizzes'] as $myPendingQuiz)
            <a href="/panel/quizzes/{{ $myPendingQuiz->id }}/overview" target="_blank" class="text-dark">
                <div class="bg-gray-100 rounded-16 p-12 mt-16">
                    <div class="bg-white py-12 rounded-12">
                        <div class="d-flex align-items-center px-16">
                            @if(!empty($myPendingQuiz->icon))
                                <div class="size-48 rounded-8 bg-gray-100">
                                    <img src="{{ $myPendingQuiz->icon }}" alt="" class="img-cover rounded-8">
                                </div>
                            @else
                                <div class="d-flex-center size-48 rounded-8 bg-success-30">
                                    <x-iconsax-bul-clipboard-tick class="icons text-success" width="24px" height="24px"/>
                                </div>
                            @endif

                            <div class="ml-8">
                                <span class="d-block text-dark">{{ truncate($myPendingQuiz->title, 26) }}</span>
                                <span class="d-block font-12 text-gray-500">{{ truncate($myPendingQuiz->webinar->title, 32) }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-40 mt-16 pt-24 pb-12 px-16 border-top-gray-100">
                            <div class="d-flex align-items-center">
                                <x-iconsax-bul-message-question class="icons text-gray-500" width="20px" height="20px"/>
                                <span class="ml-4 font-12 text-gray-500">{{ $myPendingQuiz->getQuestionsCount() }}</span>
                            </div>

                            @if(!empty($myPendingQuiz->submited_at))
                                <div class="d-flex align-items-center">
                                    <x-iconsax-bul-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                                    <span class="ml-4 font-12 text-gray-500">{{ dateTimeFormat($myPendingQuiz->submited_at, 'j M Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-16">
                        <div class="d-flex align-items-center">
                            <div class="size-40 rounded-circle">
                                <img src="{{ $myPendingQuiz->creator->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-12 text-gray-500">{{ trans('public.created_by') }}</span>
                                <span class="d-block font-weight-bold mt-4">{{ truncate($myPendingQuiz->creator->full_name, 25) }}</span>
                            </div>
                        </div>

                        <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                </div>
            </a>
        @endforeach
    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column text-center mt-16 rounded-16 border-dashed border-gray-200 bg-gray-100 p-32">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h5 class="font-14 text-dark mt-12">{{ trans('update.no_quiz!') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.you_don’t_have_any_not_participated_or_pending_review_quiz') }}</div>
        </div>
    @endif
</div>
