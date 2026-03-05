@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.offline_payments') }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form class="mb-0">
                        <input type="hidden" name="page_type" value="{{ $pageType }}">

                        <div class="row">
                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-4 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" class="form-control text-center" name="search" value="{{ request()->get('search') }}">
                                </div>
                            </div>

                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-4 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>

                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-4 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>

                            @if($pageType == 'history')
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="input-label">{{trans('admin/main.status')}}</label>
                                        <select name="status" data-plugin-selectTwo class="form-control populate">
                                            <option value="">{{trans('admin/main.all_status')}}</option>
                                            <option value="approved" @if(request()->get('status') == 'approved') selected @endif>{{trans('admin/main.approved')}}</option>
                                            <option value="reject" @if(request()->get('status') == 'reject') selected @endif>{{trans('admin/main.rejected')}}</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.role')}}</label>
                                    <select name="role_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_roles')}}</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if($role->id == request()->get('role_id')) selected @endif>{{ $role->caption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.user')}}</label>
                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search teachers">

                                        @if(!empty($users) and $users->count() > 0)
                                            @foreach($users as $user_filter)
                                                <option value="{{ $user_filter->id }}" selected>{{ $user_filter->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.bank')}}</label>
                                    <select name="account_type" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_banks')}}</option>

                                        @foreach($offlineBanks as $offlineBank)
                                            <option value="{{ $offlineBank->id }}" @if(request()->get('account_type') == $offlineBank->id) selected @endif>{{ $offlineBank->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="@if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">Filter Type</option>
                                        <option value="amount_asc" @if(request()->get('sort') == 'amount_asc') selected @endif>{{trans('admin/main.amount_ascending')}}</option>
                                        <option value="amount_desc" @if(request()->get('sort') == 'amount_desc') selected @endif>{{trans('admin/main.amount_descending')}}</option>
                                        <option value="pay_date_asc" @if(request()->get('sort') == 'pay_date_asc') selected @endif>{{trans('admin/main.Transaction_time_ascending')}}</option>
                                        <option value="pay_date_desc" @if(request()->get('sort') == 'pay_date_desc') selected @endif>{{trans('admin/main.Transaction_time_descending')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="d-flex align-items-center @if($pageType == 'requests') col-md-3 @else col-md-2 @endif">
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
                               <h5 class="font-14 mb-0">{{ trans('admin/main.offline_payments') }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_offline_payments_export_excel')
                                <div class="d-flex align-items-center gap-12">
                                    <a href="{{ getAdminPanelUrl() }}/financial/offline_payments/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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
                                        <th>{{trans('admin/main.user')}}</th>
                                        <th>{{trans('admin/main.role')}}</th>
                                        <th>{{trans('admin/main.amount')}}</th>
                                        <th>{{trans('admin/main.bank')}}</th>
                                        <th>{{trans('admin/main.referral_code')}}</th>
                                        <th>{{trans('admin/main.phone')}}</th>
                                        <th>{{ trans('admin/main.type') }}</th>
                                        <th>{{trans('update.attachment')}}</th>
                                        <th width=180px>{{trans('admin/main.transaction_time')}}</th>

                                        @if($pageType == 'history')
                                            <th>{{trans('admin/main.status')}}</th>
                                        @endif

                                        @if($pageType == 'requests')
                                            <th width="150px">{{trans('admin/main.actions')}}</th>
                                        @endcan
                                    </tr>

                                    @if($offlinePayments->count() > 0)
                                        @foreach($offlinePayments as $offlinePayment)
                                            <tr>
                                                <td class="text-left">
                                                    {{ $offlinePayment->user->full_name }}
                                                </td>

                                                <td>{{ $offlinePayment->user->role->caption }}</td>

                                                <td>
                                                    {{ handlePrice($offlinePayment->amount, true, false) }}
                                                </td>

                                                @if(!empty($offlinePayment->offlineBank->title))
                                                <td>{{ $offlinePayment->offlineBank->title }}</td>
                                               @else
                                                <td>-</td>
                                                @endif

                                                <td>
                                                    <span>{{ $offlinePayment->reference_number }}</span>
                                                </td>

                                                <td>{{ $offlinePayment->user->mobile }}</td>

                                                <td>
                                                    @if($offlinePayment->type === \App\Models\OfflinePayment::$typeCart)
                                                        <span class="badge-status text-info bg-info-30">Cart checkout</span>
                                                    @else
                                                        <span class="badge-status text-gray-600 bg-gray-100">Charge wallet</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if(!empty($offlinePayment->attachment))
                                                        <a href="{{ $offlinePayment->getAttachmentPath() }}" target="_blank" class="text-primary">{{ trans('public.view') }}</a>
                                                    @else
                                                        ---
                                                    @endif
                                                </td>

                                                <td>{{ dateTimeFormat($offlinePayment->pay_date, 'j M Y H:i') }}</td>

                                                @if($pageType == 'history')
                                                    <td>
                                                        <span class="{{ ($offlinePayment->status == 'approved') ? 'text-success' : 'text-danger' }}">
                                                            @if($offlinePayment->status == 'approved')
                                                                <span class="badge-status text-success bg-success-30">{{ trans('financial.approved') }}</span>
                                                            @else
                                                                <span class="badge-status text-danger bg-danger-30">{{ trans('public.rejected') }}</span>
                                                            @endif
                                                        </span>
                                                    </td>
                                                @endif

                                                @if($pageType == 'requests')
                                                <td>
    <div class="btn-group dropdown table-actions position-relative">
        @if($offlinePayment->status == \App\Models\OfflinePayment::$waiting)
            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
            </button>

                    <div class="dropdown-menu dropdown-menu-right">

                        @can('admin_offline_payments_approved')
                            @include('admin.includes.delete_button',[
                                'url' => getAdminPanelUrl().'/financial/offline_payments/'.$offlinePayment->id.'/approved',
                                'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                                'btnText' => trans('financial.approve'),
                                'btnIcon' => 'tick-circle',
                                'iconType' => 'lin',
                                'iconClass' => 'text-success mr-2'
                            ])
                        @endcan

                        @can('admin_offline_payments_reject')
                            @include('admin.includes.delete_button',[
                                'url' => getAdminPanelUrl().'/financial/offline_payments/'.$offlinePayment->id.'/reject',
                                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                'btnText' => trans('public.reject'),
                                'btnIcon' => 'close-circle',
                                'iconType' => 'lin',
                                'iconClass' => 'text-danger mr-2'
                            ])
                        @endcan
                       
                        @if($offlinePayment->type === \App\Models\OfflinePayment::$typeCart && !empty($offlinePayment->order_id))
                         <div class="dropdown-divider"></div>
                            <a href="{{ getAdminPanelUrl() }}/financial/offline_payments/{{ $offlinePayment->id }}/cart-items" class="dropdown-item d-flex align-items-center py-3 px-0 gap-4">
                                <x-iconsax-lin-bag-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                <span class="text-gray-500 font-14">{{ trans('update.cart_items') }}</span>
                            </a>
                        @endif
                    </div>
                @endif
             </div>
            </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $offlinePayments->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.offline_payment_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.offline_payment_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.offline_payment_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.offline_payment_hint_description_2')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

