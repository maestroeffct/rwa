<div class="bg-white mt-16 p-16 rounded-8 border-gray-200 border-dashed">
    <h5 class="font-14 font-weight-bold">{{ trans('update.reply_to_comment') }}</h5>

    <form action="" class="mt-16" method="post">
        <input type="hidden" name="item_id" value=""/>
        <input type="hidden" name="item_name" value=""/>

        <div class="form-group mb-0">
            <textarea name="reply" class="js-ajax-reply form-control" rows="6"></textarea>
            <div class="invalid-feedback"></div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-16 pt-16 border-top-gray-100">
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-48 bg-gray-300 rounded-12">
                    <x-iconsax-bol-info-circle class="icons text-gray-500" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h6 class="font-14">{{ trans('update.comments_approval') }}</h6>
                    <p class="mt-4 font-12 text-gray-500">{{ trans('update.your_comments_will_be_published_after_admin_approval') }}</p>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <button type="button" class="js-close-comment-reply-btn btn btn-lg bg-gray-400 text-gray-500 mr-12">{{ trans('public.close') }}</button>
                <button type="button" class="js-submit-comment-reply-btn btn btn-lg btn-primary">{{ trans('panel.reply') }}</button>
            </div>
        </div>
    </form>
</div>
