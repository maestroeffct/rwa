@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@php
    $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
@endphp

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.organizations_list') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">{{ trans('admin/main.organizations_list') }}</a></div>
                <div class="breadcrumb-item"><a href="#">{{ trans('admin/main.users_list') }}</a></div>
            </div>
        </div>
    </section>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.total_organizations')}}</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <x-iconsax-bul-courthouse class="icons text-primary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalOrganizations }}</h5>
                    </div>
                </div>
            </div> 
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.verified_organizations')}}</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <x-iconsax-bul-award class="icons text-success" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $verifiedOrganizations }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.organizations_instructors')}}</span>
                            <div class="d-flex-center size-48 bg-secondary-30 rounded-12">
                                <x-iconsax-bul-teacher class="icons text-secondary" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalOrganizationsTeachers }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">{{trans('admin/main.organizations_students')}}</span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <x-iconsax-bul-briefcase class="icons text-accent" width="24px" height="24px"/>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalOrganizationsStudents }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <section class="card mt-32">
            <div class="card-body pb-4">
                <form method="get" class="mb-0">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                <input name="full_name" type="text" class="form-control" value="{{ request()->get('full_name') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.filters') }}</label>
                                <select name="sort" data-plugin-selectTwo class="form-control populate">
                                    <option value="">{{ trans('admin/main.filter_type') }}</option>
                                    <option value="sales_classes_asc" @if(request()->get('sort') == 'sales_classes_asc') selected @endif>{{ trans('admin/main.classes_sales_ascending') }}</option>
                                    <option value="sales_classes_desc" @if(request()->get('sort') == 'sales_classes_desc') selected @endif>{{ trans('admin/main.classes_sales_descending') }}</option>
                                    <option value="purchased_classes_asc" @if(request()->get('sort') == 'purchased_asc') selected @endif>{{ trans('admin/main.purchased_classes_ascending') }}</option>
                                    <option value="purchased_classes_desc" @if(request()->get('sort') == 'purchased_desc') selected @endif>{{ trans('admin/main.purchased_classes_descending') }}</option>
                                    <option value="sales_appointments_asc" @if(request()->get('sort') == 'appointments_asc') selected @endif>{{ trans('admin/main.sales_appointments_ascending') }}</option>
                                    <option value="sales_appointments_desc" @if(request()->get('sort') == 'appointments_desc') selected @endif>{{ trans('admin/main.sales_appointments_descending') }}</option>
                                    <option value="purchased_appointments_asc" @if(request()->get('sort') == 'purchased_appointments_asc') selected @endif>{{ trans('admin/main.purchased_appointments_ascending') }}</option>
                                    <option value="purchased_appointments_desc" @if(request()->get('sort') == 'purchased_appointments_desc') selected @endif>{{ trans('admin/main.purchased_appointments_descending') }}</option>
                                    <option value="register_asc" @if(request()->get('sort') == 'register_asc') selected @endif>{{ trans('admin/main.register_date_ascending') }}</option>
                                    <option value="register_desc" @if(request()->get('sort') == 'register_desc') selected @endif>{{ trans('admin/main.register_date_descending') }}</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.users_group') }}</label>
                                <select name="group_id" data-plugin-selectTwo class="form-control populate">
                                    <option value="">{{ trans('admin/main.select_users_group') }}</option>
                                    @foreach($userGroups as $userGroup)
                                        <option value="{{ $userGroup->id }}" @if(request()->get('group_id') == $userGroup->id) selected @endif>{{ $userGroup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.status') }}</label>
                                <select name="status" data-plugin-selectTwo class="form-control populate">
                                    <option value="">{{ trans('admin/main.all_status') }}</option>
                                    <option value="active_verified" @if(request()->get('status') == 'active_verified') selected @endif>{{ trans('admin/main.active_verified') }}</option>
                                    <option value="active_notVerified" @if(request()->get('status') == 'active_notVerified') selected @endif>{{ trans('admin/main.active_not_verified') }}</option>
                                    <option value="inactive" @if(request()->get('status') == 'inactive') selected @endif>{{ trans('admin/main.inactive') }}</option>
                                    <option value="ban" @if(request()->get('status') == 'ban') selected @endif>{{ trans('admin/main.banned') }}</option>
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
    </div>

    <div class="card">
      
         <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_organizations_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            @can('admin_users_export_excel')
                                   <a href="{{ getAdminPanelUrl() }}/organizations/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                       <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                   </a>
                               @endcan

                               @can('admin_quizzes_create')
                                   <a href="{{ getAdminPanelUrl() }}/users/create" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.new') }} {{ trans('admin/main.user') }}</span>
                                   </a>
                               @endcan

                            </div>
                           
                       </div>

        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table custom-table font-14">
                    <tr>
                        <th>{{ trans('admin/main.id') }}</th>
                        <th>{{ trans('admin/main.name') }}</th>
                        <th>{{ trans('admin/main.classes_sales') }}</th>
                        <th>{{ trans('admin/main.appointments_sales') }}</th>
                        <th>{{ trans('admin/main.purchased_classes') }}</th>
                        <th>{{ trans('admin/main.purchased_appointments') }}</th>
                        <th>{{ trans('admin/main.instructors') }}</th>
                        <th>{{ trans('admin/main.students') }}</th>
                        <th>{{ trans('admin/main.register_date') }}</th>
                        <th>{{ trans('admin/main.status') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($users as $user)

                        <tr>
                            <td>
                                <a href="{{ $user->getProfileUrl() }}" class="text-dark" target="_blank">{{ $user->id }}</a>
                            </td>

                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <figure class="avatar mr-2" style="position: relative;">
                                            <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}">
                                            @if($user->verified)
                                                <div style="position: absolute; right: 1px; top: 1px; width: 12px; height: 12px; background-color: #3498db; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 1;" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.verified_organization') }}">
                                                    <i class="fa fa-check text-white" style="font-size: 8px;"></i>
                                                </div>
                                            @endif
                                        </figure>
                                    </div>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1">{{ $user->full_name }}</div>

                                        @if($registerMethod == 'mobile')
                                        @if($user->mobile)
                                            <div class="text-small font-12 text-gray-500">{{ $user->mobile }}</div>
                                        @endif
                                        @else
                                        @if($user->email)
                                            <div class="text-small font-12 text-gray-500">{{ $user->email }}</div>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </td>




                            <td>
                                <div class="media-body">
                                    <div class="mt-0 mb-1">{{ $user->classesSalesCount }}</div>
                                    <div class="text-small font-12 text-gray-500">{{ handlePrice($user->classesSalesSum) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="media-body">
                                    <div class="mt-0 mb-1">{{ $user->meetingsSalesCount }}</div>
                                    <div class="text-small font-12 text-gray-500">{{ handlePrice($user->meetingsSalesSum) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="media-body">
                                    <div class="mt-0 mb-1">{{ $user->classesPurchasedsCount }}</div>
                                    <div class="text-small font-12 text-gray-500">{{ handlePrice($user->classesPurchasedsSum) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="media-body">
                                    <div class="mt-0 mb-1">{{ $user->meetingsPurchasedsCount }}</div>
                                    <div class="text-small font-12 text-gray-500">{{ handlePrice($user->meetingsPurchasedsSum) }}</div>
                                </div>
                            </td>
                            <td class="mt-0 mb-1">{{ $user->getOrganizationTeachers()->count() }}</td>
                            <td class="mt-0 mb-1">{{ $user->getOrganizationStudents()->count() }}</td>

                            <td>{{ dateTimeFormat($user->created_at, 'j M Y - H:i') }}</td>

                            <td>
                                <div class="media-body">
                                    @if($user->ban and !empty($user->ban_end_at) and $user->ban_end_at > time())
                                    <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.ban') }}</span>
                                        <div class="text-small font-12 text-gray-500">Until {{ dateTimeFormat($user->ban_end_at, 'j M Y') }}</div>
                                    @else
                                    <span class="badge-status {{ ($user->status == 'active') ? 'text-success bg-success-30' : 'text-warning bg-warning-30' }}">{{ trans('admin/main.'.$user->status) }}</span>
                                    @endif
                                </div>
                            </td>
                            
     <td class="text-center" width="120">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_users_impersonate')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/impersonate" 
                   target="_blank"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                </a>
            @endcan

            @can('admin_users_edit')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_users_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/users/'.$user->id.'/delete',
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans('admin/main.delete'),
                    'btnIcon' => 'trash',
                    'iconType' => 'lin',
                    'iconClass' => 'text-danger mr-2',
                    'deleteConfirmMsg' => trans('update.user_delete_confirm_msg')
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


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h4>{{trans('admin/main.hints')}}</h4></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.organizations_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.organizations_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.organizations_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.organizations_hint_description_2')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.organizations_hint_title_3')}}</div>
                        <div class="text-small font-600-bold">{{trans('admin/main.organizations_hint_description_3')}}</div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
