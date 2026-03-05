@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.enrollment_history') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.enrollment_history') }}</div>
            </div>
        </div>

        <div class="section-body">


            <section class="card">
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
                                            data-placeholder="{{ trans('update.search_instructor') }}">

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
                                            data-placeholder="{{ trans('webinars.select_student') }}">

                                        @if(!empty($students) and $students->count() > 0)
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" selected>{{ $student->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('admin/main.show_results') }}">
                                </div>
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
                               <h5 class="font-14 mb-0">{{ trans('update.enrollment') }} {{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_enrollment_export')
                                <div class="d-flex align-items-center gap-12">
                                    <a href="{{ getAdminPanelUrl() }}/enrollments/export" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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
                                        <th class="text-left">{{ trans('admin/main.item') }}</th>
                                        <th>{{ trans('admin/main.type') }}</th>
                                        <th>{{ trans('admin/main.date') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($sales as $sale)
                                        <tr>
                                            <td>{{ $sale->id }}</td>

                                            <td class="text-left">
                                                {{ !empty($sale->buyer) ? $sale->buyer->full_name : '' }}
                                                <div class="font-12 text-muted text-small">ID : {{  !empty($sale->buyer) ? $sale->buyer->id : '' }}</div>
                                            </td>

                                            <td class="text-left">
                                                {{ $sale->item_seller }}
                                                <div class="font-12 text-muted text-small">ID : {{  $sale->seller_id }}</div>
                                            </td>

                                            <td class="text-left">
                                                <div class="media-body">
                                                    <div>{{ $sale->item_title }}</div>
                                                    <div class="font-12 text-muted text-small">ID : {{ $sale->item_id }}</div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class=" text-center">
                                                    @if($sale->manual_added)
                                                        <span class="text-warning">{{ trans('public.manual') }}</span>
                                                    @else
                                                        {{ trans('update.normal_purchased') }}
                                                    @endif
                                                </span>
                                            </td>

                                            <td>{{ dateTimeFormat($sale->created_at, 'j F Y H:i') }}</td>

                                            <td>
                                                @if(!empty($sale->refund_at))
                                                    <div class="badge-status text-warning bg-warning-30">{{ trans('admin/main.refund') }}</div>
                                                @elseif(!$sale->access_to_purchased_item)
                                                    <div class="badge-status text-danger bg-danger-30">{{ trans('update.access_blocked') }}</div>
                                                @else
                                                    <div class="badge-status text-success bg-success-30">{{ trans('admin/main.success') }}</div>
                                                @endif
                                            </td>

                                           
                                            <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                    <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                        <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">
                                                    @can('admin_sales_invoice')
                                                            <a href="{{ getAdminPanelUrl() }}/financial/sales/{{ $sale->id }}/invoice" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <x-iconsax-lin-chart class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                                <span class="text-gray-500 font-14">{{ trans('admin/main.invoice') }}</span>
                                                            </a>
                                                        @endcan

                                                        @if($sale->access_to_purchased_item)
                                                        @can('admin_enrollment_block_access')
                                                            @include('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/enrollments/'. $sale->id .'/block-access',
                                                           'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                           'btnText' => trans('update.block_access'),
                                                           'btnIcon' => 'trash',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-danger mr-2',
                                                        ])
                                                        @endcan
                                                        @else
                                                        @can('admin_enrollment_enable_access')
                                                        @include('admin.includes.delete_button',[
                                                            'url' => getAdminPanelUrl().'/enrollments/'. $sale->id .'/enable-access',
                                                           'btnClass' => 'dropdown-item text-success mb-0 py-3 px-0 font-14',
                                                           'btnText' => trans('update.enable-student-access'),
                                                           'btnIcon' => 'tick-square',
                                                           'iconType' => 'lin',
                                                           'iconClass' => 'text-success mr-2',
                                                        ])
                                                        @endcan
                                                        @endif
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

