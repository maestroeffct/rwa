<div class="bg-white p-16 rounded-24">


    @if(!empty($selectSupport))
        <div class="d-flex flex-column">
            <div class="bg-gray-100 p-16 rounded-12">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="font-14 font-weight-bold">{{ $selectSupport->title }}</h5>

                    @if($selectSupport->status != 'close')
                        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                            <button type="button" class="d-flex-center size-24 btn-transparent">
                                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                            </button>

                            <div class="actions-dropdown__dropdown-menu">
                                <ul class="my-8">

                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <a href="/panel/support/{{ $selectSupport->id }}/close" class="text-danger">{{ trans('public.close') }}</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center mt-16">
                    <div class="size-40 rounded-circle bg-gray-300">
                        <img src="{{ $selectSupport->user->getAvatar(40) }}" alt="" class="img-cover rounded-circle">
                    </div>
                    <div class="ml-8">
                        <h6 class="font-12 font-weight-bold text-gray-500">{{ $selectSupport->user->full_name }}</h6>

                        <div class="d-flex align-items-center mt-8">
                            <span class="font-12 text-gray-500">{{ trans('public.created') }}: {{ dateTimeFormat($selectSupport->created_at,'j M Y | H:i') }}</span>

                            @if(!empty($selectSupport->webinar))
                                <div class="d-flex align-items-center ml-32">
                                    <x-iconsax-bul-teacher class="icons text-gray-500" width="16px" height="16px"/>
                                    <span class="font-12 text-gray-500 ml-4">{{ $selectSupport->webinar->title }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="conversationsCard" class="support-conversation-messages pt-16 border-top-gray-200" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
                @if(!empty($selectSupport->conversations) and !$selectSupport->conversations->isEmpty())
                    @foreach($selectSupport->conversations as $conversation)
                        @php
                            $conversationUser = (!empty($conversation->supporter)) ? $conversation->supporter : $conversation->sender;
                        @endphp

                        <div class="p-16 rounded-12 border-gray-200 mt-16">
                            <div class="d-flex align-items-end justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="size-40 rounded-circle bg-gray-200">
                                        <img src="{{ $conversationUser->getAvatar(40) }}" class="img-cover rounded-circle" alt="">
                                    </div>
                                    <div class="ml-8">
                                        <h6 class="font-12 font-weight-bold">{{ $conversationUser->full_name }}</h6>
                                        <span class="mt-4 font-12 text-gray-500 d-block">{{ (!empty($conversation->supporter)) ? trans('panel.staff') : $conversation->sender->role_name }}</span>
                                    </div>
                                </div>

                                <span class="font-12 text-gray-500">{{ dateTimeFormat($conversation->created_at, 'j M Y H:i') }}</span>
                            </div>

                            <div class="mt-16 pt-16 border-top-gray-100">
                                <p class="white-space-pre-wrap text-gray-500 font-14">{{ $conversation->message }}</p>

                                @if(!empty($conversation->attach))
                                    <a href="{{ url($conversation->attach) }}" target="_blank" class="d-inline-flex-center p-8 border-gray-300 rounded-8 mt-16">
                                        <x-iconsax-lin-document-download class="icons text-gray-400" width="16px" height="16px"/>
                                        <span class="font-12 text-gray-500 ml-4">{{ trans('update.attachment') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <form action="/panel/support/{{ $selectSupport->id }}/conversations" method="post" class="mt-auto" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="d-flex align-items-center pt-28 border-top-gray-100">
                    <div class="form-group mb-0 flex-1">
                        <label class="form-group-label">{{ trans('update.type_your_message') }}</label>
                        <input type="text" name="message" class="form-control @error('message') is-invalid @enderror" autocomplete="off">

                        @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="file" name="attach" id="attach" value="{{ old('attach') }}" class="position-absolute opacity-0 d-none"/>

                    <button type="button" class="js-conversation-message-attach-btn btn bg-gray-100 size-48 rounded-circle ml-20 p-0">
                        <x-iconsax-lin-paperclip-2 class="icons text-gray-400" width="24px" height="24px"/>
                    </button>

                    <button type="submit" class="btn btn-primary size-48 rounded-circle ml-20 p-0">
                        <x-iconsax-lin-send-2 class="icons text-white" width="24px" height="24px"/>
                    </button>
                </div>
            </form>

        </div>
    @else

        <div class="row">

          <div class="col-12 col-lg-4">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('panel.total_conversations') }}</span>
                        <div class="size-48 d-flex-center bg-primary-30 rounded-12">
                            <x-iconsax-bul-message class="icons text-primary" width="24px" height="24px"/>
                        </div>
                    </div>

                    <h5 class="font-24 mt-12 line-height-1">{{ $supportsCount }}</h5>
                </div>
            </div>

            <div class="col-12 col-lg-4 mt-16 mt-md-0">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('panel.open_conversations') }}</span>
                        <div class="size-48 d-flex-center bg-warning-30 rounded-12">
                            <x-iconsax-bul-message-time class="icons text-warning" width="24px" height="24px"/>
                        </div>
                    </div>

                    <h5 class="font-24 mt-12 line-height-1">{{ $openSupportsCount }}</h5>
                </div>
            </div>
            <div class="col-12 col-lg-4 mt-16 mt-md-0">
                <div class="bg-white p-16 rounded-16 border-gray-200">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('panel.closed_conversations') }}</span>
                        <div class="size-48 d-flex-center bg-danger-30 rounded-12">
                            <x-iconsax-bul-message-remove class="icons text-danger" width="24px" height="24px"/>
                        </div>
                    </div>
                    <h5 class="font-24 mt-12 line-height-1">{{ $closeSupportsCount }}</h5>
                </div>
            </div>

        </div>


        @include('design_1.panel.includes.no-result',[
            'file_name' => 'support_tickets.svg',
            'title' => trans('panel.select_support'),
            'hint' => nl2br(trans('panel.select_support_hint')),
            'extraClass' => 'mt-0',
        ])
    @endif

</div>
