<div class="course-right-side-section position-relative mt-28">
    <div class="course-right-side-section__mask"></div>

    <div class="position-relative bg-white rounded-24 p-16 z-index-2">

        @if(!empty($webinarPartnerTeacher))
            <span class="course-right-side__teacher-invited py-4 px-8 rounded-32 bg-primary font-12 text-white">{{ trans('update.invited') }}</span>
        @endif


        <div class="d-flex align-items-center">
            <div class="position-relative size-64 rounded-circle">
                <img src="{{ $userRow->getAvatar(64) }}" alt="{{ $userRow->full_name }}" class="img-cover rounded-circle">

                @if($userRow->verified)
                    <div class="course-right-side__teacher-verified-badge d-flex-center rounded-circle size-16 p-2 bg-primary" data-tippy-content="{{ trans('public.verified') }}">
                        <x-tick-icon class="icons text-white" />
                    </div>
                @endif
            </div>

            <div class="ml-8">
                <a href="{{ $userRow->getProfileUrl() }}" target="_blank" class="">
                    <div class="d-block font-weight-bold text-dark">{{ $userRow->full_name }}</div>
                </a>

                <p class="mt-2 font-12 text-gray-500">{{ $userRow->bio }}</p>
            </div>
        </div>

        @php
            $userRowRates = $userRow->rates(true);
        @endphp

        <div class="position-relative d-flex align-items-center flex-wrap gap-12 mt-32 pt-36 pr-16 pl-20 pb-20 rounded-12 border-gray-200">

            <div class="course-right-side__teacher-rate-card p-8 rounded-24 bg-gray-100">
                @include('design_1.web.components.rate', [
                        'rate' => $userRowRates['rate'],
                        'rateCount' => $userRowRates['count'],
                        'rateClassName' => '',
                    ])
            </div>

            @foreach($userRow->getBadges() as $userBadge)
                <div class="size-32 rounded-8" data-toggle="tooltip" data-placement="bottom" data-html="true" title="{!! (!empty($userBadge->badge_id) ? nl2br($userBadge->badge->description) : nl2br($userBadge->description)) !!}">
                    <img src="{{ !empty($userBadge->badge_id) ? $userBadge->badge->image : $userBadge->image }}" class="img-cover rounded-8" alt="{{ !empty($userBadge->badge_id) ? $userBadge->badge->title : $userBadge->title }}">
                </div>
            @endforeach
        </div>

        <div class="d-flex align-items-center gap-16 mt-16">
            <a href="{{ $userRow->getProfileUrl() }}" target="_blank" class="btn btn-primary btn-lg flex-1">{{ trans('public.profile') }}</a>

            @if($userRow->hasMeeting())
                <a href="{{ $userRow->getMeetingReservationUrl() }}" target="_blank" class="d-inline-flex-center size-48 rounded-12 border-2 border-gray-400">
                    <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="24px" height="24px"/>
                </a>
            @endif
        </div>

        @if($userRow->offline)
            <div class="mt-16 p-12 rounded-12 border-gray-200 bg-gray-100">
                <div class="d-flex align-items-center">
                    <x-iconsax-lin-profile-delete class="icons text-gray-500" width="24px" height="24px"/>
                    <span class="ml-4 font-12 font-weight-bold text-gray-500">{{ trans('update.the_instructor_is_currently_unavailable') }}</span>
                </div>

                <div class="mt-12 text-gray-500">{!! nl2br($userRow->offline_message) !!}</div>
            </div>
        @endif

    </div>
</div>
