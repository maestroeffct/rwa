@php
    $coverImage = getForumsImagesSettings("forum_search_topics_cover_image");
@endphp

<div class="card-with-mask position-relative">
    <div class="mask-8-white"></div>

    <div class="position-relative bg-white rounded-24 p-12 z-index-3">
        <div class="forum-topic-cover position-relative rounded-16">
            <img src="{{ $coverImage }}" alt="{{ trans('update.topics') }}" class="img-cover rounded-16">

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

            <h1 class="font-16 mt-8 ">{{ trans('update.forum_search_results') }}</h1>

            <div class="d-flex align-items-center justify-content-between mt-8">
                <div class="text-gray-500">{{ trans('update.check_results_or_try_new_words_for_new_results') }}</div>

                <div class="d-flex align-items-center gap-24">
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


            <div class="d-flex align-items-center justify-content-between mt-12 pt-16 border-top-gray-100">
                <div class="d-flex align-items-center gap-24">
                    <div class="text-primary">{{ trans('public.newest') }}</div>

                    <div class="text-gray-500">{{ trans('update.popular_topics') }}</div>

                    <div class="text-gray-500">{{ trans('update.not_answered') }}</div>
                </div>

                <div class="d-flex align-items-center gap-24">
                    <div class="js-show-search-drawer cursor-pointer">
                        <x-iconsax-lin-search-normal class="icons text-gray-500" width="20px" height="20px"/>
                    </div>

                    <a href="/forums/create-topic" class="btn btn-primary btn-lg">{{ trans('update.new_topic') }}</a>
                </div>
            </div>

        </div>

    </div>
</div>
