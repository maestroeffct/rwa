@if(!empty($pendingReviewQuizzesResults) and count($pendingReviewQuizzesResults))
    <div class="mt-28">
        <div class="">
            <h3 class="font-16 text-dark">{{ trans('update.pending_review_quizzes') }}</h3>
            <p class="mt-4 font-12 text-gray-500">{{ trans('update.you_have_quizzes_waiting_for_your_review_please_review_them_to_calculate_your_student_grade') }}</p>
        </div>

        <div class="row">

            @foreach($pendingReviewQuizzesResults as $pendingReviewQuizResult)
                <div class="col-12 col-md-6 col-lg-3 mt-16">
                    <div class="position-relative most-active-assignment-card">
                        <div class="most-active-assignment-card__mask"></div>

                        <a href="{{ "/panel/quizzes/results/{$pendingReviewQuizResult->id}/edit" }}" class="d-block text-decoration-none">
                            <div class="position-relative z-index-2 bg-white p-20 rounded-16">
                                <div class="d-flex align-items-center">
                                    @if(!empty($pendingReviewQuizResult->quiz->icon))
                                        <div class="d-flex-center size-64">
                                            <img src="{{ $pendingReviewQuizResult->quiz->icon }}" alt="" class="img-fluid">
                                        </div>
                                    @else
                                        <div class="d-flex-center size-64 rounded-circle bg-gray-100">
                                            <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
                                        </div>
                                    @endif

                                    <div class="ml-8">
                                        <h6 class="font-14 font-weight-bold text-dark">{{ truncate($pendingReviewQuizResult->quiz->title, 28) }}</h6>

                                        @if(!empty($pendingReviewQuizResult->quiz->webinar))
                                            <p class="font-12 text-gray-500 mt-4">{{ truncate($pendingReviewQuizResult->quiz->webinar->title, 32) }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between mt-20 pt-20 border-top-gray-100">
                                    <div class="d-flex align-items-center">
                                        <div class="size-40 rounded-circle bg-gray-100">
                                            <img src="{{ $pendingReviewQuizResult->user->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                                        </div>
                                        <div class="ml-8">
                                            <h6 class="font-14 text-dark">{{ $pendingReviewQuizResult->user->full_name }}</h6>
                                            <p class="font-12 text-gray-500 mt-2">{{ dateTimeFormat($pendingReviewQuizResult->created_at, 'j M Y H:i') }}</p>
                                        </div>
                                    </div>

                                    <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endif
