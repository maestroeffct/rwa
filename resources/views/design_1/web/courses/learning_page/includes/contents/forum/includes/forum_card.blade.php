<div class="card-with-mask learning-page__forum-card">
    <div class="mask-8-white rounded-24"></div>

    <div class="position-relative z-index-2 d-flex flex-column flex-lg-row gap-16 bg-white p-12 rounded-24">
        <div class="learning-page__forum-card-info p-8 flex-1">
            <div class="d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="size-40 rounded-circle">
                        <img src="{{ $forum->user->getAvatar(40) }}" class="img-cover rounded-circle" alt="{{ $forum->user->full_name }}">
                    </div>
                    <div class="ml-8">
                        <a href="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/answers" class="">
                            <h4 class="font-14 font-weight-bold text-dark">{{ truncate($forum->title, 32) }}</h4>
                        </a>

                        <div class="mt-4 font-12 text-gray-500">{{ trans('public.by') }} <span class="font-weight-bold">{{ truncate($forum->user->full_name, 15) }}</span> {{ trans('update.on') }} {{ dateTimeFormat($forum->created_at, 'j M Y') }}</div>
                    </div>
                </div>

                @if($course->isOwner($user->id))
                    <div class="js-forum-pin-toggle cursor-pointer text-gray-500" data-action="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/pinToggle">
                        <x-iconsax-lin-paperclip-2 class="icons " width="16px" height="16px"/>
                    </div>
                @endif
            </div>

            <div class="learning-page__forum-card-description mt-12 text-gray-500">{{ truncate($forum->description, 224) }}</div>
        </div>

        <div class="learning-page__forum-card-stats bg-gray-100 rounded-12 p-12">
            @if(!empty($forum->answers) and count($forum->answers))
                <div class="d-flex align-items-center justify-content-between pl-4 py-4">
                    <div class="d-flex align-items-center gap-40">
                        <div class="">
                            <span class="d-block font-12 text-gray-500">{{ trans('update.replies') }}</span>
                            <span class="d-block mt-2 font-12 font-weight-bold text-dark">{{ $forum->answer_count }}</span>
                        </div>

                        <div class="">
                            <span class="d-block font-12 text-gray-500">{{ trans('update.active_users') }}</span>
                            <span class="d-block mt-2 font-12 font-weight-bold text-dark">{{ $activeUsersCount }}</span>
                        </div>
                    </div>

                    @if(!empty($forum->resolved) or true)
                        <div class="d-flex-center size-24 rounded-circle bg-primary">
                            <x-iconsax-lin-verify class="icons text-white" width="16px" height="16px"/>
                        </div>
                    @endif
                </div>

                <a href="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/answers" class="">
                    <div class="d-flex align-items-center justify-content-between bg-white rounded-8 mt-12 p-12">
                        <div class="">
                            <div class="font-12 text-gray-500">{{ trans('update.last_activity') }}</div>

                            <div class="d-flex align-items-center mt-8">
                                <div class="size-40 rounded-circle">
                                    <img src="{{ $forum->lastAnswer->user->getAvatar(30) }}" class="img-cover rounded-circle" alt="{{ $forum->lastAnswer->user->full_name }}">
                                </div>
                                <div class="ml-8">
                                    <h4 class="font-12 text-dark font-weight-bold">{{ $forum->lastAnswer->user->full_name }}</h4>
                                    <p class="font-12 font-weight-500 text-gray-500 mt-4">{{ dateTimeFormat($forum->lastAnswer->created_at, 'j M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                </a>
            @else
                <a href="{{ $course->getForumPageUrl() }}/{{ $forum->id }}/answers" class="">
                    <div class="d-flex-center flex-column text-center w-100 h-100 py-36">
                        <div class="d-flex-center size-40 rounded-12 bg-gray-200">
                            <x-iconsax-bul-messages class="icons text-primary" width="24px" height="24px"/>
                        </div>
                        <h5 class="font-14 text-gray-500 mt-8">{{ trans('update.have_an_idea') }}</h5>
                        <div class="mt-4 font-12 text-gray-500">{{ trans('update.share_it_with_others_now') }}</div>
                    </div>
                </a>
            @endif
        </div>
    </div>
</div>
