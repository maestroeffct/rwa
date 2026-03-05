<div class="card-with-mask position-relative">
    <div class="mask-8-white"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-16 rounded-24">
        <div class="">
            <div class="d-flex align-items-center">
                <a href="/" class="text-gray-500">{{ getPlatformName() }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-gray-400 mx-4" width="14px" height="14px"/>
                <a href="/forums" class="text-gray-500">{{ trans('update.forum') }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-gray-400 mx-4" width="14px" height="14px"/>
                <span class="text-gray-500">{{ $forum->title }}</span>
            </div>

            <h1 class="font-16 mt-8">{{ $topic->title }}</h1>

            <div class="d-flex align-items-center mt-24">
                <div class="size-40 rounded-circle">
                    <img src="{{ $topic->creator->getAvatar(40) }}" alt="{{ $topic->creator->full_name }}" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <a href="{{ $topic->creator->getProfileUrl() }}" target="_blank" class="text-dark">
                        <h6 class="font-14">{{ $topic->creator->full_name }}</h6>
                    </a>

                    <span class="d-block font-12 text-gray-500">{{ dateTimeFormat($topic->created_at, 'j M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="d-flex gap-16 mt-16 mt-lg-0">
            <div class="d-flex flex-column align-items-lg-end justify-content-between flex-1">
                <div class="d-flex justify-content-end">
                    <button type="button" data-action="{{ $topic->getBookmarkUrl() }}" class="{{ !empty($authUser) ? 'js-topic-bookmark' : 'login-to-access' }} d-flex-center btn-transparent {{ $topic->bookmarked ? 'text-primary' : 'text-gray-500' }}" data-tippy-content="{{ trans('update.bookmark') }}">
                        <x-iconsax-lin-frame-4 class="icons" width="24px" height="24px"/>
                    </button>
                </div>

                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-8 gap-lg-24 mt-auto">
                    <div class="d-flex align-items-center gap-4 font-12">
                        <x-iconsax-lin-messages class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-weight-bold text-dark">{{ $topic->posts()->count() }}</span>
                        <span class="text-gray-500">{{ trans('update.posts') }}</span>
                    </div>

                    <div class="d-flex align-items-center gap-4 font-12">
                        <x-iconsax-lin-profile-2user class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="font-weight-bold text-dark">{{ $topic->members_count }}</span>
                        <span class="text-gray-500">{{ trans('update.active_members') }}</span>
                    </div>
                </div>
            </div>

            <div class="topic-posts-cover-card rounded-16 bg-gray-100 {{ empty($topic->cover) ? 'd-flex-center border-dashed border-gray-300' : '' }}">
                @if(!empty($topic->cover))
                    <img src="{{ $topic->cover }}" alt="{{ $topic->title }}" class="img-cover rounded-16">
                @else
                    <x-iconsax-bul-note-2 class="icons text-info" width="36px" height="36px"/>
                @endif
            </div>
        </div>
    </div>


</div>
