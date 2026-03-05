@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.verification_requests') }}
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th class="text-left">{{ trans('update.installment_plan') }}</th>
                                        <th class="text-center">{{ trans('update.product') }}</th>
                                        <th class="text-center">{{ trans('financial.total_amount') }}</th>
                                        <th class="text-center">{{ trans('update.upfront') }}</th>
                                        <th class="text-center">{{ trans('update.installments_count') }}</th>
                                        <th class="text-center">{{ trans('update.installments_amount') }}</th>
                                        <th class="text-center">{{ trans('update.request_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.status') }}</th>
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
                                                        <div class="mt-0 mb-1">{{ $order->user->full_name }}</div>

                                                        @if($order->user->mobile)
                                                            <div class="text-gray-500 text-small ">{{ $order->user->mobile }}</div>
                                                        @endif

                                                        @if($order->user->email)
                                                            <div class="text-gray-500 text-small">{{ $order->user->email }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-left">
                                                <div class="">
                                                    <span class="d-block text-dark font-14">{{ $order->selectedInstallment->installment->title }}</span>
                                                    <span class="d-block text-gray-500 font-12 mt-1">{{ trans('update.target_types_'.$order->selectedInstallment->installment->target_type) }}</span>
                                                </div>
                                            </td>

                                            @php
                                                $itemPrice = $order->getItemPrice();
                                            @endphp

                                            <td class="text-center">
                                                @if(!empty($order->webinar_id))
                                                    <a href="{{ !empty($order->webinar) ? $order->webinar->getUrl() : '' }}"
                                                       target="_blank" class="text-dark font-14">#{{ $order->webinar_id }}-{{ !empty($order->webinar) ? $order->webinar->title : '' }}</a>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_courses') }}</span>
                                                @elseif(!empty($order->bundle_id))
                                                    <a href="{{ !empty($order->bundle) ? $order->bundle->getUrl() : '' }}"
                                                       target="_blank" class="text-dark font-14">#{{ $order->bundle_id }}-{{ !empty($order->bundle) ? $order->bundle->title : '' }}</a>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_bundles') }}</span>
                                                @elseif(!empty($order->product_id))
                                                    <a href="{{ !empty($order->product) ? $order->product->getUrl() : '' }}"
                                                       target="_blank" class="text-dark font-14">#{{ $order->product_id }}-{{ !empty($order->product) ? $order->product->title : '' }}</a>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_store_products') }}</span>
                                                @elseif(!empty($order->subscribe_id))
                                                    <span class="text-dark font-14">{{ trans('admin/main.purchased_subscribe') }}</span>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_subscription_packages') }}</span>
                                                @elseif(!empty($order->registration_package_id))
                                                    <span class="font-14">{{ trans('update.purchased_registration_package') }}</span>
                                                    <span class="d-block text-gray-500 font-12">{{ trans('update.target_types_registration_packages') }}</span>
                                                @else
                                                    ---
                                                @endif
                                            </td>

                                            <td class="text-center">{{ handlePrice($order->selectedInstallment->totalPayments($itemPrice)) }}</td>

                                            <td class="text-center">
                                                @if(!empty($order->selectedInstallment->upfront))
                                                    {{ ($order->selectedInstallment->upfront_type == 'percent') ? $order->selectedInstallment->upfront.'%' : handlePrice($order->selectedInstallment->upfront) }}
                                                @else
                                                    --
                                                @endif
                                            </td>

                                            <td class="text-center">{{ $order->selectedInstallment->steps_count }}</td>

                                            <td class="text-center">
                                                @php
                                                    $stepsFixedAmount = $order->selectedInstallment->steps->where('amount_type', 'fixed_amount')->sum('amount');
                                                    $stepsPercents = $order->selectedInstallment->steps->where('amount_type', 'percent')->sum('amount');
                                                @endphp

                                                <span class="">{{ $stepsFixedAmount ? handlePrice($stepsFixedAmount) : '' }}</span>

                                                @if($stepsPercents)
                                                    <span>{{ $stepsFixedAmount ? ' + ' : '' }}{{ $stepsPercents }}%</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ dateTimeFormat($order->created_at, 'j M Y') }}</td>

                                            <td class="text-center">
                                                @if($order->status == "pending_verification")
                                                    <span class="badge-status text-warning bg-warning-30">{{ trans('update.pending_verification') }}</span>
                                                @elseif($order->status == "open")
                                                    <span class="badge-status text-success bg-success-30">{{ trans('financial.approved') }}</span>
                                                @elseif($order->status == "rejected")
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('public.rejected') }}</span>
                                                @elseif($order->status == "canceled")
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('public.canceled') }}</span>
                                                @elseif($order->status == "refunded")
                                                    <span class="badge-status text-warning bg-warning-30">{{ trans('update.refunded') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                            <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_installments_orders')
                @if($order->status == "pending_verification")
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl("/financial/installments/orders/{$order->id}/approve"),
                        'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                        'btnText' => trans("admin/main.approve"),
                        'btnIcon' => 'tick-square',
                        'iconType' => 'lin',
                        'iconClass' => 'text-success mr-2',
                    ])

                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl("/financial/installments/orders/{$order->id}/reject"),
                        'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                        'btnText' => trans("admin/main.reject"),
                        'btnIcon' => 'close-square',
                        'iconType' => 'lin',
                        'iconClass' => 'text-danger mr-2',
                    ])
                @endif

                <a href="{{ getAdminPanelUrl("/financial/installments/orders/{$order->id}/details") }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
                    <x-iconsax-lin-sms-tracking class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('site.send_message') }}</span>
                </a>
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
