<div class="bg-white py-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark px-16">{{ trans('panel.noticeboard') }}</h4>

    {{-- User Message --}}
    @if(!empty($unreadNoticeboards) and count($unreadNoticeboards))
        <div class="student-dashboard__noticeboards-lists px-16" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
            @foreach($unreadNoticeboards as $unreadNoticeboard)
                <div class="js-noticeboard-card bg-gray-100 rounded-16 p-12 mt-16 position-relative cursor-pointer">
                    <div class="js-show-noticeboard-info d-none" data-id="{{ $unreadNoticeboard->id }}"></div>
                    
                    <a href="javascript:void(0);" class="stretched-link" onclick="$(this).closest('.js-noticeboard-card').find('.js-show-noticeboard-info').trigger('click')"></a>
                    
                    <div class="bg-white p-16 rounded-12">
                        <h5 class="font-14 text-dark">{!! truncate($unreadNoticeboard->title, 25) !!}</h5>
                        <div class="mt-8 font-12 text-gray-500">{!! truncate(strip_tags($unreadNoticeboard->message), 150) !!}</div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-8 mt-8">
                        <div class="js-noticeboard-sender-info d-flex align-items-center">
                            @if($unreadNoticeboard->sender_type = 'platform')
                                <div class="d-flex-center size-40 rounded-circle bg-primary">
                                    <x-iconsax-bul-teacher class="icons text-white" width="24px" height="24px"/>
                                </div>
                            @else
                                @php
                                    $senderAvatar = !empty($unreadNoticeboard->senderUser) ? $unreadNoticeboard->senderUser->getAvatar(40) : getDefaultAvatarPath();
                                @endphp

                                <div class="size-40 rounded-circle">
                                    <img src="{{ $senderAvatar }}" alt="" class="img-cover rounded-circle">
                                </div>
                            @endif

                            <div class="ml-8">
                                <span class="d-block font-weight-bold">{{ ($unreadNoticeboard->sender_type = 'platform') ? trans('update.platform') : $unreadNoticeboard->sender }}</span>
                                <span class="d-block font-12 text-gray-500 mt-4">{{ dateTimeFormat($unreadNoticeboard->created_at, 'j M Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="">
                            <div class="d-flex-center size-40 rounded-circle border-gray-200 bg-hover-white">
                                <x-iconsax-lin-arrow-right class="icons text-gray-500" width="16px" height="16px"/>
                            </div>
                            <input type="hidden" class="js-noticeboard-message" value="{{ $unreadNoticeboard->message }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty --}}
        <div class="d-flex-center flex-column text-center mt-16 p-32 rounded-16 border-dashed border-gray-200 bg-gray-100">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <x-iconsax-bul-notification-bing class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h5 class="mt-12 font-14 text-dark">{{ trans('update.no_notice!') }}</h5>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.you_don’t_have_any_notices_from_the_administrator_at_this_moment') }}</div>
        </div>
    @endif
</div>

@push('scripts_bottom')
<script>
    "use strict";
    $(document).ready(function() {
        $(document).on('click', '.js-noticeboard-card', function(e) {
            if (!$(e.target).closest('.js-show-noticeboard-info').length) {
                $(this).find('.js-show-noticeboard-info').trigger('click');
            }
        });
    });
</script>
@endpush

