@foreach($reviews as $reviewRow)
    <div class="js-all-reviews-card">
        @include('design_1.web.components.reviews.review', [
            'comment' => $reviewRow,
            'commentCreator' => $reviewRow->creator,
            'replies' => $reviewRow->comments,
            'description' => $reviewRow->description,
            'replyItemId' => $reviewRow->id,
            'showRate' => true,
            'showReplyForm' => true,
            'deleteUrlPrefix' => "/reviews",
            'className' => 'bg-white p-16 rounded-12 border-gray-200 '. ($loop->first ? 'mt-24' : 'mt-16')
        ])

        <div class="js-review-reply-form">

        </div>
    </div>
@endforeach
