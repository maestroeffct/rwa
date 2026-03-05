<div class="container mt-64">
    <div class="d-flex-center flex-column text-center">
        <h2 class="font-32 font-weight-bold">{{ trans('update.recommended_topics') }}</h2>
        <p class="mt-4 text-gray-500">{{ trans('update.recommended_topics_hint') }}</p>
    </div>

    <div class="row">
        @foreach($recommendedTopics as $recommendedTopic)
            <div class="col-12 col-md-6 col-lg-3 mt-44">
                <div class="bg-white py-20 rounded-24">
                    <div class="d-flex-center flex-column text-center px-16">
                        <div class="forum-recommended-topics__image d-flex-center size-124 rounded-8">
                            <img src="{{ $recommendedTopic->icon }}" alt="{{ $recommendedTopic->title }}" class="img-fluid">
                        </div>

                        <h3 class="font-20 mt-16">{{ $recommendedTopic->title }}</h3>

                        <div class="mt-8 font-16 text-gray-500">{{ $recommendedTopic->subtitle }}</div>
                    </div>

                    <div class="mt-12 border-top-gray-100"></div>

                    @foreach($recommendedTopic->topics as $topic)
                        <a href="{{ $topic->getPostsUrl() }}" class="d-flex align-items-center text-dark font-14 mt-16 px-16">
                            <div class="d-flex-center size-24 rounded-circle border-gray-100">
                                <x-iconsax-lin-arrow-right-1 class="icons text-primary" width="14px" height="14px"/>
                            </div>
                            <span class="ml-8">{{ truncate($topic->title, 35) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
