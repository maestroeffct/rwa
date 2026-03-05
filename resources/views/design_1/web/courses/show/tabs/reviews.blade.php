<div class="bg-white p-16 rounded-24">

    @php
        $reviewOptions = [
            'content_quality',
            'instructor_skills',
            'purchase_worth',
            'support_quality',
        ];
    @endphp


    {{-- Rate --}}
    @include('design_1.web.components.reviews.rate_card', ['itemRow' => $course, 'reviewOptions' => $reviewOptions])

    {{-- Review form --}}
    @include('design_1.web.components.reviews.submit_form', [
        'itemRow' => $course,
        'itemName' => 'webinar_id',
        'reviewOptions' => $reviewOptions,
        'reviewFormPath' => "/reviews/store",
     ])

    {{-- Review Lists --}}
    @if(!empty($courseReviews) and count($courseReviews['reviews']))
        <div class="js-course-reviews-container">
            @include('design_1.web.components.reviews.all_cards', ['reviews' => $courseReviews['reviews']])
        </div>

        @if(!empty($courseReviews['has_more']))
            <div class="d-flex-center mt-16">
                <button type="button" class="js-review-load-more-btn d-flex-center py-16 px-24 rounded-12 border-dashed border-gray-300 text-gray-500 bg-white bg-hover-gray-100 cursor-pointer" data-path="/course/{{ $course->slug }}/reviews/load-more">
                    <x-iconsax-lin-rotate-left class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="ml-4">{{ trans('update.load_more') }}</span>
                </button>
            </div>
        @endif
    @endif

</div>

<div class="js-reply-to-review-html d-none">
    @include('design_1.web.components.reviews.reply_form', [
        'itemId' => $course->id,
        'itemName' => 'webinar_id',
        'reviewReplyFormPath' => "/reviews/store-reply-comment",
    ])
</div>
