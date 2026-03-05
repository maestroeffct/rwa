@php
    if (!empty($topic) and !empty($topic->cover)) {
        $coverImage = $topic->cover;
    } else {
        $coverImage = getForumsImagesSettings("create_topic_cover_image");
    }
@endphp

<div class="card-with-mask position-relative">
    <div class="mask-8-white"></div>

    <div class="position-relative bg-white rounded-24 p-12 z-index-3">
        <div class="forum-topic-cover position-relative rounded-16">
            <img src="{{ $coverImage }}" alt="{{ trans('update.new_topic') }}" class="img-cover rounded-16">

            <div class="forum-topic-cover__icon d-inline-flex-center size-64 rounded-circle bg-white border-gray-200">
                <x-iconsax-bul-messages class="icons text-primary" width="32px" height="32px"/>
            </div>
        </div>

        <div class="pl-12 pr-4 pb-12">
            <div class="d-flex align-items-center mt-40">
                <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-gray-400" width="16px" height="16px"/>
                <a href="/forums" class="text-gray-500">{{ trans('update.forum') }}</a>
            </div>

            <h1 class="font-16 mt-8 ">{{ !empty($topic) ? trans('update.edit_topic') : trans('update.new_topic') }}</h1>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-8">
                <div class="text-gray-500">{{ trans('update.create_a_new_topic_and_communicate_with_other_users') }}</div>

                <div class="d-flex align-items-center gap-24 mt-16 mt-lg-0">
                    <div class="d-flex align-items-center gap-4 font-12 text-gray-500">
                        <x-iconsax-lin-message-text class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-weight-bold">{{ $topicsCount }}</span>
                        <span class="">{{ trans('update.topics') }}</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 font-12 text-gray-500">
                        <x-iconsax-lin-messages class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-weight-bold">{{ $postsCount }}</span>
                        <span class="">{{ trans('update.posts') }}</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 font-12 text-gray-500">
                        <x-iconsax-lin-profile-2user class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-weight-bold">{{ $membersCount }}</span>
                        <span class="">{{ trans('update.members') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
