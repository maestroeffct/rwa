<div class="bg-white py-16 rounded-16">
    <div class="px-16 mb-16">
        <h4 class="font-16">{{ trans('panel.conversations') }}</h4>

        <div class="d-flex align-items-center mt-16">
            <div class="conversation-search-box flex-1 form-group d-flex align-items-center mb-0 rounded-12 bg-gray-100 py-4 px-8">
                <input type="text" name="search" class="form-control flex-1 bg-transparent border-0" value="{{ request()->get('search') }}" placeholder="{{ trans('public.search') }}">

                <button type="button" class="btn-transparent ml-8 p-4">
                    <x-iconsax-lin-search-normal class="icons text-gray-400" width="16px" height="16px"/>
                </button>
            </div>

            <div class="actions-dropdown position-relative d-flex ml-12">
                <button type="button" class="btn-transparent d-flex-center size-48 rounded-12 bg-gray-100">
                    <x-iconsax-lin-setting-4 class="icons text-gray-400" width="16px" height="16px"/>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32 dropdown-menu-left">
                    <ul class="my-8">

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-status="all" class="js-conversation-status">{{ trans('update.all_tickets') }}</button>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-status="replied" class="js-conversation-status">{{ trans('update.replied_tickets') }}</button>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-status="open" class="js-conversation-status">{{ trans('update.waiting_tickets') }}</button>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-status="close" class="js-conversation-status">{{ trans('update.closed_tickets') }}</button>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </div>

    <div class="support-conversation-card" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>

        @foreach($supports as $support)
            @php
                $lastConversation = $support->conversations->first();
            @endphp

            <a href="/panel/support/{{ $support->id }}/conversations" class="js-conversation-lists js-conversation-status-{{ $support->status }}">
                <div class="d-flex align-items-center px-16 py-12 support-conversation-item {{ (!empty($selectSupport) and $selectSupport->id == $support->id) ? 'active' : '' }}">
                    <div class="size-48 rounded-circle mb-16 bg-gray-200">
                        <img src="{{ (!empty($support->webinar) and $support->webinar->teacher_id != $authUser->id) ? $support->webinar->teacher->getAvatar() : $support->user->getAvatar() }}"
                             alt=""
                             class="js-avatar-img img-cover rounded-circle">
                    </div>

                    <div class="ml-8">
                        <h6 class="font-14 text-dark">{{ (!empty($support->webinar) and $support->webinar->teacher_id != $authUser->id) ? $support->webinar->teacher->full_name : $support->user->full_name }}</h6>
                        <p class="font-12 mt-6 text-gray-500">{{ truncate($support->title, 40) }}</p>

                        <div class="d-flex align-items-center mt-8">
                            <span class="font-12 text-gray-500">{{ !empty($lastConversation) ? dateTimeFormat($lastConversation->created_at,'j M Y | H:i') : dateTimeFormat($support->created_at,'j M Y | H:i') }}</span>
                            <span class="size-4 rounded-circle bg-gray-300 mx-8"></span>

                            @if($support->status == 'close')
                                <span class="badge-status rounded-8 font-10 py-4 px-6 bg-danger-30 text-danger ">{{ trans('panel.closed') }}</span>
                            @elseif($support->status == 'supporter_replied')
                                <span class="badge-status rounded-8 font-10 py-4 px-6 bg-primary-30 text-primary">{{ trans('panel.replied') }}</span>
                            @else
                                <span class="badge-status rounded-8 font-10 py-4 px-6 bg-warning-30 text-warning">{{ trans('public.waiting') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @endforeach

    </div>
</div>
