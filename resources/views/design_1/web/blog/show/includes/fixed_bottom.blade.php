<div class="post-bottom-fixed-card bg-white">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between h-100">
        <div class="d-flex align-items-center">
            <div class="post-bottom-fixed-card__post-img rounded-8">
                <img src="{{ $post->image }}" class="img-cover rounded-8" alt="{{ $post->title }}">
            </div>
            <div class="ml-8">
                <div class="font-12 text-gray-500">{{ trans('update.you_are_studing') }}</div>
                <div class="mt-4 font-14 font-weight-bold">{{ $post->title }}</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-24 mt-16 mt-lg-0">

            <div class="d-flex align-items-center">
                <a href="{{ $post->author->getProfileUrl() }}" target="_blank" class="d-flex align-items-center">
                    <div class="size-40 rounded-circle">
                        <img src="{{ $post->author->getAvatar(40) }}" alt="{{ $post->author->full_name }}" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.written_by') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500">{{ $post->author->full_name }}</span>
                    </div>
                </a>
            </div>

            @if(!empty( $post->study_time ))
                <div class="d-flex align-items-center pl-24 border-left-gray-200">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <x-iconsax-lin-clock-1 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.study_time') }}</span>
                        <span class="d-block font-14 font-weight-bold text-gray-500">{{ $post->study_time }} {{ trans('update.mins') }}</span>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<div class="post-bottom-fixed-card__progress">
    <div class="progress-line"></div>
</div>
