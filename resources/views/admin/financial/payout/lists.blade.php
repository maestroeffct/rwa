@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.payouts') }}</div>
            </div>
        </div>


        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <input type="hidden" name="payout" value="{{ request()->get('payout') }}">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.search') }}</label>
                                    <input type="text" class="form-control text-center" name="search" value="{{ request()->get('search') }}">
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
                                    <label class="input-label">{{ trans('admin/main.role') }}</label>
                                    <select name="role_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_roles') }}</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @if($role->id == request()->get('role_id')) selected @endif>{{ $role->caption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.user') }}</label>
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.bank') }}</label>
                                    <select name="account_type" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{ trans('admin/main.all_banks') }}</option>

                                        @foreach($offlineBanks as $offlineBank)
                                            <option value="{{ $offlineBank->id }}" @if(request()->get('account_type') == $offlineBank->id) selected @endif>{{ $offlineBank->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.filters') }}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">Filter Type</option>
                                        <option value="amount_asc" @if(request()->get('sort') == 'amount_asc') selected @endif>{{ trans('admin/main.amount_ascending') }}</option>
                                        <option value="amount_desc" @if(request()->get('sort') == 'amount_desc') selected @endif>{{ trans('admin/main.amount_descending') }}</option>
                                        <option value="created_at_asc" @if(request()->get('sort') == 'created_at_asc') selected @endif>{{ trans('admin/main.last_payout_date_ascending') }}</option>
                                        <option value="created_at_desc" @if(request()->get('sort') == 'created_at_desc') selected @endif>{{ trans('admin/main.last_payout_date_descending') }}</option>
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
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_payout_in_a_single_place') }}</p>
                           </div>

                            <div class="d-flex align-items-center gap-12">

                            @can('admin_payouts_export_excel')
                                   <a href="{{ getAdminPanelUrl() }}/financial/payouts/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                       <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                   </a>
                            @endcan


                            </div>

                       </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.user') }}</th>
                                        <th>{{ trans('admin/main.role') }}</th>
                                        <th>{{ trans('admin/main.payout_amount') }}</th>
                                        <th class="">{{ trans('admin/main.bank') }}</th>

                                        <th>{{ trans('admin/main.phone') }}</th>
                                        <th width="180px">{{ trans('admin/main.last_payout_date') }}</th>

                                        @if(request()->get('payout') == 'history')
                                            <th>{{ trans('admin/main.status') }}</th>
                                        @endif

                                        <th width="150px">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @if($payouts->count() > 0)
                                        @foreach($payouts as $payout)

                                            <tr>
                                                <td class="text-left">
                                                    <span class="d-block">{{ $payout->user->full_name }}</span>
                                                </td>

                                                <td>{{ $payout->user->role->caption }}</td>

                                                <td>{{ handlePrice($payout->amount) }}</td>


                                                @if(!empty($payout->userSelectedBank->bank))
                                                <td class="">
                                                    @php
                                                        $bank = $payout->userSelectedBank->bank;
                                                    @endphp
                                                    <div class="font-weight-500">{{ $bank->title }}</div>

                                                    {{-- For Modal --}}
                                                    <input type="hidden" class="js-bank-details" data-name="{{ trans("admin/main.bank") }}" value="{{ $bank->title }}">
                                                    @foreach($bank->specifications as $specification)
                                                        @php
                                                            $selectedBankSpecification = $payout->userSelectedBank->specifications->where('user_selected_bank_id', $payout->userSelectedBank->id)->where('user_bank_specification_id', $specification->id)->first();
                                                        @endphp

                                                        @if(!empty($selectedBankSpecification))
                                                            <input type="hidden" class="js-bank-details" data-name="{{ $specification->name }}" value="{{ $selectedBankSpecification->value }}">
                                                        @endif
                                                    @endforeach

                                                </td>
                                                @else
                                                <td>-</td>
                                                @endif

                                                <td>{{ $payout->user->mobile }}</td>

                                                <td>{{ dateTimeFormat($payout->created_at, 'j M Y H:i') }}</td>

                                                @if(request()->get('payout') == 'history')
                                                    <td>
                                                        <span class="badge-status {{ ($payout->status == 'done') ? 'text-success bg-success-30' : 'text-danger bg-danger-30' }}">{{ trans('public.'.$payout->status) }}</span>
                                                    </td>
                                                @endif


                                                <td width="80px">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <button type="button" class="js-show-details dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                <span class="text-gray-500 font-14">{{ trans('update.show_details') }}</span>
            </button>

            @if(request()->get('payout') == 'requests' and $payout->status === \App\Models\Payout::$waiting)
                @can('admin_payouts_payout')
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl().'/financial/payouts/'.$payout->id.'/payout',
                        'btnClass' => 'dropdown-item text-success mb-3 py-3 px-0 font-14',
                        'btnText' => trans('admin/main.payout'),
                        'btnIcon' => 'card',
                        'iconType' => 'lin',
                        'iconClass' => 'text-success mr-2'
                    ])
                @endcan

                @can('admin_payouts_reject')
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl().'/financial/payouts/'.$payout->id.'/reject',
                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                        'btnText' => trans('public.reject'),
                        'btnIcon' => 'close-circle',
                        'iconType' => 'lin',
                        'iconClass' => 'text-danger mr-2'
                    ])
                @endcan
            @endif
        </div>
    </div>
</td>

                                            </tr>
                                        @endforeach
                                    @endif

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $payouts->appends(request()->input())->links() }}
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
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.payout_list_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.payout_list_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.payout_list_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.payout_list_hint_description_2')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    <script>
        var payoutDetailsLang = '{{ trans('update.payout_details') }}';
        var closeLang = '{{ trans('public.close') }}';
    </script>


    <script src="/assets/admin/js/parts/payout.min.js"></script>
@endpush
