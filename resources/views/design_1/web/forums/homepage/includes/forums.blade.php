<div class="container mt-64">
    <div class="d-flex-center flex-column text-center">
        <h2 class="font-32 font-weight-bold">{{ trans('update.forums') }}</h2>
        <p class="mt-4 text-gray-500">{{ trans('update.browse_different_forums_and_get_involved') }}</p>
    </div>

    @foreach($forums as $forum)
        <div class="forum-card position-relative {{ $loop->first ? 'mt-24' : 'mt-32' }}">
            <div class="forum-card__mask"></div>

            <div class="position-relative card-before-line bg-white rounded-24 py-16 px-8 z-index-2">
                <a href="{{ $forum->getUrl() }}" class="text-dark">
                    <h3 class="font-16 font-weight-bold text-dark">{{ $forum->title }}</h3>
                </a>

                <div class="px-8">
                    {{-- Foreach Sub Forums Or Self --}}
                    @if(!empty($forum->subForums) and count($forum->subForums))
                        @foreach($forum->subForums as $subForum)
                            @include('design_1.web.forums.homepage.includes.forum_card',['forum' => $subForum])
                        @endforeach
                    @else
                        @include('design_1.web.forums.homepage.includes.forum_card',['forum' => $forum])
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
