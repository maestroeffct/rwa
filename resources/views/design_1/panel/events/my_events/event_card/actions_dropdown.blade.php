<div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
    <div class="d-flex-center size-40 bg-white border-gray-200 rounded-8 cursor-pointer">
        <x-iconsax-lin-more class="icons text-gray-500" width="24px" height="24px"/>
    </div>

    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
        <ul class="my-8">

            @can('panel_events_create')
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/events/{{ $event->id }}/edit" class="">{{ trans('public.edit') }}</a>
                </li>
            @endcan

            @if($event->type == "online")
                @if(empty($event->session))
                    <li class="actions-dropdown__dropdown-menu-item">
                        <button type="button" data-path="/panel/events/{{ $event->id }}/create-session" class="js-add-event-session">{{ trans('update.create_session') }}</button>
                    </li>
                @else
                    <li class="actions-dropdown__dropdown-menu-item">
                        <button type="button" data-path="/panel/events/{{ $event->id }}/join-session-modal" class="js-join-to-events-session">{{ trans('update.join_to_session') }}</button>
                    </li>
                @endif
            @endif

            @can('panel_events_sold_tickets_lists')
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/events/{{ $event->id }}/sold-tickets" class="">{{ trans('update.view_tickets') }}</a>
                </li>
            @endcan

            @if($event->creator_id == $authUser->id)
                @can('panel_events_create')
                    <li class="actions-dropdown__dropdown-menu-item">
                        @include('design_1.panel.includes.content_delete_btn', [
                            'deleteContentUrl' => "/panel/events/{$event->id}/delete",
                            'deleteContentClassName' => ' text-danger',
                            'deleteContentItem' => $event,
                            'deleteContentItemType' => "event",
                        ])
                    </li>
                @endcan
            @endif

        </ul>
    </div>
</div>
