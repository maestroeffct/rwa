@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.transactions') }}
                </div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.cashback_users')}}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-people class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalUsers }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_purchase')}}</span>
                                <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                    <x-iconsax-bul-dollar-square class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($totalPurchase) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_cashback')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-empty-wallet-change class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($totalCashback) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input name="title" type="text" class="form-control" value="{{ request()->get('title') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            @php
                                $filters = ['purchase_amount_asc', 'purchase_amount_desc', 'cashback_amount_asc', 'cashback_amount_desc', 'last_cashback_asc', 'last_cashback_desc'];
                            @endphp
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all')}}</option>

                                        @foreach($filters as $filter)
                                            <option value="{{ $filter }}" @if(request()->get('sort') == $filter) selected @endif>{{trans('update.'.$filter)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.user')}}</label>
                                    <select name="user_ids[]" multiple="multiple" class="form-control search-user-select2"
                                            data-placeholder="Search users">

                                        @if(!empty($selectedUsers) and $selectedUsers->count() > 0)
                                            @foreach($selectedUsers as $user)
                                                <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.min_purchase_amount')}}</label>
                                    <input type="text" name="min_purchase_amount" class="form-control" value="{{ request()->get('min_purchase_amount') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.min_cashback_amount')}}</label>
                                    <input type="text" name="min_cashback_amount" class="form-control" value="{{ request()->get('min_cashback_amount') }}">
                                </div>
                            </div>

                           <div class="col-md-3 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Lists --}}
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_cashback_transactions')
                                <div class="text-right">
                                    <a href="{{ getAdminPanelUrl('/cashback/history/excel?'. http_build_query(request()->all())) }}" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                                </div>
                            @endcan

                            </div>
                            </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th>{{trans('update.total_purchase')}}</th>
                                        <th>{{trans('update.total_cashback')}}</th>
                                        <th>{{trans('update.last_cashback')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($transactions as $transaction)
                                        <tr class="text-center">

                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($transaction->user))
                                                        <figure class="avatar mr-2">
                                                            <img src="{{ $transaction->user->getAvatar() }}" alt="{{ $transaction->user->full_name }}">
                                                        </figure>
                                                        <div class="media-body ml-1">
                                                            <div class="mt-0 mb-1">{{ $transaction->user->full_name }}</div>

                                                            @if($transaction->user->mobile)
                                                                <div class="text-gray-500 text-small ">{{ $transaction->user->mobile }}</div>
                                                            @endif

                                                            @if($transaction->user->email)
                                                                <div class="text-gray-500 text-small ">{{ $transaction->user->email }}</div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-danger fs-11">{{ trans('update.deleted_user') }}</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                {{ $transaction->purchase_amount ? handlePrice($transaction->purchase_amount) : '-' }}
                                            </td>

                                            <td>
                                                {{ handlePrice($transaction->total_cashback) }}
                                            </td>

                                            <td>{{ dateTimeFormat($transaction->last_cashback, 'j M Y') }}</td>

                                            <td class="text-center mb-2" width="120">
    @if(!empty($transaction->user))
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_users_impersonate')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $transaction->user_id }}/impersonate" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                </a>
            @endcan

            @can('admin_users_edit')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $transaction->user_id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_cashback_transactions')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl("/users/{$transaction->user_id}/disable_cashback_toggle"),
                    'btnClass' => 'dropdown-item text-' . ($transaction->user->disable_cashback ? 'success' : 'danger') . ' mb-0 py-3 px-0 font-14',
                    'btnText' => $transaction->user->disable_cashback ? trans('update.enable_cashback') : trans('update.disable_cashback'),
                    'btnIcon' => $transaction->user->disable_cashback ? 'tick-square' : 'close-square',
                    'iconType' => 'lin',
                    'iconClass' => 'text-' . ($transaction->user->disable_cashback ? 'success' : 'danger') . ' mr-2',
                ])
            @endcan
        </div>
    </div>
    @endif
</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $transactions->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
