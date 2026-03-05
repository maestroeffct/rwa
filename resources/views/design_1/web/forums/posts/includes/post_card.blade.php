@php
    $cardUser = !empty($post) ? $post->user : $topic->creator;
    $cardUserBadges = $cardUser->getBadges();
@endphp

<div class="card-with-mask position-relative mt-28">
    <div class="mask-8-white"></div>

    <div class="topic-post-card position-relative d-flex flex-column flex-lg-row bg-white p-12 rounded-24 z-index-2">
        <div class="topic-post-stats position-relative pt-24 pb-16 px-12 rounded-12 bg-gray-100">

            @if(!empty($post) and $post->pin)
                <div class="topic-post-pined" data-tippy-content="{{ trans('update.pined') }}">
                    <x-iconsax-lin-paperclip-2 class="icons text-warning" width="20px" height="20px"/>
                </div>
            @endif

            <div class="d-flex-center flex-column text-center">
                {{-- Avatar --}}
                <div class="d-flex-center size-96 rounded-circle {{ ($cardUser->id == $topic->creator_id) ? 'bg-primary' : 'bg-gray-100' }} ">
                    <div class="d-flex-center size-80 rounded-circle bg-white">
                        <div class="size-64 rounded-circle">
                            <img src="{{ $cardUser->getAvatar(64) }}" class="img-cover rounded-circle js-user-avatar" alt="{{ $cardUser->full_name }}">
                        </div>
                    </div>
                </div>

                <h4 class="js-user-name font-14 {{ ($cardUser->id == $topic->creator_id) ? 'mt-8' : 'mt-16' }}">{{ $cardUser->full_name }}</h4>

                <div class="px-8 py-4 rounded-16 border-gray-200 font-12 text-gray-500 mt-8 backdrop-filter-blur-4">
                    @if($cardUser->isUser())
                        {{ trans('quiz.student') }}
                    @elseif($cardUser->isTeacher())
                        {{ trans('public.instructor') }}
                    @elseif($cardUser->isOrganization())
                        {{ trans('home.organization') }}
                    @elseif($cardUser->isAdmin())
                        {{ trans('panel.staff') }}
                    @endif
                </div>

                @if(!empty($cardUserBadges) and count($cardUserBadges))
                    <div class="d-flex-center flex-wrap mt-16 gap-12">
                        @foreach($cardUserBadges as $badge)
                            <div class="d-flex-center size-32 rounded-8" data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{ (!empty($badge->badge_id) ? $badge->badge->description : $badge->description) }}">
                                <img src="{{ !empty($badge->badge_id) ? $badge->badge->image : $badge->image }}" class="img-fluid rounded-8" width="32" height="32" alt="{{ !empty($badge->badge_id) ? $badge->badge->title : $badge->title }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-12">
                @if($cardUser->getTopicsPostsCount() > 0)
                    <div class="d-flex align-items-center justify-content-between mt-12 font-12 text-gray-500">
                        <span class="">{{ trans('site.posts') }}:</span>
                        <span class="font-weight-bold">{{ $cardUser->getTopicsPostsCount() }}</span>
                    </div>
                @endif

                @if($cardUser->getTopicsPostsLikesCount() > 0)
                    <div class="d-flex align-items-center justify-content-between mt-12 font-12 text-gray-500">
                        <span class="">{{ trans('update.likes') }}:</span>
                        <span class="font-weight-bold">{{ $cardUser->getTopicsPostsLikesCount() }}</span>
                    </div>
                @endif

                @if(count($cardUser->followers()))
                    <div class="d-flex align-items-center justify-content-between mt-12 font-12 text-gray-500">
                        <span class="">{{ trans('panel.followers') }}:</span>
                        <span class="font-weight-bold">{{ count($cardUser->followers()) }}</span>
                    </div>
                @endif

                <div class="d-flex align-items-center justify-content-between mt-12 font-12 text-gray-500">
                    <span class="">{{ trans('update.member_since') }}:</span>
                    <span class="font-weight-bold">{{ dateTimeFormat($cardUser->created_at,'j M Y') }}</span>
                </div>

                @if(!empty($cardUser->getCountryAndState()))
                    <div class="d-flex align-items-center justify-content-between mt-12 font-12 text-gray-500">
                        <span class="">{{ trans('update.location') }}:</span>
                        <span class="font-weight-bold">{{ $cardUser->getCountryAndState() }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="topic-post-content d-flex flex-column pl-lg-16 mt-16 mt-lg-0">

            @if(!empty($post) and !empty($post->parent))
                <div class="p-12 rounded-12 border-dashed border-gray-200 bg-white mb-16">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-32 rounded-8 bg-gray-100">
                            <x-iconsax-lin-quote-down class="icons text-gray-500" width="16px" height="16px"/>
                        </div>
                        <div class="ml-4">
                            <span class="d-block font-12 text-gray-500">{{ trans('update.reply_to') }}</span>
                            <span class="d-block mt-2 font-12 font-weight-bold">{{ $post->parent->user->full_name }}</span>
                        </div>
                    </div>

                    <div class="text-gray-500 mt-12">{!! truncate($post->parent->description, 200) !!}</div>
                </div>
            @endif

            {{-- Post Description --}}
            <div class="js-topic-post-description text-gray-500 mb-8">{!! !empty($post) ? $post->description : $topic->description !!}</div>


            <div class="mt-auto">

                {{-- Attachments --}}
                @if(!empty($post) and !empty($post->attach))
                    <div class="d-flex flex-wrap gap-8">
                        <a href="{{ $post->getAttachmentUrl($forum->slug,$topic->slug) }}" target="_blank" class="d-flex-center p-8 rounded-8 bg-white border-gray-300 font-12 text-gray-500">
                            <x-iconsax-lin-document-download class="icons text-gray-400" width="16px" height="16px"/>
                            <span class="ml-4">{{ truncate($post->getAttachmentName(), 24) }}</span>
                        </a>
                    </div>
                @elseif(empty($post) and !empty($topic->attachments) and count($topic->attachments))
                    <div class="d-flex flex-wrap gap-8 align-items-center">
                        @foreach($topic->attachments as $attachment)
                            <a href="{{ $attachment->getDownloadUrl($forum->slug,$topic->slug) }}" target="_blank" class="d-flex-center p-8 rounded-8 bg-white border-gray-300 font-12 text-gray-500">
                                <x-iconsax-lin-document-download class="icons text-gray-400" width="16px" height="16px"/>
                                <span class="ml-4">{{ truncate($attachment->getName(), 24) }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="d-flex align-items-center justify-content-between mt-16 pt-16 border-top-gray-200">
                    <span class="font-12 text-gray-500">{{ dateTimeFormat(!empty($post) ? $post->created_at : $topic->created_at,'j M Y | H:i') }}</span>

                    <div class="d-flex align-items-center gap-20">
                        @if(!empty($authUser) and !$topic->close)
                            {{-- Replay --}}
                            @if(!empty($post))
                                <button type="button" data-id="{{ $post->id }}"
                                        class="js-reply-post-btn btn-transparent d-flex-center"
                                        data-tippy-content="{{ trans('panel.reply') }}"
                                >
                                    <x-iconsax-lin-message class="icons text-gray-500" width="20px" height="20px"/>
                                </button>
                            @endif

                            {{-- Pin & Unpin --}}
                            @if(!empty($post) and $authUser->id == $topic->creator_id)
                                <button type="button" data-action="{{ $topic->getPostsUrl() }}/{{ $post->id }}/pin-toggle"
                                        class="js-btn-post-pin btn-transparent d-flex-center {{ $post->pin ? 'text-warning' : 'text-gray-500' }}"
                                        data-tippy-content="{{ trans('update.pin') }}"
                                >
                                    <x-iconsax-lin-paperclip-2 class="icons" width="20px" height="20px"/>
                                </button>
                            @endif

                            {{-- Edit --}}
                            @if($authUser->id == $cardUser->id)
                                @if(!empty($post))
                                    <button type="button" data-action="{{ $post->getEditUrl($forum->slug, $topic->slug) }}"
                                            class="js-post-edit btn-transparent d-flex-center"
                                            data-tippy-content="{{ trans('public.edit') }}"
                                    >
                                        <x-iconsax-lin-edit class="icons text-gray-500" width="20px" height="20px"/>
                                    </button>
                                @else
                                    <a href="{{ $topic->getEditUrl($forum->slug) }}"
                                       class="d-flex-center"
                                       data-tippy-content="{{ trans('public.edit') }}"
                                    >
                                        <x-iconsax-lin-edit class="icons text-gray-500" width="20px" height="20px"/>
                                    </a>
                                @endif
                            @endif

                            {{-- Report --}}
                            <button type="button" data-id="{{ !empty($post) ? $post->id : $topic->id }}" data-type="{{ !empty($post) ? 'topic_post' : 'topic' }}"
                                    class="js-topic-post-report btn-transparent d-flex-center "
                                    data-tippy-content="{{ trans('panel.report') }}"
                                    data-path="{{ $topic->getPostsUrl() }}/report-modal"
                            >
                                <x-iconsax-lin-flag class="icons text-gray-500" width="20px" height="20px"/>
                            </button>
                        @endif

                        @php
                            $isLiked = ((!empty($post) and in_array($post->id, $likedPostsIds)) or (empty($post) and $topic->liked));
                        @endphp

                        {{-- Likes --}}
                        <div class="js-topic-post-like-btn-parent d-flex align-items-center gap-4 font-12 text-gray-500">
                            <button type="button" class="d-flex-center btn-transparent {{ !empty($authUser) ? 'js-topic-post-like' : 'login-to-access' }} {{ $isLiked ? 'liked' : '' }}"
                                    data-action="{{ !empty($post) ? $post->getLikeUrl($forum->slug,$topic->slug) : $topic->getLikeUrl($forum->slug) }}"
                            >
                                <x-iconsax-lin-heart class="js-empty-like-icon icons text-gray-500 {{ $isLiked ? 'd-none' : '' }}" width="20px" height="20px"/>
                                <x-iconsax-bol-heart class="js-full-like-icon icons text-danger {{ $isLiked ? '' : 'd-none' }}" width="20px" height="20px"/>
                            </button>
                            <span class="js-like-count font-weight-bold">{{ !empty($post) ? $post->likes->count() : $topic->likes->count() }}</span>
                            <span class="">{{ trans('update.likes') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
