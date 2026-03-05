<div data-action="{{ $topic->getPostsUrl() }}/report" class="js-topic-report-form my-16">
    <input type="hidden" name="item_id" value="{{ $item }}"/>
    <input type="hidden" name="item_type" value="{{ $type }}"/>

    <div class="form-group">
        <label class="form-group-label" for="message_to_reviewer">{{ trans('public.message_to_reviewer') }}</label>
        <textarea name="message" id="message_to_reviewer" class="js-ajax-message form-control" rows="10"></textarea>
        <div class="invalid-feedback"></div>
    </div>

    <p class="text-gray-500 font-12">{{ trans('product.report_modal_hint') }}</p>

</div>
