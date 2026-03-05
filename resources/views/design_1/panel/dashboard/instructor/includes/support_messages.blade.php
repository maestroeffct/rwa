<div class="bg-white p-16 rounded-24 mt-24">
    <h4 class="font-14 text-dark">{{ trans('panel.support_messages') }}</h4>

    {{-- If Have Data --}}
    @if(!empty($supportMessages['totalTickets']))

        <div class="d-grid grid-columns-2 gap-16 mt-16">
            {{-- Open Tickets --}}
            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-16 p-16">
                <div class="">
                    <span class="d-block font-16 font-weight-bold text-dark">{{ $supportMessages['openTickets'] }}</span>
                    <span class="d-block font-12 text-gray-500 mt-8">{{ trans('update.open_tickets') }}</span>
                </div>

                <x-iconsax-bul-message-notif class="icons text-warning" width="24px" height="24px"/>
            </div>

            {{-- Total Tickets --}}
            <div class="d-flex align-items-start justify-content-between bg-gray-100 rounded-16 p-16">
                <div class="">
                    <span class="d-block font-16 font-weight-bold text-dark">{{ $supportMessages['totalTickets'] }}</span>
                    <span class="d-block font-12 text-gray-500 mt-8">{{ trans('update.total_tickets') }}</span>
                </div>

                <x-iconsax-bul-messages class="icons text-primary" width="24px" height="24px"/>
            </div>
        </div>

        @if(!empty($supportMessages['supports']) and count($supportMessages['supports']))
            <div class="student-dashboard__support-messages" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
                @foreach($supportMessages['supports'] as $support)
                    <div class="bg-gray-100 rounded-16 p-12 pb-20 mt-16">
                        <div class="bg-white p-12 rounded-12">
                            @php
                                $supportUser = $support->user;
                                $lastConversation = $support->conversations->first();
                            @endphp

                            <div class="d-flex align-items-center">
                                <div class="size-40 rounded-circle">
                                    <img src="{{ $supportUser->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                                </div>
                                <div class="ml-8">
                                    <h5 class="font-14 text-dark">{{ truncate($support->title, 25) }}</h5>

                                    <div class="d-flex align-items-center gap-8 font-12 text-gray-500 mt-4">
                                        <span class="font-weight-bold">{{ $supportUser->full_name }}</span>
                                        <span class="size-4 rounded-16 bg-gray-300"></span>
                                        <span class="">{{ dateTimeFormat($support->created_at, 'j M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-20 font-12 text-gray-500 white-space-pre-wrap">{{ truncate($lastConversation->message, 100) }}</div>
                        </div>

                        <div class="d-flex align-items-center mt-20">
                            @if (!empty($support->department))
                                <div class="size-32 rounded-8 bg-gray-100">
                                    <img src="{{ getPlatformLogo() }}" alt="" class="img-cover rounded-8">
                                </div>
                                <div class="ml-4 font-12 text-gray-500">{{ $support->department->title }}</div>
                            @elseif(!empty($support->webinar))
                                <div class="size-32 rounded-8 bg-gray-100">
                                    <img src="{{ $support->webinar->getIcon() }}" alt="" class="img-cover rounded-8">
                                </div>
                                <div class="ml-4 font-12 text-gray-500">{{ $support->webinar->title }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        {{-- If Empty --}}
        <div class="d-flex-center flex-column bg-gray-100 border-dashed border-gray-200 text-center mt-16 p-32 rounded-16">
            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                <x-iconsax-bul-messages class="icons text-primary" width="24px" height="24px"/>
            </div>
            <h4 class="font-14 text-dark mt-12">{{ trans('update.no_support_ticket!') }}</h4>
            <div class="mt-4 font-12 text-gray-500">{{ trans('update.you_can_enable_support_for_your_courses_and_encourage_students') }}</div>
        </div>
    @endif
</div>
