<div class="container mt-40">
    <div class="forum-hero-circles circle-2"></div>

    <div class="row">
        <div class="col-12 col-lg-6 position-relative mt-100">

            <div class="forum-hero-circles circle-1"></div>

            @if(!empty($heroSettings) and !empty($heroSettings['badge']) and !empty($heroSettings['badge']['title']) and !empty($heroSettings['badge']['color']))
                <div class="forum-extra-badge d-inline-flex-center px-16 py-8 rounded-8 font-12"
                     style="color: {{ $heroSettings['badge']['color'] }}; border-color: {{ $heroSettings['badge']['color'] }}"
                >
                    <span class="forum-extra-badge__mask" style="background-color: {{ $heroSettings['badge']['color'] }}"></span>
                    <span class="z-index-2">{{ $heroSettings['badge']['title'] }}</span>
                </div>
            @endif

            @if(!empty($heroSettings) and !empty($heroSettings['title']))
                <h1 class="forum-hero-title mt-8">{{ $heroSettings['title'] }}</h1>
            @endif

            @if(!empty($heroSettings) and !empty($heroSettings['description']))
                <div class="mt-20 font-16 text-gray-500">{!! nl2br($heroSettings['description']) !!}</div>
            @endif

            @if(!empty($heroSettings) and !empty($heroSettings['show_search']))
                <div class="forum-hero-search-box bg-white p-12 mt-20 rounded-16">
                    <form action="/forums/search" method="get">
                        <div class="form-group d-flex align-items-center m-0">
                            <input type="text" name="search" class="form-control border-0 bg-white flex-1" placeholder="{{ trans('update.search_discussions') }}"/>
                            <button type="submit" class="btn btn-primary btn-lg">{{ trans('public.search') }}</button>
                        </div>
                    </form>
                </div>
            @endif

        </div>

        <div class="col-12 col-lg-6 mt-40 mt-lg-0 position-relative">

            @if(!empty($heroSettings) and !empty($heroSettings['image']))
                <div class="forum-hero-image">
                    <img src="{{ $heroSettings['image'] }}" alt="{{ trans('update.forum') }}" class="img-cover">
                </div>
            @endif

            <div class="forum-hero-circles circle-3"></div>

        </div>
    </div>
</div>
