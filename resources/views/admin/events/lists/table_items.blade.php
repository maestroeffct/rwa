@php
    $getMinAndMaxPrice = $event->getMinAndMaxPrice();
    $eventSoldTicketsCount = $event->getSoldTicketsCount();
@endphp

<tr>
    {{-- Event --}}
    <td class="text-left">
        <a href="{{ $event->getUrl() }}" target="_blank" class="">
            <div class="d-flex align-items-center">
                <div class="size-44 rounded-circle">
                    <img src="{{ $event->getIcon() }}" alt="" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <div class="text-dark">{{ $event->title }}</div>

                    @if(!empty($event->category))
                        <div class="font-12 mt-4 text-gray-500">{{ $event->category->title }}</div>
                    @endif
                </div>
            </div>
        </a>
    </td>

    {{-- Provider --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="size-44 rounded-circle">
                <img src="{{ $event->creator->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
            </div>
            <div class="ml-8">
                <div class="text-dark">{{ $event->creator->full_name }}</div>
            </div>
        </div>
    </td>

    {{-- Event Type --}}
    <td>
        <span class="">{{ trans("update.{$event->type}") }}</span>
    </td>

    {{-- Price --}}
    <td class="">
        <div class="d-flex-center text-center gap-4">
            @if($getMinAndMaxPrice['min'] == $getMinAndMaxPrice['max'])
                <span class="">{{ ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free') }}</span>
            @else
                <span class="">{{ ($getMinAndMaxPrice['min'] > 0) ? handlePrice($getMinAndMaxPrice['min'], true, true, false, null, true) : trans('update.free') }}</span>
                -
                <span class="">{{ ($getMinAndMaxPrice['max'] > 0) ? handlePrice($getMinAndMaxPrice['max'], true, true, false, null, true) : trans('update.free') }}</span>
            @endif
        </div>
    </td>

    {{-- Capacity --}}
    <td width="130">
        @if(!is_null($event->capacity))
            <div class="d-flex-center flex-column">
                <span class="">{{ $event->capacity }}</span>
                <span class="font-10 text-gray-500 mt-2">{{ trans('update.n_remaining', ['count' => $event->getAvailableCapacity()]) }}</span>
            </div>
        @else
            <span class="">{{ trans('update.unlimited') }}</span>
        @endif
    </td>

    {{-- Sold Tickets --}}
    <td>
        <div class="d-flex-center flex-column">
            <span class="">{{ $eventSoldTicketsCount }}</span>

            @if($eventSoldTicketsCount > 0)
                <span class="font-10 text-gray-500 mt-2">{{ handlePrice($event->getAllSales()->sum('paid_amount')) }}</span>
            @endif
        </div>
    </td>

    {{-- Start Date --}}
    <td>
        <span class="">{{ (!empty($event->start_date)) ? dateTimeFormat($event->start_date, 'j M Y H:i') : '-' }}</span>
    </td>

    {{-- Created Date --}}
    <td>
        <span class="">{{ dateTimeFormat($event->created_at, 'j M Y H:i') }}</span>
    </td>

    {{-- Status --}}
    <td>
        @switch($event->status)
            @case('publish')
                @if(!empty($event->start_date) and $event->start_date > time())
                    <span class="badge-status text-success bg-success-30">{{ trans('update.scheduled') }}</span>
                @elseif(!empty($event->end_date) and $event->end_date < time())
                    <span class="badge-status text-info bg-info-30">{{ trans('update.ended') }}</span>
                @else
                    <span class="badge-status text-primary bg-primary-30">{{ trans('update.ongoing') }}</span>
                @endif
                @break
            @case('draft')
                <span class="badge-status text-dark bg-dark-30">{{ trans('admin/main.is_draft') }}</span>
                @break
            @case('pending')
                <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.waiting') }}</span>
                @break
            @case('unpublish')
                <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.unpublished') }}</span>
                @break
            @case('canceled')
                <span class="badge-status text-danger bg-danger-30">{{ trans('update.canceled') }}</span>
                @break
            @case('rejected')
                <span class="badge-status text-danger bg-danger-30">{{ trans('update.rejected') }}</span>
                @break
        @endswitch
    </td>

    {{-- Actions --}}
    <td>
        <div class="btn-group dropdown table-actions position-relative">
            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
            </button>

            <div class="dropdown-menu dropdown-menu-right">

                @if(!in_array($event->status, ['canceled', 'draft', 'pending']))
                    @can('admin_events_sold_tickets')
                        <a href="{{ getAdminPanelUrl("/events/{$event->id}/sold-tickets") }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                            <x-iconsax-lin-ticket class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                            <span class="text-gray-500 font-14">{{ trans('update.sold_tickets') }}</span>
                        </a>
                    @endcan

                    @can('admin_event_send_notification')
                        <a href="{{ getAdminPanelUrl('/events/'. $event->id .'/send-notification') }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                            <x-iconsax-lin-notification-bing class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                            <span class="text-gray-500 font-14">{{ trans('notification.send_notification') }}</span>
                        </a>
                    @endcan
                @endif

                @if(in_array($event->status, ['draft', 'pending', 'unpublish', 'canceled', 'rejected']))
                    @include('admin.includes.delete_button',[
                       'url' => getAdminPanelUrl('/events/'.$event->id.'/publish'),
                       'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                       'btnText' => trans("admin/main.publish"),
                       'btnIcon' => 'tick-square',
                       'iconType' => 'lin',
                       'iconClass' => 'text-success mr-2',
                    ])
                @endif

                @if($event->status == 'pending')
                    @include('admin.includes.delete_button',[
                       'url' => getAdminPanelUrl('/events/'.$event->id.'/reject'),
                       'btnClass' => 'dropdown-item  text-danger mb-3 py-3 px-0 font-14',
                       'btnText' => trans("admin/main.reject"),
                       'btnIcon' => 'close-square',
                       'iconType' => 'lin',
                       'iconClass' => 'text-danger mr-2',
                    ])

                    @include('admin.includes.delete_button',[
                       'url' => getAdminPanelUrl('/events/'.$event->id.'/unpublish'),
                       'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                       'btnText' => trans("admin/main.unpublish"),
                       'btnIcon' => 'gallery-slash',
                       'iconType' => 'lin',
                       'iconClass' => 'text-danger mr-2',
                    ])
                @endif


                @can('admin_events_create')
                    <a href="{{ getAdminPanelUrl('/events/'. $event->id .'/edit') }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                        <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                        <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                    </a>
                @endcan

                @if($event->status != "canceled")
                    @include('admin.includes.delete_button',[
                       'url' => getAdminPanelUrl('/events/'.$event->id.'/cancel'),
                       'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                       'btnText' => trans("admin/main.cancel"),
                       'btnIcon' => 'tick-square',
                       'iconType' => 'lin',
                       'iconClass' => 'text-danger mr-2',
                    ])
                @endif

                @can('admin_events_delete')
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl('/events/'.$event->id.'/delete'),
                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                        'btnText' => trans("admin/main.delete"),
                        'btnIcon' => 'trash',
                        'iconType' => 'lin',
                        'iconClass' => 'text-danger mr-2',
                     ])
                @endcan
            </div>
        </div>
    </td>
</tr>
