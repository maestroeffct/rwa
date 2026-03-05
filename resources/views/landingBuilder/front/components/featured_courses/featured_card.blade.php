@if(!empty($featured['course']))
    @php
        $featuredCourseItem = \App\Models\Webinar::query()->where('id', $featured['course'])->where('status', 'active')->first();
    @endphp

    @if(!empty($featuredCourseItem))
        <div class="featured-courses-section__item-card position-relative rounded-32 bg-gray-100">
            <a href="{{ $featuredCourseItem->getUrl() }}" class="text-decoration-none d-block">
                @if(!empty($featured['cover_image']))
                    <img src="{{ $featured['cover_image'] }}" alt="cover" class="img-cover rounded-32">
                @endif

                @if(!empty($featured['overlay_image']) and empty($disableOverlayImage))
                    <div class="featured-courses-section__item-card-overlay-image d-flex-center">
                        <img src="{{ $featured['overlay_image'] }}" alt="overlay" class="img-fluid">
                    </div>
                @endif
            </a>

            <div class="featured-courses-section__item-card-course-box d-flex flex-column bg-white rounded-16 p-16 text-left">
                <a href="{{ $featuredCourseItem->getUrl() }}" class="text-decoration-none">
                    <h3 class="font-24 text-dark">{{ $featuredCourseItem->title }}</h3>
                </a>

                @include('design_1.web.components.rate', ['rate' => $featuredCourseItem->getRate(), 'showRateStars' => true, 'rateClassName' => 'mt-16'])

                <div class="d-flex align-items-center gap-8 mt-16">
                    <a href="{{ $featuredCourseItem->teacher->getProfileUrl() }}" target="_blank" class="size-48 rounded-circle bg-gray-100">
                        <img src="{{ $featuredCourseItem->teacher->getAvatar(48) }}" alt="{{ $featuredCourseItem->teacher->full_name }}" class="img-cover rounded-circle">
                    </a>
                    <div class="">
                        <a href="{{ $featuredCourseItem->teacher->getProfileUrl() }}" target="_blank" class="">
                            <h5 class="font-16 text-dark">{{ $featuredCourseItem->teacher->full_name }}</h5>
                        </a>

                        <div class="d-inline-flex align-items-center gap-4 mt-2 font-14 text-gray-500">
                            <span class="">{{ trans('public.in') }}</span>
                            <a href="{{ $featuredCourseItem->category->getUrl() }}" target="_blank" class="font-14 text-gray-500">{{ $featuredCourseItem->category->title }}</a>
                        </div>
                    </div>
                </div>

                <a href="{{ $featuredCourseItem->getUrl() }}" class="text-decoration-none">
                    <div class="featured-courses-section__item-card-course-box-description p-16 mt-16 rounded-16 border-gray-100" style="cursor: pointer;">
                        <div class="text-gray-500 font-16">{{ $featuredCourseItem->summary }}</div>

                        @if(!empty($featured['checked_items']) and is_array($featured['checked_items']))
                            <div class="mt-16">
                                <h4 class="font-16 text-dark">{{ trans('update.this_course_includes') }}:</h4>

                                @foreach($featured['checked_items'] as $checkedItem)
                                    <div class="d-flex align-items-center mt-12">
                                        <x-tick-icon class="icons text-success" width="16px" height="16px"/>
                                        <span class="ml-4 font-14 text-gray-500">{{ $checkedItem }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </a>

                <div class="d-flex align-items-center justify-content-between mt-auto">
                    <a href="{{ $featuredCourseItem->getUrl() }}" class="text-decoration-none">
                        <div class="d-flex-center p-8 rounded-8 bg-gray-100 text-gray-500" style="cursor: pointer;">
                            <x-iconsax-lin-clock-1 class="icons text-gray-500" width="20px" height="20px"/>
                            <span class="ml-4vfont-16">{{ convertMinutesToHourAndMinute($featuredCourseItem->duration) }} {{ trans('home.hours') }}</span>
                        </div>
                    </a>

                    <a href="{{ $featuredCourseItem->getUrl() }}" class="text-decoration-none">
                        <div class="d-flex align-items-end gap-12 font-24 text-primary" style="cursor: pointer;">
                            @if(!empty($featuredCourseItem->price) and $featuredCourseItem->price > 0)
                                @if($featuredCourseItem->bestTicket() < $featuredCourseItem->price)
                                    <span class="text-decoration-line-through text-gray-500 font-16">{{ ($featuredCourseItem->bestTicket() > 0) ? handlePrice($featuredCourseItem->bestTicket(), true, true, false, null, true) : trans('public.free') }}</span>
                                    <span class="font-weight-bold">{{ handlePrice($featuredCourseItem->price, true, true, false, null, true) }}</span>
                                @else
                                    <span class="font-weight-bold">{{ handlePrice($featuredCourseItem->price, true, true, false, null, true) }}</span>
                                @endif
                            @else
                                <span class="font-weight-bold">{{ trans('public.free') }}</span>
                            @endif
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
@endif
