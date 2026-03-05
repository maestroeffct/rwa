<div class="forum-topic-card position-relative">
    <div class="forum-topic-card__mask "></div>

    <div class="position-relative bg-white p-12 rounded-24 border-gray-200 z-index-3">
        <div class="row">
            <div class="col-12 col-lg-7 d-flex align-items-lg-center">
                <a href="{{ $topic->getPostsUrl() }}" class="text-dark">
                    <div class="forum-topic-card__image rounded-12 bg-gray-100 {{ (empty($topic->cover)) ? 'd-flex-center border-gray-300 border-dashed' : '' }}">
                        @if(!empty($topic->cover))
                            <img src="{{ $topic->cover }}" alt="{{ $topic->title }}" class="img-cover rounded-12">
                        @else
                            <x-iconsax-bul-note-2 class="icons text-info" width="64px" height="64px"/>
                        @endif
                    </div>
                </a>

                <div class="ml-12 d-flex flex-column h-100 w-100">
                    <a href="{{ $topic->getPostsUrl() }}" class="text-dark font-14">
                        <h4 class="font-16 font-weight-bold">{{ $topic->title }}</h4>
                    </a>

                    <p class="forum-topic-card__desc mt-8 font-14 text-gray-500 mb-8">{{ truncate(strip_tags($topic->description), 200) }}</p>

                    <div class="d-flex align-items-end justify-content-between mt-auto pb-8">

                        <div class="d-flex align-items-center">
                            <div class="size-40 rounded-circle">
                                <img src="{{ $topic->creator->getAvatar(40) }}" alt="{{ $topic->creator->full_name }}" class="img-cover rounded-circle">
                            </div>
                            <div class="ml-8">
                                <a href="{{ $topic->creator->getProfileUrl() }}" target="_blank" class="text-dark">
                                    <h4 class="font-14 font-weight-bold text-dark">{{ $topic->creator->full_name }}</h4>
                                </a>
                                <span class="d-block font-12 text-gray-500">{{ dateTimeFormat($topic->created_at, 'j M Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            @if($topic->pin)
                                <x-iconsax-lin-paperclip-2 class="icons text-warning" width="20px" height="20px" data-tippy-content="{{ trans('pin') }}"/>
                            @endif

                            @if($topic->close)
                                <x-iconsax-lin-lock class="icons text-danger ml-16" width="20px" height="20px" data-tippy-content="{{ trans('closed') }}"/>
                            @endif

                            @if($topic->private)
                                <x-iconsax-lin-shield-security class="icons text-primary ml-16" width="20px" height="20px" data-tippy-content="{{ trans('private') }}"/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5 mt-20 mt-lg-0">
                <div class="d-flex flex-column w-100 h-100 rounded-12 bg-gray-100 p-16">
                    <div class="d-flex align-items-center mb-16">
                        <div class="">
                            <span class="d-block font-12 text-gray-500">{{ trans('update.replies') }}</span>
                            <span class="d-block font-14 font-weight-bold mt-4">{{ $topic->posts_count ?? 0 }}</span>
                        </div>

                        <div class="mx-20 mx-lg-40">
                            <span class="d-block font-12 text-gray-500">{{ trans('update.likes') }}</span>
                            <span class="d-block font-14 font-weight-bold mt-4">{{ $topic->likes_count ?? 0 }}</span>
                        </div>

                        <div class="">
                            <span class="d-block font-12 text-gray-500">{{ trans('update.active_users') }}</span>
                            <span class="d-block font-14 font-weight-bold mt-4">{{ $topic->visits_count ?? 0 }}</span>
                        </div>
                    </div>


                    <a href="{{ $topic->getPostsUrl() }}" class="">
                        <div class="d-flex align-items-center justify-content-between px-12 rounded-8 bg-white mt-auto {{ (!empty($topic->lastActivity)) ? 'py-12' : 'py-24' }}">
                            @if(!empty($topic->lastActivity))
                                <div class="">
                                    <span class="d-block font-12 text-gray-500">{{ trans('update.last_activity') }}</span>

                                    <div class="d-flex align-items-center mt-8">
                                        <div class="size-40 rounded-circle">
                                            <img src="{{ $topic->lastActivity->user->getAvatar(40) }}" alt="{{ $topic->lastActivity->user->full_name }}" class="img-cover rounded-circle">
                                        </div>

                                        <div class="ml-8">
                                            <h4 class="font-14 font-weight-bold text-dark">{{ $topic->lastActivity->user->full_name }}</h4>
                                            <span class="d-block font-12 text-gray-500 mt-4">{{ dateTimeFormat($topic->lastActivity->created_at, 'j M Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                                        <x-iconsax-bul-messages class="icons text-primary" width="24px" height="24px"/>
                                    </div>
                                    <div class="ml-8">
                                        <h4 class="font-14 font-weight-bold text-dark">{{ trans('update.have_an_idea') }}</h4>
                                        <span class="d-block font-12 text-gray-500 mt-4">{{ trans('update.share_it_with_others_now') }}</span>
                                    </div>
                                </div>
                            @endif

                            <x-iconsax-lin-arrow-right-1 class="icons text-gray-500" width="20px" height="20px"/>
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>

</div>
