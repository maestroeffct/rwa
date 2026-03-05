<div class="learning-page__top-header d-flex align-items-center justify-content-between w-100 bg-white py-16 pl-32 pr-24">
    <div class="">
        <a href="{{ $course->getUrl() }}" class="font-16 font-weight-bold text-dark d-flex mb-4">
            <span class="">{{ $course->title }}</span>
        </a>

        <div class="d-none d-lg-flex">
            @include('design_1.panel.includes.breadcrumb')
        </div>
    </div>

    <div class="d-flex align-items-center gap-16">

        {{-- Course Tools --}}
        <div class="d-none d-lg-block">
            @include('design_1.web.courses.learning_page.includes.top_header.course_tools')
        </div>

        <div class="learning-page__line-separator d-none d-lg-block"></div>

        {{-- Notification --}}
        @php
            $hasNotification = (!empty($course->noticeboards_count) and $course->noticeboards_count > 0);
        @endphp

        <div class="position-relative d-flex-center size-48 rounded-circle {{ (!empty($hasNotification)) ? 'bg-primary-20 cursor-pointer js-show-noticeboards' : 'bg-gray-100' }}"
             data-path="{{ $course->getNoticeboardsPageUrl() }}"
        >
            <div class="d-flex-center size-32 rounded-circle {{ (!empty($hasNotification)) ? 'bg-primary' : 'bg-gray-200' }}">
                <x-iconsax-bul-notification-bing class="icons {{ (!empty($hasNotification)) ? 'text-white' : 'text-gray-500' }}" width="16px" height="16px"/>
            </div>

            @if($hasNotification)
                <span class="learning-page-notify-counter d-flex-center p-4 rounded-circle bg-danger font-12 text-white">{{ $course->noticeboards_count }}</span>
            @endif
        </div>

        <div class="js-toggle-show-learning-page-sidebar-drawer d-flex d-lg-none ml-16 cursor-pointer">
            <x-iconsax-lin-menu class="icons text-gray-500" width="24px" height="24px"/>
        </div>

    </div>
</div>
