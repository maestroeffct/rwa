@foreach($comments as $commentRow)
    <div class="js-comment-card">
        @include('design_1.web.components.comments.comment', [
            'comment' => $commentRow,
            'replies' => $commentRow->replies,
            'mainCommentId' => $commentRow->id,
            'commentForItemId' => $commentForItemId,
            'commentForItemName' => $commentForItemName,
            'className' => 'bg-white p-16 rounded-12 border-gray-200 '. ($loop->first ? 'mt-24' : 'mt-16')
        ])

        <div class="js-comment-reply-form">

        </div>
    </div>
@endforeach
