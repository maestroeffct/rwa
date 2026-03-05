@if(!empty($dayEvents) and !empty($dayEvents['total']))
    @php
        $icons = [
            'courses_expirations' => 'video-play',
            'quiz_expirations' => 'clipboard-tick',
            'live_sessions' => 'video',
            'assignment_expirations' => 'note',
            'bundle_expirations' => 'box',
            'subscription_expirations' => 'crown',
            'registration_package_expirations' => 'cup',
            'installments' => 'graph',
            'meetings' => 'profile-2user',
            'live_class_start' => 'video',
        ];
    @endphp


    <div class="bg-white p-16 rounded-24">
        <div class="pb-6 border-bottom-gray-100">
            <h3 class="d-flex align-items-center font-14 font-weight-bold text-dark">{{ trans('update.events_for') }} <span class="js-selected-date ml-4">{{ dateTimeFormat(!empty($dayTimestamp) ? $dayTimestamp : time(), 'j M Y') }}</span></h3>
            <p class="font-12 text-gray-500 mt-4">{{ trans('update.check_events_and_add_them_to_reminder') }}</p>
        </div>

        {{-- Events Card --}}
        @foreach($dayEvents as $eventName => $dayEventItems)
            @if(!empty($dayEventItems) and is_array($dayEventItems))
                @foreach($dayEventItems as $event)
                    @if(!empty($event) and is_array($event))
                        @php
                            $icon = $icons[$eventName];
                        @endphp

                        <div class="d-flex align-items-center justify-content-between bg-gray-100 p-12 rounded-16 mt-16">
                            <div class="d-flex align-items-center">
                                <div class="d-flex-center size-48 rounded-8 bg-gray-200">
                                    @svg("iconsax-bul-{$icon}", ['height' => '24px', 'width' => '24px', 'class' => 'icons text-primary'])
                                </div>
                                <div class="ml-8">
                                    <div class="">{{ trans("update.{$eventName}") }}</div>
                                    <p class="font-12 text-gray-500 mt-4">{{ $event['subtitle'] }}</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-16">
                                @if(!empty($event['time']))
                                    <div class="d-inline-flex p-8 rounded-8 bg-gray-200 font-12 text-gray-500">{{ $event['time'] }}</div>
                                @endif

                                <a href="{{ $event['add_to_calendar_url'] }}" target="_blank" class="d-flex-center size-40 bg-white rounded-circle bg-hover-gray-200">
                                    <x-iconsax-bul-notification-bing class="icons text-gray-500" width="24px" height="24px"/>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>
@else
    @include('design_1.panel.includes.no-result',[
        'file_name' => 'events.svg',
        'title' => trans('update.events_no_result'),
        'hint' => nl2br(trans('update.events_no_result_hint')),
        'extraClass' => 'mt-0',
    ])
@endif
