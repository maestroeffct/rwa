<div class="mt-28">
    <h3 class="font-16 font-weight-bold">{{ trans('update.pending_quizzes') }}</h3>
    <p class="mt-4 font-12 text-gray-500">{{ trans('update.you_have_several_quizzes_that_you_have_not_checked_them') }}</p>

    <div class="position-relative mt-16">
        <div class="swiper-container js-make-swiper pending-quizzes-swiper pb-24"
             data-item="pending-quizzes-swiper"
             data-autoplay="false"
             data-breakpoints="1440:4.2,769:3.4,320:1.4"
        >
            <div class="swiper-wrapper py-8">
                @foreach($pendingQuizzes->sortByDesc('expiry_timestamp') as $pendingQuiz)
                    <div class="swiper-slide">
                        <a href="/panel/quizzes/{{ $pendingQuiz->id }}/overview" target="_blank" class="d-block text-decoration-none">
                            <div class="bg-white p-20 rounded-12">
                                <div class="d-flex align-items-center justify-content-between">
                                        @if(!empty($pendingQuiz->icon))
                                        <div class="d-flex-center size-64 rounded-12">
                                            <img src="{{ $pendingQuiz->icon }}" class="img-cover rounded-12">
                                        @else
                                        <div class="d-flex-center size-64 bg-primary-30 rounded-12">
                                            <x-iconsax-bul-clipboard-tick class="icons text-white" width="32px" height="32px"/>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center ml-8 cursor-pointer">
                                        <span class="font-12 text-primary mr-4">{{ trans('update.start_now') }}</span>
                                        <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                    </div>
                                </div>

                                <h5 class="mt-12 font-14 font-weight-bold text-dark">{{ $pendingQuiz->title }}</h5>
                                <p class="mt-4 font-12 text-gray-500 text-ellipsis">{{ $pendingQuiz->webinar->title }}</p>

                                <div class="d-flex align-items-center mt-12 flex-wrap">

                                    <div class="d-flex align-items-center mr-16">
                                        <x-iconsax-lin-note-2 class="icons text-gray-400" width="16px" height="16px"/>
                                        <span class="ml-4 font-12 text-gray-400">{{ $pendingQuiz->quizQuestions->sum('grade') }}</span>
                                    </div>

                                    <div class="d-flex align-items-center mr-16">
                                        <x-iconsax-lin-timer-1 class="icons text-gray-400" width="16px" height="16px"/>
                                        <span class="ml-4 font-12 text-gray-400">{{ $pendingQuiz->time }} {{ trans('public.min') }}.</span>
                                    </div>

                                    @if(!empty($pendingQuiz->expiry_days) and !empty($pendingQuiz->expiry_timestamp))
                                        <div class="d-flex align-items-center mr-16">
                                            <x-iconsax-lin-calendar-2 class="icons text-gray-400" width="16px" height="16px"/>
                                            <span class="ml-4 font-12 text-gray-400">{{ trans('update.expired_on_date', ['date' => dateTimeFormat($pendingQuiz->expiry_timestamp, 'j M Y H:i')]) }}</span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
