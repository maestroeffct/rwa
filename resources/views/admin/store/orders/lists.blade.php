@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.sales') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.sales') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_orders')}}</span>
                                <div class="d-flex-center size-48 bg-primary-40 rounded-12">
                                    <x-iconsax-bul-bag-2 class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalOrders['count'] }}</h5>
                            <span class="text-gray-500 font-14">{{ trans('admin/main.amount') }}: {{ handlePrice($totalOrders['amount']) }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_success_orders')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-bag-tick class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $successOrders['count'] }}</h5>
                            <span class="text-gray-500 font-14">{{ trans('admin/main.amount') }}: {{ handlePrice($successOrders['amount']) }}</span>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_waiting_orders')}}</span>
                                <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                    <x-iconsax-bul-bag-timer class="icons text-warning" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $waitingOrders['count'] }}</h5>
                            <span class="text-gray-500 font-14">{{ trans('admin/main.amount') }}: {{ handlePrice($waitingOrders['amount']) }}</span>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_canceled_orders')}}</span>
                                <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                    <x-iconsax-bul-bag-cross class="icons text-danger" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $canceledOrders['count'] }}</h5>
                            <span class="text-gray-500 font-14">{{ trans('admin/main.amount') }}: {{ handlePrice($canceledOrders['amount']) }}</span>
                        </div>
                    </div>
                </div>


            </div>


            <section class="card mt-30">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.search') }}</label>
                                    <input type="text" class="form-control" name="item_title" value="{{ request()->get('item_title') }}">
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.status') }}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_status') }}</option>
                                        @foreach(\App\Models\ProductOrder::$status as $str)
                                            @if($str != \App\Models\ProductOrder::$pending)
                                                <option value="{{ $str }}" @if(request()->get('status') == $str) selected @endif>{{ trans('update.product_order_status_'.$str) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.seller') }}</label>
                                    <select name="seller_ids[]" multiple="multiple" data-search-option="just_organization_and_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="{{ trans('update.search_seller') }}">

                                        @if(!empty($sellers) and $sellers->count() > 0)
                                            @foreach($sellers as $seller)
                                                <option value="{{ $seller->id }}" selected>{{ $seller->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('update.customer') }}</label>
                                    <select name="customer_ids[]" multiple="multiple" data-search-option="just_student_role" class="form-control search-user-select2"
                                            data-placeholder="{{ trans('public.search_user') }}">

                                        @if(!empty($customers) and $customers->count() > 0)
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" selected>{{ $customer->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>


                        </div>

                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header justify-content-between">
                            <div>
                                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">

                                @can('admin_store_products_orders_export')
                                    <div class="d-flex align-items-center gap-12">
                                        <a href="{{ getAdminPanelUrl() }}/store/orders/export?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                            <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                            <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                        </a>
                                    </div>
                                @endcan

                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">{{ trans('update.customer') }}</th>
                                        <th class="text-left">{{ trans('admin/main.seller') }}</th>
                                        <th>{{ trans('admin/main.type') }}</th>
                                        <th>{{ trans('update.quantity') }}</th>
                                        <th>{{ trans('admin/main.paid_amount') }}</th>
                                        <th>{{ trans('admin/main.discount') }}</th>
                                        <th>{{ trans('admin/main.tax') }}</th>
                                        <th>{{ trans('admin/main.date') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>

                                            <td class="text-left">
                                                @if(!empty($order->buyer))
                                                    {{ $order->buyer->full_name  }}
                                                    <div class="text-gray-500 text-small">ID : {{  $order->buyer->id }}</div>
                                                @elseif(!empty($order->gift) and !empty($order->gift))
                                                    {{ $order->gift->user->full_name }}
                                                    <div class="text-gray-500 text-small">ID : {{  $order->gift->user_id }}</div>
                                                    <span class="d-block mt-1 text-gray-500 font-12">{!! trans('update.a_gift_for_name_on_date',['name' => $order->gift->name, 'date' => (!empty($order->gift->date) ? dateTimeFormat($order->gift->date, 'j M Y H:i') : trans('update.instantly'))]) !!}</span>
                                                @endif
                                            </td>

                                            <td class="text-left">
                                                {{ !empty($order->seller) ? $order->seller->full_name : '' }}
                                                <div class="text-gray-500 text-small">ID : {{  !empty($order->seller) ? $order->seller->id : '' }}</div>
                                            </td>

                                            <td>
                                                @if(!empty($order->product))
                                                    <span>{{ trans('update.product_type_'.$order->product->type) }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span>{{ $order->quantity }}</span>
                                            </td>

                                            <td>
                                                @if(!empty($order->sale))
                                                    <span class="">{{ handlePrice($order->sale->total_amount) }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($order->sale))
                                                    <span class="">{{ handlePrice($order->sale->discount) }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($order->sale))
                                                    <span class="">{{ handlePrice($order->sale->tax) }}</span>
                                                @endif
                                            </td>

                                            <td>{{ dateTimeFormat($order->created_at, 'j F Y H:i') }}</td>

                                            <td>
                                                @if($order->status == \App\Models\ProductOrder::$waitingDelivery)
                                                    <span class="badge-status text-warning bg-warning-30">{{ trans('update.product_order_status_waiting_delivery') }}</span>
                                                @elseif($order->status == \App\Models\ProductOrder::$success)
                                                    <span class="badge-status text-success bg-success-30">{{ trans('update.product_order_status_success') }}</span>
                                                @elseif($order->status == \App\Models\ProductOrder::$shipped)
                                                    <span class="badge-status text-primary bg-primary-30">{{ trans('update.product_order_status_shipped') }}</span>
                                                @elseif($order->status == \App\Models\ProductOrder::$canceled)
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('update.product_order_status_canceled') }}</span>
                                                @endif
                                            </td>


                                            <td>
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can('admin_store_products_orders_invoice')
                                                            <a href="{{ getAdminPanelUrl() }}/store/orders/{{ $order->id }}/invoice" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <x-iconsax-lin-printer class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.invoice') }}</span>
                                                            </a>
                                                        @endcan

                                                        @if($order->status == \App\Models\ProductOrder::$waitingDelivery or !empty($order->tracking_code))
                                                            @can('admin_store_products_orders_tracking_code')
                                                                <a href="{{ getAdminPanelUrl("/store/orders/{$order->sale_id}/getProductOrder/{$order->id}") }}" class="js-enter-tracking-code dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                    <x-iconsax-lin-map class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                    <span class="text-gray-500 font-14">{{ !empty($order->tracking_code) ? trans('update.edit_tracking_code') : trans('update.enter_tracking_code') }}</span>
                                                                </a>
                                                            @endcan
                                                        @endif

                                                        @can('admin_store_products_orders_refund')

                                                            @include('admin.includes.delete_button',[
                                                               'url' => getAdminPanelUrl().'/store/orders/'. $order->id .'/refund',
                                                               'btnClass' => 'dropdown-item text-warning mb-0 py-3 px-0 font-14',
                                                               'btnText' => trans('admin/main.refund'),
                                                               'btnIcon' => 'rotate-left',
                                                               'iconType' => 'lin',
                                                               'iconClass' => 'text-warning mr-2',
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

@push('scripts_bottom')
    <script>
        var enterTrackingCodeModalTitleLang = '{{ trans('update.enter_tracking_code') }}';
        var saveLang = '{{ trans('public.save') }}';
        var cancelLang = '{{ trans('public.close') }}';
    </script>


    <script src="/assets/admin/js/parts/store/orders.min.js"></script>
@endpush
