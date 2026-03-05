@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ trans('update.registration_bonus') }}
                </div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.achieved_users')}}</span>
                                <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                    <x-iconsax-bul-profile-2user class="icons text-primary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $achievedUsers }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.unlocked_bonus_users')}}</span>
                                <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                    <x-iconsax-bul-user-tick class="icons text-success" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ $unlockedBonusUsers }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.unlocked_bonus')}}</span>
                                <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                    <x-iconsax-bul-unlock class="icons text-accent" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($unlockedBonus) }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card-statistic">
                        <div class="card-statistic__mask"></div>
                        <div class="card-statistic__wrap">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="text-gray-500 mt-8">{{trans('update.total_bonus')}}</span>
                                <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                    <x-iconsax-bul-dollar-square class="icons text-secondary" width="24px" height="24px"/>
                                </div>
                            </div>
                            <h5 class="font-24 mt-12 line-height-1 text-black">{{ handlePrice($totalBonus) }}</h5>
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
                                $filters = ['registration_date_asc', 'registration_date_desc', 'referred_users_asc', 'referred_users_desc', 'bonus_asc', 'bonus_desc'];
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
                                    <label class="input-label">{{trans('admin/main.user')}}</label>
                                    <select name="role_id" class="form-control select2" data-allow-clear="true"
                                            data-placeholder="{{ trans('update.select_user_role') }}">
                                        <option value="">{{ trans('admin/main.all') }}</option>

                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ (request()->get('role_id') == $role->id) ? 'selected' : '' }}>{{ $role->caption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            {{--<div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.bonus_wallet')}}</label>
                                    <select name="bonus_wallet" class="form-control populate">
                                        <option value="">{{trans('admin/main.all')}}</option>

                                        <option value="income_wallet" {{ (request()->get('bonus_wallet') == 'income_wallet') ? 'selected' : '' }}>{{ trans('update.income_wallet') }}</option>
                                        <option value="balance_wallet" {{ (request()->get('bonus_wallet') == 'balance_wallet') ? 'selected' : '' }}>{{ trans('update.balance_wallet') }}</option>
                                    </select>
                                </div>
                            </div>--}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.bonus_status')}}</label>
                                    <select name="bonus_status" class="form-control populate">
                                        <option value="">{{trans('admin/main.all')}}</option>

                                        <option value="locked" {{ (request()->get('bonus_status') == 'locked') ? 'selected' : '' }}>{{ trans('update.locked') }}</option>
                                        <option value="unlocked" {{ (request()->get('bonus_status') == 'unlocked') ? 'selected' : '' }}>{{ trans('update.unlocked') }}</option>
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

                             @can('admin_registration_bonus_export_excel')
                                <div class="d-flex align-items-center gap-12">
                                    <a href="{{ getAdminPanelUrl('/registration_bonus/export?'. http_build_query(request()->all())) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                    </a>
                                </div>
                            @endcan

                             </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('update.user_id') }}</th>
                                        <th class="text-left">{{ trans('admin/main.name') }}</th>
                                        <th class="text-left">{{trans('admin/main.role')}}</th>

                                        <th>{{trans('update.bonus')}}</th>

                                        @if (!empty($registrationBonusSettings['unlock_registration_bonus_with_referral']) and !empty($registrationBonusSettings['number_of_referred_users']))
                                            <th>{{trans('update.referred_users')}}</th>

                                            @if (!empty($registrationBonusSettings['enable_referred_users_purchase']))
                                                <th>{{trans('update.referred_purchases')}}</th>
                                            @endif
                                        @endif

                                        {{--<th>{{trans('update.bonus_wallet')}}</th>--}}
                                        <th>{{trans('update.registration_date')}}</th>
                                        <th>{{trans('update.bonus_status')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($users as $user)
                                        <tr class="text-center">
                                            <td>{{ $user->id }}</td>

                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar mr-2">
                                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}">
                                                    </figure>
                                                    <div class="media-body ml-1">
                                                        <div class="mt-0 mb-1">{{ $user->full_name }}</div>

                                                        @if($user->mobile)
                                                            <div class="text-gray-500 text-small">{{ $user->mobile }}</div>
                                                        @endif

                                                        @if($user->email)
                                                            <div class="text-gray-500 text-small">{{ $user->email }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-left">{{ $user->role->caption }}</td>

                                            <td>
                                                {{ handlePrice($user->registration_bonus_amount ?? 0) }}
                                            </td>

                                            @if (!empty($registrationBonusSettings['unlock_registration_bonus_with_referral']) and !empty($registrationBonusSettings['number_of_referred_users']))
                                                <td>{{ $user->referred_users }}</td>

                                                @if (!empty($registrationBonusSettings['enable_referred_users_purchase']))
                                                    <td>{{ $user->referred_purchases }}</td>
                                                @endif
                                            @endif


                                            {{--<td>{{ !empty($user->bonus_wallet) ? trans('update.'.$user->bonus_wallet) : '-' }}</td>--}}

                                            <td>{{ dateTimeFormat($user->created_at, 'j M Y') }}</td>

                                                <td>
                                                     <span class="badge-status {{ ($user->bonus_status == 'Lock') ? 'text-danger bg-danger-30' : 'text-success bg-success-30' }}">{{ $user->bonus_status }}</span>
                                                </td>
                                        
                                                <td class="text-center mb-2" width="120">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_users_impersonate')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/impersonate" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                </a>
            @endcan

            @can('admin_users_edit')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_users_edit')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl("/users/{$user->id}/disable_registration_bonus"),
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-12',
                    'btnText' => trans('update.disable_registration_bonus'),
                    'btnIcon' => 'close-square',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2',
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
                            {{ $users->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

