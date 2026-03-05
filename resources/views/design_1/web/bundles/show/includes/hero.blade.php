<div class="course-hero d-flex flex-column justify-content-end rounded-32 px-20 bg-gray-200">
    <div class="course-hero__mask rounded-32"></div>

    <img src="{{ $bundle->getImageCover() }}" class="course-hero__cover-img img-cover rounded-32" alt="{{ $bundle->title }}"/>

    <div class="course-hero__content position-relative z-index-3">
        @if(!empty($bundle->category))
            <div class="d-flex align-items-center text-white opacity-50">
                <a href="/bundles" class="text-white">{{ trans('update.bundles') }}</a>
                <x-iconsax-lin-arrow-right-1 class="icons text-white mx-2" width="16px" height="16px"/>
                <a href="{{ $bundle->category->getUrl() }}" class="text-white">{{ $bundle->category->title }}</a>
            </div>
        @endif

        <div class="d-flex align-items-center flex-wrap gap-12 mt-4">
            <h1 class="course-hero__title font-32 font-weight-bold text-white text-ellipsis">{{ $bundle->title }}</h1>

            {{-- Badges --}}
            <div class="d-flex align-items-center gap-12">
                {{-- Top Seller --}}
                <div class="d-flex-center p-4 pr-8 rounded-32 bg-accent">
                    <x-iconsax-bul-moneys class="icons text-white" width="20px" height="20px"/>
                    <span class="ml-4 font-12 text-white">Top Seller</span>
                </div>
            </div>
        </div>

        @if(!empty($bundle->summary))
            <div class="mt-8 text-white opacity-50">{!! nl2br($bundle->summary) !!}</div>
        @endif

        <div class="d-flex align-items-center flex-wrap gap-24 mt-12">
            {{-- Rate --}}
            @include('design_1.web.components.rate', [
                  'rate' => $bundle->getRate(),
                 'rateCount' => $bundle->getRateCount(),
                 'rateClassName' => ''
             ])

            {{-- Students --}}
            <div class="d-flex align-items-center font-12 text-white">
                <x-iconsax-lin-teacher class="icons text-white" width="16px" height="16px"/>
                <span class="mx-4 font-weight-bold">{{ $bundle->sales_count }}</span>
                <span class="opacity-50">{{ trans('quiz.students') }}</span>
            </div>

            {{-- Lectures --}}
            <div class="d-flex align-items-center font-12 text-white">
                <x-iconsax-lin-note-2 class="icons text-white" width="16px" height="16px"/>
                <span class="mx-4 font-weight-bold">{{ $bundle->bundleWebinars->count() }}</span>
                <span class="opacity-50">{{ trans('update.courses') }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-20">
            <div class="d-flex align-items-center">
                <div class="size-40 rounded-circle">
                    <img src="{{ $bundle->teacher->getAvatar(40) }}" class="img-cover rounded-circle" alt="{{ $bundle->teacher->full_name }}">
                </div>

                <div class="ml-8">
                    <a href="{{ $bundle->teacher->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-white">{{ $bundle->teacher->full_name }}</a>
                    <p class="mt-2 font-12 text-white">{{ $bundle->teacher->role->caption }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
