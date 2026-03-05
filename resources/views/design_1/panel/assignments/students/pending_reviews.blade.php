@if(!empty($pendingReviewAssignments) and count($pendingReviewAssignments))
    <div class="mt-28">
        <div class="">
            <h3 class="font-16 text-dark">{{ trans('update.pending_review_submissions') }}</h3>
            <p class="mt-4 font-12 text-gray-500">{{ trans('update.you_have_assignments_waiting_for_your_review') }}</p>
        </div>

        <div class="row">

            @foreach($pendingReviewAssignments as $pendingReviewAssignment)
                <div class="col-12 col-md-6 col-lg-3 mt-16">
                    <a href="{{ "{$pendingReviewAssignment->assignment->webinar->getLearningPageUrl()}?type=assignment&item={$pendingReviewAssignment->assignment->id}&student={$pendingReviewAssignment->student_id}" }}" target="_blank">
                        <div class="position-relative most-active-assignment-card">
                            <div class="most-active-assignment-card__mask"></div>

                            <div class="position-relative z-index-2 d-flex align-items-center justify-content-between bg-white p-20 rounded-16">
                                <div class="d-flex align-items-center">
                                    <div class="size-40 rounded-circle bg-gray-100">
                                        <img src="{{ $pendingReviewAssignment->student->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                                    </div>
                                    <div class="ml-8">
                                        <h6 class="font-14 text-dark">{{ $pendingReviewAssignment->student->full_name }}</h6>
                                        <p class="font-12 text-gray-500 mt-2">{{ dateTimeFormat($pendingReviewAssignment->created_at, 'j M Y H:i') }}</p>
                                    </div>
                                </div>

                                <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="16px" height="16px"/>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach

        </div>
    </div>
@endif
