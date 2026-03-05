@php
    $sessionItemTitle = null;
    $sessionItemUrl = null;

    if (!empty($session->event_id) and !empty($session->event)) {
        $sessionItemTitle = $session->event->title;
        $sessionItemUrl = $session->event->getUrl();
    }

    if (!empty($session->meeting_package_sold_id) and !empty($session->meetingPackageSold)) {
        $meetingPackage = $session->meetingPackageSold->meetingPackage;

        if (!empty($meetingPackage)) {
            $sessionItemTitle = $meetingPackage->title;
            $sessionItemUrl = null;
        }
    }

    if (!empty($session->reserve_meeting_id)) {
        $sessionItemTitle = trans('public.meeting');
        $sessionItemUrl = null;
    }
@endphp

<div class="learning-page__top-header d-flex align-items-center justify-content-between w-100 bg-white py-16 pl-32 pr-24">
    <div class="">
        @if(!empty($sessionItemUrl))
            <a href="{{ $sessionItemUrl }}" class="font-16 font-weight-bold text-dark d-flex mb-4">
                <span class="">{{ $sessionItemTitle }}</span>
            </a>
        @else
            <div class="font-16 font-weight-bold text-dark d-flex mb-4">{{ $sessionItemTitle }}</div>
        @endif

        <div class="d-none d-lg-flex">
            @include('design_1.panel.includes.breadcrumb')
        </div>
    </div>

    <div class="d-flex align-items-center gap-16">

        <div class="js-toggle-show-learning-page-sidebar-drawer d-flex d-lg-none ml-16 cursor-pointer">
            <x-iconsax-lin-menu class="icons text-gray-500" width="24px" height="24px"/>
        </div>

    </div>
</div>
