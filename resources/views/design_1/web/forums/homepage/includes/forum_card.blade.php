<div class="position-relative row align-items-center mt-20 z-index-2">
    <div class="col-8 col-lg-5">
        <div class="d-flex align-items-center">
            <a href="{{ $forum->getUrl() }}" class="text-dark">
                <div class="size-48 rounded-12 bg-gray-100">
                    <img src="{{ $forum->icon }}" alt="{{ $forum->title }}" class="img-cover">
                </div>
            </a>

            <div class="ml-12">
                <a href="{{ $forum->getUrl() }}" class="text-dark">
                    <h4 class="font-16 font-weight-bold text-dark">{{ $forum->title }}</h4>
                </a>

                <p class="mt-8 font-14 text-gray-500">{{ truncate($forum->description, 130) }}</p>
            </div>
        </div>
    </div>

    <div class="col-4 col-lg-3 d-flex align-items-center justify-content-end justify-content-lg-center">
        <div class="d-flex-center gap-20 gap-lg-48">
            <div class="forum-card-stats-item d-flex-center flex-column text-gray-500">
                <span class="font-14 font-weight-bold">{{ $forum->topics_count }}</span>
                <span class="font-12 mt-4">{{ trans('topics') }}</span>
            </div>

            <div class="forum-card-stats-item d-flex-center flex-column text-gray-500">
                <span class="font-14 font-weight-bold">{{ $forum->posts_count }}</span>
                <span class="font-12 mt-4">{{ trans('posts') }}</span>
            </div>
        </div>
    </div>

    @if(!empty($forum->lastTopic))
        <div class="col-12 col-lg-4 mt-12 mt-lg-0">
            <div class="d-flex align-items-center">
                <div class="size-40 rounded-circle">
                    <img src="{{ $forum->lastTopic->creator->getAvatar(40) }}" alt="{{ $forum->lastTopic->creator->full_name }}" class="img-cover rounded-circle">
                </div>

                <div class="ml-8 text-gray-500">
                    <a href="{{ $forum->lastTopic->getPostsUrl() }}" class="d-block text-dark">
                        <span class="d-block font-14">{{ truncate($forum->lastTopic->title, 26) }}</span>
                    </a>

                    <div class="d-flex align-items-center text-gray-500 mt-4 font-12">
                        <span class="font-weight-bold">{{ $forum->lastTopic->creator->full_name }}</span>
                        <span class="ml-4">{{ trans('on') }} {{ dateTimeFormat($forum->lastTopic->created_at, 'j M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

