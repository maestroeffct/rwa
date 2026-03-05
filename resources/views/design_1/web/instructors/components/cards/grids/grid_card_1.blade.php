@php
    $canReserve = false;
    if(!empty($instructor->meeting) and !$instructor->meeting->disabled and !empty($instructor->meeting->meetingTimes) and $instructor->meeting->meeting_times_count > 0) {
        $canReserve = true;
    }
@endphp

<div class="instructor-card position-relative">
    <div class="instructor-card__mask"></div>

    <div class="position-relative z-index-2 d-flex align-items-start bg-white p-16 rounded-32">
        <a href="{{ $instructor->getProfileUrl() }}" class="flex-1">
            <div class="d-flex align-items-center bg-gray-100 p-24 rounded-24 w-100">
                <div class="position-relative size-64 rounded-circle bg-gray-100">
                    <img src="{{ $instructor->getAvatar(64) }}" alt="{{ $instructor->full_name }}" class="img-cover rounded-circle">

                    {{-- Rate --}}
                    <div class="instructor-card__rate d-flex-center gap-4 text-center bg-white p-4 pr-8 rounded-32">
                        <x-iconsax-bol-star-1 class="icons text-warning" width="14px" height="14px"/>
                        <span class="font-12 font-weight-bold text-dark">{{ $instructor->rates() }}</span>
                    </div>

                    @if($instructor->verified)
                        <div class="instructor-card__verified-badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="{{ trans('public.verified') }}">
                            <x-tick-icon class="icons text-white"/>
                        </div>
                    @endif
                </div>

                <div class="ml-12">
                    <h6 class="font-16 text-dark">{{ truncate($instructor->full_name, 25) }}</h6>
                    <div class="font-14 mt-8 text-gray-500">{{ (!empty($instructor->bio)) ? truncate($instructor->bio, 29) : "" }}</div>
                </div>
            </div>
        </a>

        <div class="d-flex flex-column gap-24 py-20 pl-24 pr-8">
            @if($canReserve)
                <a href="/users/{{ $instructor->username }}/meetings" target="_blank" class="" data-tippy-content="{{ trans('public.reserve_a_meeting') }}">
                    <x-iconsax-bul-video class="icons text-gray-500" width="24px" height="24px"/>
                </a>
            @endif

            <a href="{{ $instructor->getProfileUrl() }}" target="_blank" class="" data-tippy-content="{{ trans('public.view_profile') }}">
                <x-iconsax-bul-profile class="icons text-gray-500" width="24px" height="24px"/>
            </a>
        </div>
    </div>
</div>
