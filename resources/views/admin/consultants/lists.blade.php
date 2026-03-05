@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{trans('admin/main.consultants_list_title')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.consultants')}}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_consultants')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-profile-2user class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalConsultants }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.available_consultants')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-user-tick class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $availableConsultants }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.unavailable_consultants')}}</span>
                            <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                <x-iconsax-bul-user-remove class="icons text-secondary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $unavailableConsultants }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.consultants_without_appointment')}}</span>
                            <div class="d-flex-center size-48 bg-danger-30 rounded-12">
                                <x-iconsax-bul-user-minus class="icons text-danger" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $consultantsWithoutAppointment }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" class="form-control" name="search" value="{{ request()->get('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.filters')}}</label>
                                    <select name="sort" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.filter_type')}}</option>
                                        <option value="appointments_asc" @if(request()->get('sort') == 'appointments_asc') selected @endif>{{trans('admin/main.sales_appointments_ascending')}}</option>
                                        <option value="appointments_desc" @if(request()->get('sort') == 'appointments_desc') selected @endif>{{trans('admin/main.sales_appointments_descending')}}</option>
                                        <option value="appointments_income_asc" @if(request()->get('sort') == 'appointments_income_asc') selected @endif>{{trans('admin/main.appointments_income_ascending')}}</option>
                                        <option value="appointments_income_desc" @if(request()->get('sort') == 'appointments_income_desc') selected @endif>{{trans('admin/main.appointments_income_descending')}}</option>
                                        <option value="pending_appointments_asc" @if(request()->get('sort') == 'pending_appointments_asc') selected @endif>{{trans('admin/main.pending_appointments_ascending')}}</option>
                                        <option value="pending_appointments_desc" @if(request()->get('sort') == 'pending_appointments_desc') selected @endif>{{trans('admin/main.pending_appointments_descending')}}</option>
                                        <option value="created_at_asc" @if(request()->get('sort') == 'created_at_asc') selected @endif>{{trans('admin/main.register_date_ascending')}}</option>
                                        <option value="created_at_desc" @if(request()->get('sort') == 'created_at_desc') selected @endif>{{trans('admin/main.register_date_descending')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.organization')}}</label>
                                    <select name="organization_id" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.select_organization')}}</option>
                                        @foreach($organizations as $organization)
                                            <option value="{{ $organization->id }}" @if(request()->get('organization_id') == $organization->id) selected @endif>{{ $organization->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.users_group')}}</label>
                                    <select name="group_id" class="form-control populate">
                                        <option value="">{{trans('admin/main.select_users_group')}}</option>
                                        @foreach($userGroups as $userGroup)
                                            <option value="{{ $userGroup->id }}" @if(request()->get('group_id') == $userGroup->id) selected @endif>{{ $userGroup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="disabled" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_status')}}</option>
                                        <option value="0" @if(request()->get('disabled') == '0') selected @endif>{{trans('admin/main.available')}}</option>
                                        <option value="1" @if(request()->get('disabled') == '1') selected @endif>{{trans('admin/main.unavailable')}}</option>
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

            <div class="card">
                <div class="card-header justify-content-between">
                    <div>
                        <h5 class="font-14 mb-0">{{ trans('admin/main.consultants_list_title') }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_consultants_in_a_single_place') }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-12">
                        @can('admin_consultants_export_excel')
                            <a href="{{ getAdminPanelUrl() }}/consultants/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-center">
                        <table class="table custom-table font-14">
                            <tr>
                                <th>{{trans('admin/main.id')}}</th>
                                <th class="text-left">{{trans('admin/main.name')}}</th>
                                <th>{{trans('admin/main.appointments_sales')}}</th>
                                <th>{{trans('admin/main.pending_appointments')}}</th>
                                <th>{{trans('admin/main.wallet_charge')}}</th>
                                <th>{{trans('admin/main.user_group')}}</th>
                                <th>{{trans('admin/main.register_date')}}</th>
                                <th>{{trans('admin/main.status')}}</th>
                                <th width="120">{{trans('admin/main.actions')}}</th>

                            </tr>

                            @foreach($consultants as $consultant)
                                <tr>
                                    <td>{{ $consultant->id }}</td>

                                    <td class="text-left">
                                        <div class="d-flex align-items-center">
                                            <figure class="avatar mr-2">
                                                <img src="{{ $consultant->getAvatar() }}" alt="...">
                                            </figure>
                                            <div class="media-body ml-1">
                                                <div class="mt-0 mb-1 font-weight-bold">{{ $consultant->full_name }}</div>
                                                <div class="text-gray-500 text-small font-600-bold">{{ $consultant->mobile }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="media-body">
                                            <div class="text-dark mt-0 mb-1">{{ $consultant->meetingsSalesCount }}</div>

                                            @if($consultant->meetingsSalesSum > 0)
                                                <div class="text-small text-gray-500">{{ handlePrice($consultant->meetingsSalesSum) }}</div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        {{ $consultant->pendingAppointments }}
                                    </td>

                                    <td>
                                        {{ handlePrice($consultant->getAccountingBalance()) }}
                                    </td>

                                    <td>{{ !empty($consultant->userGroup) ? $consultant->userGroup->group->name : '-' }}</td>

                                    <td>{{ dateTimeFormat($consultant->created_at, 'j M Y | H:i') }}</td>

                                    <td>
                                        @if($consultant->disabled)
                                            <span class="badge-status text-danger bg-danger-30">{{trans('admin/main.unavailable')}}</span>
                                        @else
                                            <span class="badge-status text-success bg-success-30">{{trans('admin/main.available')}}</span>
                                        @endif
                                    </td>

                                    <td class="text-center" width="120">
                                    <div class="btn-group dropdown table-actions position-relative">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                        </button>
                                
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can('admin_users_impersonate')
                                                <a href="{{ getAdminPanelUrl() }}/users/{{ $consultant->id }}/impersonate" 
                                                   target="_blank"
                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                                                </a>
                                            @endcan
                                
                                            @can('admin_users_edit')
                                                <a href="{{ getAdminPanelUrl() }}/users/{{ $consultant->id }}/edit"
                                                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                </a>
                                            @endcan
                                
                                            @can('admin_users_delete')
                                                @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/users/'.$consultant->id.'/delete',
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans('admin/main.delete'),
                                                    'btnIcon' => 'trash',
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
                    {{ $consultants->appends(request()->input())->links() }}
                </div>
            </div>

            <section class="card">
                <div class="card-body">
                    <div class="section-title ml-0 mt-0 mb-3"><h4>{{trans('admin/main.hints')}}</h4></div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="media-body">
                                <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.consultants_hint_title_1')}}</div>
                                <div class=" text-small font-600-bold">{{trans('admin/main.consultants_hint_description_1')}}</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="media-body">
                                <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.consultants_hint_title_2')}}</div>
                                <div class=" text-small font-600-bold">{{trans('admin/main.consultants_hint_description_2')}}</div>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="media-body">
                                <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.consultants_hint_title_3')}}</div>
                                <div class="text-small font-600-bold">{{trans('admin/main.consultants_hint_description_3')}}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </section>

@endsection
