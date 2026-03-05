<div class="js-noticeboards-drawer noticeboards-drawer bg-white py-16">
    <div class="d-flex align-items-center pb-16 border-bottom-gray-bg px-16">
        <button type="button" class="js-noticeboards-drawer-close d-flex btn-transparent">
            <x-iconsax-lin-arrow-right class="icons text-gray-500" width="25px" height="25px"/>
        </button>

        <span class="font-14 font-weight-bold ml-8">{{ trans("update.course_notices") }} ({{ $course->noticeboards_count }})</span>
    </div>

    <div class="noticeboards-drawer__body p-16" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>
        <div id="noticeboardsDrawerBody"></div>
    </div>
</div>
<div class="noticeboards-drawer-mask"></div>
