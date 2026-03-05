<div class="d-flex align-items-center justify-content-between bg-white p-16 rounded-16">
    <div class="d-flex align-items-center">
        <div class="d-flex-center size-48">
            @if(!empty($quiz->icon))
                <img src="{{ $quiz->icon }}" class="img-cover rounded-12">
            @else
                <x-iconsax-bul-clipboard-tick class="icons text-success" width="32px" height="32px"/>
            @endif
        </div>

        <div class="ml-8">
            <h1 class="font-14 font-weight-bold">{{ $quiz->title }}</h1>
            <p class="mt-4 font-12 text-gray-500">{{ $webinar->title }}</p>
        </div>
    </div>

    <a href="{{ $webinar->getLearningPageUrl() }}" class="d-flex align-items-center text-gray-500 font-14">
        <x-iconsax-lin-arrow-left class="icons text-gray-500" width="16px" height="16px"/>
        <span class="ml-4">{{ trans('update.back_to_the_course') }}</span>
    </a>
</div>
