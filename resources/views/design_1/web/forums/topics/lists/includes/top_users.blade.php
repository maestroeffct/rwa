<div class="card-with-mask position-relative mb-28">
    <div class="mask-8-white"></div>

    <div class="position-relative bg-white rounded-24 py-16 z-index-2">
        <div class="card-before-line px-16">
            <h3 class="font-14">{{ trans('update.top_users') }}</h3>
        </div>

        <div class="px-16">
            @foreach($topUsers as $topUser)
                <div class="d-flex align-items-center mt-16">
                    <div class="size-40 rounded-circle">
                        <img src="{{ $topUser->getAvatar(40) }}" alt="{{ $topUser->full_name }}" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <a href="{{ $topUser->getProfileUrl() }}" target="_blank" class=" text-dark">
                            <span class="font-12 font-weight-bold text-dark">{{ $topUser->full_name }}</span>
                        </a>

                        <div class="d-flex align-items-center gap-12 mt-8 font-12 text-gray-500">
                            <span class="">{{ trans('update.n_topics',['count' => $topUser->topics]) }}</span>
                            <span class="">{{ trans('update.n_posts',['count' => $topUser->posts]) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
