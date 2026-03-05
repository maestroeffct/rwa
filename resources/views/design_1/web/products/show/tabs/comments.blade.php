
<div class="bg-white p-16 rounded-24">

    {{-- Form --}}
    @include('design_1.web.components.comments.submit_form', [
        'commentForItemId' => $product->id,
        'commentForItemName' => "product_id",
    ])

    {{-- Comments Lists --}}
    @if(!empty($productComments) and count($productComments['comments']))
        <div class="js-course-comments-container">
            @include('design_1.web.components.comments.all_cards', [
                'comments' => $productComments['comments'],
                'commentForItemId' => $product->id,
                'commentForItemName' => "product_id",
            ])
        </div>

        @if(!empty($productComments['has_more']))
            <div class="d-flex-center mt-16">
                <button type="button" class="js-comments-load-more-btn d-flex-center py-16 px-24 rounded-12 border-dashed border-gray-300 text-gray-500 bg-white bg-hover-gray-100 cursor-pointer" data-path="/comments/lists/product/{{ $product->id }}">
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
