<div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
    <div class="d-flex-center size-40 bg-white border-gray-200 rounded-8 cursor-pointer">
        <x-iconsax-lin-more class="icons text-gray-500" width="24px" height="24px"/>
    </div>

    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
        <ul class="my-8">

            <li class="actions-dropdown__dropdown-menu-item">
                <a href="/panel/events/my-purchases/{{ $event->id }}/tickets" target="_blank" class="">{{ trans('update.view_tickets') }}</a>
            </li>

            <li class="actions-dropdown__dropdown-menu-item">
                <a href="/panel/events/my-purchases/{{ $event->id }}/invoice" target="_blank" class="">{{ trans('public.invoice') }}</a>
            </li>

            @if($event->type == "online" and !empty($event->session))
                <li class="actions-dropdown__dropdown-menu-item">
                    <button type="button" data-path="/panel/events/my-purchases/{{ $event->id }}/join-modal" class="js-join-to-events-session">{{ trans('update.join_the_event') }}</button>
                </li>
            @endif

            <li class="actions-dropdown__dropdown-menu-item">
                <a href="{{ $event->getUrl() }}?tab=reviews" target="_blank" class="">{{ trans('public.feedback') }}</a>
            </li>

        </ul>
    </div>
</div>
