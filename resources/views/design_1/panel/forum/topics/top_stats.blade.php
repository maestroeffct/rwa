<div class="row">
    <div class="col-12 col-lg-4">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.published_topics') }}</span>
                <div class="size-48 d-flex-center bg-success-30 rounded-12">
                    <x-iconsax-bul-messages-3 class="icons text-success" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $publishedTopicsCount }}</h5>
        </div>
    </div>

    <div class="col-12 col-lg-4 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.locked_topics') }}</span>
                <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                    <x-iconsax-bul-key-square class="icons text-danger" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $lockedTopicsCount }}</h5>
        </div>
    </div>

    <div class="col-12 col-lg-4 mt-16 mt-md-0">
        <div class="bg-white p-16 rounded-24">
            <div class="d-flex align-items-start justify-content-between">
                <span class="text-gray-500 mt-8">{{ trans('update.topic_messages') }}</span>
                <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                    <x-iconsax-bul-message-text class="icons text-primary" width="24px" height="24px"/>
                </div>
            </div>

            <h5 class="font-24 mt-12 line-height-1">{{ $topicMessagesCount }}</h5>
        </div>
    </div>

</div>
