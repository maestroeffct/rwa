@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl() }}/financial/offline_payments">{{ trans('admin/main.offline_payments') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.cart_items') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4 class="font-16">{{ trans('update.order') }} #{{ $order->id }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table font-14">
                            <tr>
                                <th>{{ trans('public.item') }}</th>
                                <th>{{ trans('public.type') }}</th>
                                <th class="text-center">{{ trans('public.amount') }}</th>
                            </tr>
                            @foreach($orderItems as $item)
                                <tr>
                                    <td>
                                        @if(!empty($item->reserve_meeting_id) && !empty($item->reserveMeeting) && !empty($item->reserveMeeting->meeting) && !empty($item->reserveMeeting->meeting->creator))
                                            {{ $item->reserveMeeting->meeting->creator->full_name }}
                                        @elseif(!empty($item->webinar))
                                            {{ $item->webinar->title }}
                                        @elseif(!empty($item->product))
                                            {{ $item->product->title }}
                                        @elseif(!empty($item->bundle))
                                            {{ $item->bundle->title }}
                                        @elseif(!empty($item->subscribe))
                                            {{ $item->subscribe->title }}
                                        @elseif(!empty($item->promotion))
                                            {{ trans('update.promotion') }}
                                        @elseif(!empty($item->registrationPackage))
                                            {{ trans('update.registration_package') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($item->reserve_meeting_id))
                                            {{ trans('update.meeting_reservation') }}
                                        @elseif(!empty($item->webinar))
                                            {{ trans('panel.webinar') }}
                                        @elseif(!empty($item->product))
                                            {{ trans('update.product') }}
                                        @elseif(!empty($item->bundle))
                                            {{ trans('update.bundle') }}
                                        @elseif(!empty($item->subscribe))
                                            {{ trans('update.subscription_package') }}
                                        @elseif(!empty($item->promotion))
                                            {{ trans('update.promotion_plan') }}
                                        @elseif(!empty($item->registrationPackage))
                                            {{ trans('update.registration_package') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">{{ handlePrice($item->total_amount) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


