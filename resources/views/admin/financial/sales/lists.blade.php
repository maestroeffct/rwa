@extends('admin.layouts.app')

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
                                <span class="text-gray-500 mt-8">{{trans('admin/main.total_sales')}}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-bag class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalSales['count'] }}</h5>
                            <div class="text-primary">{{ handlePrice($totalSales['amount']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.classes_sales')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-video-play class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $classesSales['count'] }}</h5>
                            <div class="text-primary">{{ handlePrice($classesSales['amount']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.appointments_sales')}}</span>
                                <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                    <x-iconsax-bul-calendar class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $appointmentSales['count'] }}</h5>
                            <div class="text-primary">{{ handlePrice($appointmentSales['amount']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('admin/main.faild_sales')}}</span>
                                <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                    <x-iconsax-bul-bag-cross class="icons text-danger" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $failedSales }}</h5>
                        </div>
                    </div>
                </div>
            </div>


            <section class="card mt-32">
                <div class="card-body pb-4">
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
                                        <option value="success" @if(request()->get('status') == 'success') selected @endif>{{ trans('admin/main.success') }}</option>
                                        <option value="refund" @if(request()->get('status') == 'refund') selected @endif>{{ trans('admin/main.refund') }}</option>
                                        <option value="blocked" @if(request()->get('status') == 'blocked') selected @endif>{{ trans('update.access_blocked') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.class') }}</label>
                                    <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                            data-placeholder="Search classes">

                                        @if(!empty($webinars) and $webinars->count() > 0)
                                            @foreach($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.instructor') }}</label>
                                    <select name="teacher_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                            data-placeholder="Search teachers">

                                        @if(!empty($teachers) and $teachers->count() > 0)
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" selected>{{ $teacher->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.student') }}</label>
                                    <select name="student_ids[]" multiple="multiple" data-search-option="just_student_role" class="form-control search-user-select2"
                                            data-placeholder="Search students">

                                        @if(!empty($students) and $students->count() > 0)
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" selected>{{ $student->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3 d-flex align-items-center ">
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
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_sales_in_a_single_place') }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">

                                @can('admin_sales_export')
                                    <div class="d-flex align-items-center gap-12">
                                        <a href="{{ getAdminPanelUrl() }}/financial/sales/export" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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
                                        <th class="text-left">{{ trans('admin/main.student') }}</th>
                                        <th class="text-left">{{ trans('admin/main.instructor') }}</th>
                                        <th>{{ trans('admin/main.paid_amount') }}</th>
                                        <th>{{ trans('admin/main.discount') }}</th>
                                        <th>{{ trans('admin/main.tax') }}</th>
                                        <th class="text-left">{{ trans('admin/main.item') }}</th>
                                        <th>{{ trans('admin/main.sale_type') }}</th>
                                        <th>{{ trans('admin/main.date') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($sales as $sale)
                                        <tr>
                                            <td>{{ $sale->id }}</td>

                                            <td class="text-left">
                                                {{ !empty($sale->buyer) ? $sale->buyer->full_name : '' }}
                                                <div class="text-gray-500 text-small ">ID : {{  !empty($sale->buyer) ? $sale->buyer->id : '' }}</div>
                                            </td>

                                            <td class="text-left">
                                                {{ $sale->item_seller }}
                                                <div class="text-gray-500 text-small ">ID : {{  $sale->seller_id }}</div>
                                            </td>

                                            <td>
                                                @if($sale->payment_method == \App\Models\Sale::$subscribe)
                                                    <span class="">{{ trans('admin/main.subscribe') }}</span>
                                                @else
                                                    @if(!empty($sale->total_amount))
                                                        <span class="">{{ handlePrice($sale->total_amount ?? 0) }}</span>
                                                    @else
                                                        <span class="">{{ trans('public.free') }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span class="">{{ handlePrice($sale->discount ?? 0) }}</span>
                                            </td>
                                            <td>
                                                <span class="">{{ handlePrice($sale->tax ?? 0) }}</span>
                                            </td>
                                            <td class="text-left">
                                                <div class="media-body">
                                                    <div>{{ $sale->item_title }}</div>
                                                    <div class="text-gray-500 text-small">ID : {{ $sale->item_id }}</div>
                                                </div>
                                            </td>

                                            <td>
                                                <span>
                                                    @if($sale->type == \App\Models\Sale::$registrationPackage)
                                                        {{ trans('update.registration_package') }}
                                                    @elseif($sale->type == \App\Models\Sale::$product)
                                                        {{ trans('update.product') }}
                                                    @elseif($sale->type == \App\Models\Sale::$bundle)
                                                        {{ trans('update.bundle') }}
                                                    @elseif($sale->type == \App\Models\Sale::$gift)
                                                        {{ trans('update.gift') }}
                                                    @elseif($sale->type == \App\Models\Sale::$installmentPayment)
                                                        {{ trans('update.installment_payment') }}
                                                    @else
                                                        {{ trans('admin/main.'.$sale->type) }}
                                                    @endif
                                                </span>
                                            </td>

                                            <td>{{ dateTimeFormat($sale->created_at, 'j F Y H:i') }}</td>

                                            <td>
                                                @if(!empty($sale->refund_at))
                                                    <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.refund') }}</span>
                                                @elseif(!$sale->access_to_purchased_item)
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('update.access_blocked') }}</span>
                                                @else
                                                    <span class="badge-status text-success bg-success-30">{{ trans('admin/main.success') }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @can('admin_sales_invoice')
                                                            @if(!empty($sale->webinar_id) or !empty($sale->bundle_id))
                                                                <a href="{{ getAdminPanelUrl() }}/financial/sales/{{ $sale->id }}/invoice"
                                                                   target="_blank"
                                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                    <x-iconsax-lin-printer class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.invoice') }}</span>
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @can('admin_sales_refund')
                                                            @if(empty($sale->refund_at) and $sale->payment_method != \App\Models\Sale::$subscribe)
                                                                @include('admin.includes.delete_button',[
                                                                    'url' => getAdminPanelUrl().'/financial/sales/'.$sale->id.'/refund',
                                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                    'btnText' => trans('admin/main.refund'),
                                                                    'btnIcon' => 'close-circle',
                                                                    'iconType' => 'lin',
                                                                    'iconClass' => 'text-danger mr-2'
                                                                ])
                                                            @endif
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
                            {{ $sales->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

