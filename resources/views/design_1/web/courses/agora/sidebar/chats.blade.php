@if(!empty($session->agora_settings) and $session->agora_settings->chat)
    <div class="sidebar-chats-cards px-12 pt-16" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
        <div id="sidebarChatCards">



        </div>
    </div>

    <div class="sidebar-chats-footer d-flex align-items-center gap-12 p-12 border-top-gray-200">
        <div class="form-group mb-0 flex-1 mt-8">
            <label class="form-group-label">{{ trans('update.type_your_message') }}</label>
            <input type="text" name="message" id="chatMessageInput" class="form-control" autocomplete="off">
        </div>

        <div class="js-send-message-btn d-flex-center size-48 rounded-circle bg-primary cursor-pointer">
            <x-iconsax-lin-send-2 class="icons text-white" width="24px" height="24px"/>
        </div>
    </div>
@else
    <div class="d-flex-center flex-column text-center pt-160 px-24">
        <div class="d-flex-center size-72 rounded-24 bg-gray">
            <div class="d-flex-center size-64 rounded-24 bg-info-gradient">
                <x-iconsax-bul-messages class="icons text-white" width="32px" height="32px"/>
            </div>
        </div>

        <h4 class="font-14 mt-8 text-dark">{{ trans('update.chat_not_active') }}</h4>
        <div class="mt-8 text-gray-500">{{ trans('update.chat_not_active_hint') }}</div>
    </div>
@endif
