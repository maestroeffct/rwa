@php
    $price = (!empty($instructor->meeting)) ? $instructor->meeting->amount : 0;
    $discount = (!empty($price) and !empty($instructor->meeting) and !empty($instructor->meeting->discount) and $instructor->meeting->discount > 0) ? $instructor->meeting->discount : 0;
    $instructorRates = $instructor->rates(true);
@endphp

<div class="instructor-finder__user-card d-flex align-items-start flex-column flex-lg-row gap-24 bg-white p-16 rounded-24 mb-20">
    <a href="{{ $instructor->getProfileUrl() }}" class="text-decoration-none d-flex flex-column flex-1 h-100" target="_blank">
        <div class="d-flex align-items-center justify-content-between mb-16">
            <div class="d-flex align-items-center">
                <div class="position-relative size-64 rounded-circle bg-gray-100">
                    <img src="{{ $instructor->getAvatar(64) }}" alt="{{ $instructor->full_name }}" class="img-cover rounded-circle">

                    @if($instructor->verified)
                        <div class="instructor-finder__user-card-verified-badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="{{ trans('public.verified') }}">
                            <x-tick-icon class="icons text-white" />
                        </div>
                    @endif
                </div>

                <div class="ml-8">
                    <h6 class="font-16 font-weight-bold text-dark">{{ $instructor->full_name }}</h6>
                    <p class="mt-4 font-14 text-gray-500">{{ $instructor->bio }}</p>
                </div>
            </div>

            @include('design_1.web.components.rate', ['rate' => $instructorRates['rate'], 'rateCount' => $instructorRates['count']])
        </div>

        <div class="mb-16 font-14 text-gray-500">{{ truncate($instructor->about, 380) }}</div>

        @if(!empty($instructor->occupations) and count($instructor->occupations))
            <div class="d-flex align-items-center flex-wrap gap-12 mb-16">
                @foreach($instructor->occupations->take(10) as $occupation)
                    @if(!empty($occupation->category))
                        <div class="d-flex-center p-10 rounded-8 bg-gray-100 text-center font-14 text-gray-500">{{ $occupation->category->title }}</div>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="d-flex align-items-lg-center flex-wrap gap-32 mt-auto p-12 rounded-12 border-gray-200 border-dashed">
            {{-- Member Since --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <div class="font-12 text-gray-400">{{ trans('update.member_since') }}</div>
                    <div class="font-14 font-weight-bold text-gray-500">{{ dateTimeFormat($instructor->created_at, 'j M Y') }}</div>
                </div>
            </div>

            {{-- Courses --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-video-play class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <div class="font-12 text-gray-400">{{ trans('update.courses') }}</div>
                    <div class="font-14 font-weight-bold text-gray-500">{{ $instructor->webinars_count }}</div>
                </div>
            </div>

            {{-- Total Meetings --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-device-message class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <div class="font-12 text-gray-400">{{ trans('panel.total_meetings') }}</div>
                    <div class="font-14 font-weight-bold text-gray-500">{{ $instructor->total_meetings ?? 0 }}</div>
                </div>
            </div>

            {{-- Tutoring Hours --}}
            <div class="d-flex align-items-center">
                <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                    <x-iconsax-lin-clock-1 class="icons text-gray-500" width="20px" height="20px"/>
                </div>
                <div class="ml-8">
                    <div class="font-12 text-gray-400">{{ trans('update.tutoring_hours') }}</div>
                    <div class="font-14 font-weight-bold text-gray-500">{{ $instructor->getTotalHoursTutoring() }}</div>
                </div>
            </div>
        </div>
    </a>

    <div class="position-relative actions-box bg-gray-100 rounded-12 p-16">
        <div class="d-flex-center flex-column text-center mt-36">
            @if(!empty($instructor->meeting) and !empty($instructor->meeting->meetingTimes) and count($instructor->meeting->meetingTimes))
                @if(!empty($discount))
                    <div class="discount-box d-flex-center px-8 py-4 bg-badge rounded-16 font-12 text-white">{{ trans('update.percent_off', ['percent' => $discount]) }}</div>
                @endif

                <div class="d-flex-center bg-primary-20 rounded-8 size-48">
                    <x-iconsax-bul-device-message class="icons text-primary" width="24px" height="24px"/>
                </div>

                <div class="font-14 font-weight-bold mt-12">{{ trans('public.book_a_meeting') }}</div>

                @if(!empty($price) and $price > 0)
                    <div class="d-flex align-items-center mt-12">
                        <span class="font-16 font-weight-bold text-primary">{{ handlePrice(!empty($discount) ? ($price - ($price * $discount / 100)) : $price) }}</span>
                        <span class="font-14 text-gray-500">/{{ trans('update.hour') }}</span>
                    </div>

                    @if(!empty($discount))
                        <span class="font-14 text-gray-500 text-decoration-line-through mt-8">{{ handlePrice($price) }}</span>
                    @endif
                @else
                    <span class="font-14 font-weight-bold text-primary mt-12">{{ trans('public.free') }}</span>
                @endif

                <div class="d-flex align-items-center gap-12 mt-16">
                    <a href="{{ $instructor->getProfileUrl() }}" class="d-flex-center size-36 rounded-circle bg-gray-200 bg-hover-gray-300" target="_blank" data-tippy-content="{{ trans('public.view_profile') }}">
                        <x-iconsax-bol-frame class="icons text-gray-500" width="20px" height="20px"/>
                    </a>

                    <a href="{{ $instructor->getProfileUrl() }}?tab=appointments" class="d-flex-center size-36 rounded-circle bg-primary" target="_blank" data-tippy-content="{{ trans('public.book_a_meeting') }}">
                        <x-iconsax-bol-calendar-2 class="icons text-white" width="20px" height="20px"/>
                    </a>
                </div>
            @else
                <div class="d-flex-center bg-gray-400-20 rounded-8 size-48">
                    <x-iconsax-bul-teacher class="icons text-gray-500" width="24px" height="24px"/>
                </div>

                <div class="font-12 font-weight-bold mt-12">{{ trans('public.view_profile') }}</div>

                <div class="font-12 text-gray-500 mt-8">{{ trans('update.the_instructor_is_unavailable_for_meetings') }}</div>

                <div class="mt-16">
                    <a href="{{ $instructor->getProfileUrl() }}" class="d-flex-center size-36 rounded-circle bg-gray-200 bg-hover-gray-300" target="_blank" data-tippy-content="{{ trans('public.view_profile') }}">
                        <x-iconsax-bol-frame class="icons text-gray-500" width="20px" height="20px"/>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
