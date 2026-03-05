<div class="card-with-mask position-relative mb-28">
    <div class="mask-8-white"></div>

    <div class="position-relative bg-white rounded-24 py-16 z-index-2">
        <div class="card-before-line px-16">
            <h3 class="font-14">{{ trans('update.popular_posts') }}</h3>
        </div>

        <div class="px-16">
            @foreach($popularTopics as $popularTopic)
                <div class="d-flex align-items-center mt-16">
                    <div class="size-48 rounded-8 {{ (empty($popularTopic->cover)) ? 'd-flex-center border-gray-300 border-dashed' : '' }}">
                        @if(!empty($popularTopic->cover))
                            <img src="{{ $popularTopic->cover }}" alt="{{ $popularTopic->title }}" class="img-cover rounded-8">
                        @else
                            <x-iconsax-bul-note-2 class="icons text-info" width="28px" height="28px"/>
                        @endif
                    </div>
                    <div class="ml-8">
                        <a href="{{ $popularTopic->getPostsUrl() }}" class=" text-dark">
                            <span class="font-12 font-weight-bold text-dark">{{ $popularTopic->title }}</span>
                        </a>

                        <span class="d-block font-12 mt-8 text-gray-500">{{ dateTimeFormat($popularTopic->created_at, 'j M Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
