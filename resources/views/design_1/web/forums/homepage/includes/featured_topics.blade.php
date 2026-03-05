<div class="container mt-64">
    <div class="d-flex-center flex-column text-center">
        <h2 class="font-32 font-weight-bold">{{ trans('update.featured_topics') }}</h2>
        <p class="mt-4 text-gray-500">{{ trans('update.browse_different_forums_and_get_involved') }}</p>
    </div>

    <div class="forum-featured-topic position-relative mt-24">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="forum-featured-topic__mask"></div>

                <div class="position-relative z-index-2">
                    @foreach($featuredTopics as $featuredTopic)
                        <div class="forum-featured-topic-card position-relative {{ !$loop->first ? 'mt-32' : '' }}">
                            <div class="forum-featured-topic-card__mask"></div>

                            <div class="d-flex align-items-center position-relative p-16 rounded-24 bg-white z-index-2">
                                <div class="card-icon d-flex-center size-88 rounded-16 bg-gray-100">
                                    <img src="{{ $featuredTopic->icon }}" alt="{{ $featuredTopic->topic->title }}" class="img-cover rounded-16">
                                </div>

                                <div class="ml-12">
                                    <a href="{{ $featuredTopic->topic->getPostsUrl() }}" class="">
                                        <h4 class="font-16 font-weight-bold text-dark">{{ $featuredTopic->topic->title }}</h4>
                                    </a>

                                    <p class="mt-4 font-14 text-gray-500">{!! truncate(strip_tags($featuredTopic->topic->description),167) !!}</p>

                                    @php
                                        $participatesUsers = $featuredTopic->topic->getParticipatesUsers(2);
                                    @endphp

                                    <div class="d-flex align-items-center mt-16">

                                        @if(!empty($participatesUsers['count']))
                                            <div class="d-flex align-items-center overlay-avatars overlay-avatars-12 mr-8">
                                                @foreach($participatesUsers['users'] as $participatesUser)
                                                    <div class="overlay-avatars__item size-28 rounded-circle border-2 border-white">
                                                        <img src="{{ $participatesUser->getAvatar(28) }}" alt="{{ $participatesUser->full_name }}" class="img-cover rounded-circle">
                                                    </div>
                                                @endforeach

                                                @if($participatesUsers['count'] - count($participatesUsers['users']) > 0)
                                                    <div class="overlay-avatars__count size-28 rounded-circle d-flex-center font-12 text-gray-500 border-2 border-white bg-gray-100">
                                                        +{{ $participatesUsers['count'] - count($participatesUsers['users']) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="text-gray-500 font-14">{{ trans('public.created_by') }} <span class="font-weight-bold">{{ $featuredTopic->topic->creator->full_name }}</span></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        @if(!empty($forumImagesSettings) and !empty($forumImagesSettings['featured_topics_left_float_image']))
            <div class="forum-featured-topics-left-float-image">
                <img src="{{ $forumImagesSettings['featured_topics_left_float_image'] }}" alt="{{ trans('update.featured_topics_left_float_image') }}" class="img-fluid">
            </div>
        @endif

        @if(!empty($forumImagesSettings) and !empty($forumImagesSettings['featured_topics_right_float_image']))
            <div class="forum-featured-topics-right-float-image">
                <img src="{{ $forumImagesSettings['featured_topics_right_float_image'] }}" alt="{{ trans('update.featured_topics_right_float_image') }}" class="img-fluid">
            </div>
        @endif

    </div>


</div>
