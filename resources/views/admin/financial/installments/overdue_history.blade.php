@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.overdue_history') }}
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header justify-content-between">

                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>

                            <div class="d-flex align-items-center gap-12">
                                   <a href="{{ getAdminPanelUrl("/financial/installments/overdue_history/export") }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                       <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                   </a>
                            </div>

                       </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th class="text-left">{{ trans('update.installment_plan') }}</th>
                                        <th class="text-center">{{ trans('update.product') }}</th>
                                        <th class="text-center">{{ trans('admin/main.amount') }}</th>
                                        <th class="text-center">{{ trans('update.overdue_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
                                        <th class="text-center">{{ trans('update.paid_date') }}</th>
                                        <th class="text-center">{{ trans('update.late_days') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar mr-2">
                                                        <img src="{{ $order->user->getAvatar() }}" alt="{{ $order->user->full_name }}">
                                                    </figure>
                                                    <div class="media-body ml-1">
                                                        <div class="mt-0 mb-1 ">{{ $order->user->full_name }}</div>

                                                        @if($order->user->mobile)
                                                            <div class="text-gray-500 text-small">{{ $order->user->mobile }}</div>
                                                        @endif

                                                        @if($order->user->email)
                                                            <div class="text-gray-500 text-small ">{{ $order->user->email }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-left">
                                                <div class="">
                                                    <span class="d-block ">{{ $order->selectedInstallment->installment->title }}</span>
                                                    <span class="d-block text-gray-500 font-12 mt-1">{{ trans('update.target_types_'.$order->selectedInstallment->installment->target_type) }}</span>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                @if(!empty($order->webinar_id))
                                                    <a href="{{ !empty($order->webinar) ? $order->webinar->getUrl() : '' }}"
                                                       target="_blank" class="font-14 text-dark">#{{ $order->webinar_id }}-{{ !empty($order->webinar) ? $order->webinar->title : '' }}</a>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_courses') }}</span>
                                                @elseif(!empty($order->bundle_id))
                                                    <a href="{{ !empty($order->bundle) ? $order->bundle->getUrl() : '' }}"
                                                       target="_blank" class="font-14 text-dark">#{{ $order->bundle_id }}-{{ !empty($order->bundle) ? $order->bundle->title : '' }}</a>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_bundles') }}</span>
                                                @elseif(!empty($order->product_id))
                                                    <a href="{{ !empty($order->product) ? $order->product->getUrl() : '' }}"
                                                       target="_blank" class="font-14 text-dark">#{{ $order->product_id }}-{{ !empty($order->product) ? $order->product->title : '' }}</a>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_store_products') }}</span>
                                                @elseif(!empty($order->subscribe_id))
                                                    <span class="font-14 text-dark">{{ trans('admin/main.purchased_subscribe') }}</span>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_subscription_packages') }}</span>
                                                @elseif(!empty($order->registration_package_id))
                                                    <span class="font-14 text-dark">{{ trans('update.purchased_registration_package') }}</span>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_registration_packages') }}</span>
                                                @else
                                                    ---
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if($order->amount_type == 'percent')
                                                    {{ $order->amount }}% ({{ handlePrice(($order->getItemPrice() * $order->amount) / 100) }})
                                                @else
                                                    {{ handlePrice($order->amount) }}
                                                @endif
                                            </td>

                                            <td class="text-center">{{ dateTimeFormat($order->overdue_date, 'j M Y') }} ({{ dateTimeFormatForHumans($order->overdue_date,true,null,1) }})</td>

                                            <td class="text-center">
                                                @if(!empty($order->paid_at))
                                                    <span class="badge-status text-success bg-success-30">{{ trans('public.paid') }}</span>
                                                @else
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('update.unpaid') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ !empty($order->paid_at) ? dateTimeFormat($order->paid_at, 'j M Y') : '-' }}</td>

                                            <td class="text-center">
                                                @php
                                                    $time = !empty($order->paid_at) ? $order->paid_at : time();
                                                    $days = round(($time - $order->overdue_date) / 86400);
                                                @endphp
                                                {{ $days }}
                                            </td>

                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_installments_orders')
                <a href="{{ getAdminPanelUrl('/financial/installments/orders/'.$order->id.'/details') }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('update.show_details') }}</span>
                </a>
            @endcan

            @can('admin_users_impersonate')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $order->user_id }}/impersonate" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                </a>
            @endcan

            @can('admin_users_edit')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $order->user_id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_support_send')
                <a href="{{ getAdminPanelUrl() }}/supports/create?user_id={{ $order->user_id }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-message-text-1 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('site.send_message') }}</span>
                </a>
            @endcan

            @can('admin_installments_orders')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl('/financial/installments/orders/'.$order->id.'/cancel'),
                    'btnClass' => 'dropdown-item text-danger d-flex align-items-center mb-3 py-3 px-0 gap-4 font-14',
                    'btnText' => trans('admin/main.cancel'),
                    'btnIcon' => 'close-square',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2'
                ])

                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl('/financial/installments/orders/'.$order->id.'/refund'),
                    'btnClass' => 'dropdown-item text-danger d-flex align-items-center mb-0 py-3 px-0 gap-4 font-14',
                    'btnText' => trans('admin/main.refund'),
                    'btnIcon' => 'money-send',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2'
                ])
            @endcan
        </div>
    </div>
</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $orders->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
