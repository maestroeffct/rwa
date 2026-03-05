
<div class="card-before-line bg-white p-16 rounded-24 mt-60">
    <h4 class="font-14 mb-16">{{ trans('panel.comments') }}</h4>

    {{-- Form --}}
    @include('design_1.web.components.comments.submit_form', [
        'commentForItemId' => $post->id,
        'commentForItemName' => "blog_id",
    ])

    {{-- Comments Lists --}}
    @if(!empty($postComments) and count($postComments['comments']))
        <div class="js-course-comments-container">
            @include('design_1.web.components.comments.all_cards', [
                'comments' => $postComments['comments'],
                'commentForItemId' => $post->id,
                'commentForItemName' => "blog_id",
            ])
        </div>

        @if(!empty($postComments['has_more']))
            <div class="d-flex-center mt-16">
                <button type="button" class="js-comments-load-more-btn d-flex-center py-16 px-24 rounded-12 border-dashed border-gray-300 text-gray-500 bg-white bg-hover-gray-100 cursor-pointer" data-path="/comments/lists/blog/{{ $post->id }}">
                    <x-iconsax-lin-rotate-left class="icons text-gray-500" width="16px" height="16px"/>
                    <span class="ml-4">{{ trans('update.load_more') }}</span>
                </button>
            </div>
        @endif
    @endif

</div>

<div class="js-reply-to-comment-html d-none">
    @include('design_1.web.components.comments.reply_form')
</div>
