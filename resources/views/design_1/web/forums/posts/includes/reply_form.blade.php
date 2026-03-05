<div class="card-with-mask mt-40">
    <div class="mask-8-white"></div>

    <div class="position-relative card-before-line bg-white rounded-24 p-16 z-index-2">
        <h3 class="font-14">{{ trans('update.reply_to_this_topic') }}</h3>

        <div class="mt-16">
            <form class="" action="{{ $topic->getPostsUrl() }}" method="post">
                {{ csrf_field() }}

                <div class="js-topic-post-reply-card d-none card-with-mask mb-24">
                    <div class="mask-8-white border-gray-200"></div>

                    <input type="hidden" name="reply_post_id" class="js-reply-post-id">

                    <div class="position-relative d-flex align-items-center justify-content-between bg-gray-100 p-16 rounded-12 border-gray-200 z-index-2">
                        <div class="d-flex align-items-center">
                            <div class="size-40 rounded-circle">
                                <img src="" alt="" class="js-reply-post-user-avatar img-cover rounded-circle">
                            </div>
                            <div class="ml-4">
                                <div class="js-reply-post-user-name d-block text-dark">{!! trans('update.you_are_replying_to_the_message') !!}</div>
                                <div class="js-reply-post-description mt-4 font-12 text-gray-500"></div>
                            </div>
                        </div>

                        <button type="button" class="js-close-reply-post btn-transparent">
                            <x-iconsax-lin-add class="icons close-icon text-gray-500" width="24px" height="24px"/>
                        </button>
                    </div>
                </div>

                <div class="form-group bg-white-editor">
                    <label class="form-group-label">{{ trans('public.description') }}</label>
                    <textarea name="description" data-height="500" class="js-ajax-description main-summernote form-control"></textarea>
                    <div class="invalid-feedback"></div>
                </div>


                <div class="form-group custom-input-file mb-0 flex-1">
                    <label class="form-group-label">{{ trans('update.attachment') }} ({{ trans('public.optional') }})</label>

                    <div class="custom-file bg-white">
                        <input type="file" name="attachment" class="custom-file-input js-ajax-upload-file-input" id="attachmentsInput" data-upload-name="attachment">
                        <span class="custom-file-text text-gray-500"></span>
                        <label class="custom-file-label bg-transparent" for="attachmentsInput">
                            <x-iconsax-lin-export class="icons text-gray-400" width="24px" height="24px"/>
                        </label>
                    </div>
                </div>

                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-16 pt-16 border-top-gray-200">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 rounded-12 bg-gray-300">
                            <x-iconsax-bol-info-circle class="icons text-gray-500" width="24px" height="24px"/>
                        </div>
                        <div class="ml-8">
                            <div class="d-block font-14 font-weight-bold">{{ trans('update.topic_reply') }}</div>
                            <p class="mt-4 font-12 text-gray-500">{{ trans('update.your_reply_will_be_published_immediately') }}</p>
                        </div>
                    </div>

                    <button type="button" class="js-save-post-btn btn btn-primary btn-lg mt-16 mt-lg-0">{{ trans('update.submit_reply') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
