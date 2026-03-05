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
    @include('design_1.web.components.reviews.rate_card', ['itemRow' => $event, 'reviewOptions' => $reviewOptions])

    {{-- Review form --}}
    @include('design_1.web.components.reviews.submit_form', [
        'itemRow' => $event,
        'itemName' => 'event_id',
        'reviewOptions' => $reviewOptions,
        'reviewFormPath' => "/events/{$event->slug}/reviews/store",
        'hasBought' => $event->checkUserHasBought(),
     ])

    {{-- Review Lists --}}
    @if(!empty($eventReviews) and count($eventReviews['reviews']))
        <div class="js-course-reviews-container">
            @include('design_1.web.components.reviews.all_cards', ['reviews' => $eventReviews['reviews']])
        </div>

        @if(!empty($eventReviews['has_more']))
            <div class="d-flex-center mt-16">
                <button type="button" class="js-review-load-more-btn d-flex-center py-16 px-24 rounded-12 border-dashed border-gray-300 text-gray-500 bg-white bg-hover-gray-100 cursor-pointer" data-path="/events/{{ $event->slug }}/reviews/load-more">
                    <x-iconsax-lin-rotate-left class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="ml-4">{{ trans('update.load_more') }}</span>
                </button>
            </div>
        @endif
    @endif

</div>

<div class="js-reply-to-review-html d-none">
    @include('design_1.web.components.reviews.reply_form', [
        'itemId' => $event->id,
        'itemName' => 'event_id',
        'reviewReplyFormPath' => "/events/{$event->slug}/reviews/store-reply-comment",
    ])
</div>
