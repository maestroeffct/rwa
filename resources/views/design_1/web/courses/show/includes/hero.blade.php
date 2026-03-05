<div class="course-hero d-flex flex-column justify-content-end rounded-32 px-20 bg-gray-200">
    <div class="course-hero__mask rounded-32"></div>

    <img src="{{ $course->getImageCover() }}" class="course-hero__cover-img img-cover rounded-32" alt="{{ $course->title }}"/>

    <div class="course-hero__content position-relative z-index-3">
        @if(!empty($course->category))
            <div class="d-flex align-items-center text-white opacity-50">
                <a href="/classes" class="text-white">{{ trans('update.courses') }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-white mx-2" width="16px" height="16px"/>
                <a href="{{ $course->category->getUrl() }}" class="text-white">{{ $course->category->title }}</a>
            </div>
        @endif

        <div class="d-flex align-items-center flex-wrap gap-12 mt-4">
            <h1 class="course-hero__title font-32 font-weight-bold text-white text-ellipsis">{{ $course->title }}</h1>

            {{-- Badges --}}
            <div class="d-flex flex-wrap align-items-center gap-12">
                {{-- Featured --}}
                @if(!empty($course->feature))
                    <div class="d-flex-center p-4 pr-8 rounded-32 bg-success">
                        <x-iconsax-bul-verify class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 font-12 text-white">{{ trans('update.featured') }}</span>
                    </div>
                @endif

                {{-- Top Seller --}}
                @foreach($course->allBadges() as $courseBadge)
                    <div class="d-flex-center gap-4 p-4 pr-8 rounded-32" style="background-color: {{ $courseBadge->background }}; color: {{ $courseBadge->color }};">
                        @if(!empty($courseBadge->icon))
                            <div class="size-24">
                                <img src="{{ $courseBadge->icon }}" alt="{{ $courseBadge->title }}" class="img-cover">
                            </div>
                        @endif
                        <span class="font-12">{{ $courseBadge->title }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        @if(!empty($course->summary))
            <div class="mt-8 text-white opacity-50">{!! nl2br($course->summary) !!}</div>
        @endif

        <div class="d-flex align-items-center flex-wrap gap-24 mt-12">
            {{-- Rate --}}
            @include('design_1.web.components.rate', [
                  'rate' => $course->getRate(),
                 'rateCount' => $course->getRateCount(),
                 'rateClassName' => ''
             ])

            {{-- Students --}}
            <div class="d-flex align-items-center font-12 text-white">
                <x-iconsax-lin-teacher class="icons text-white" width="16px" height="16px"/>
                <span class="mx-4 font-weight-bold">{{ $course->getSalesCount() }}</span>
                <span class="opacity-50">{{ trans('quiz.students') }}</span>
            </div>

            {{-- Lectures --}}
            <div class="d-flex align-items-center font-12 text-white">
                <x-iconsax-lin-note-2 class="icons text-white" width="16px" height="16px"/>
                <span class="mx-4 font-weight-bold">{{ $course->getAllLessonsCount() }}</span>
                <span class="opacity-50">{{ trans('update.lectures') }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-20">
            <div class="d-flex align-items-center">
                <div class="size-40 rounded-circle">
                    <img src="{{ $course->teacher->getAvatar(40) }}" class="img-cover rounded-circle" alt="{{ $course->teacher->full_name }}">
                </div>

                <div class="ml-8">
                    <a href="{{ $course->teacher->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-white">{{ $course->teacher->full_name }}</a>
                    <p class="mt-2 font-12 text-white">{{ $course->teacher->role->caption }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
