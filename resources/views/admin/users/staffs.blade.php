@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@php
    $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
@endphp

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input name="full_name" type="text" class="form-control" value="{{ request()->get('full_name') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.role') }}</label>
                                    <select name="role_id" class="form-control">
                                        <option value="">{{ trans('public.all') }}</option>
                                        @foreach($staffsRoles as $role)
                                            <option value="{{ $role->id }}" @if(!empty(request()->get('role_id')) and request()->get('role_id') == $role->id) selected @endif>{{ $role->caption }}</option>
                                        @endforeach
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
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_staff_in_a_single_place') }}</p>
                           </div>                           
                           
                       </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.id') }}</th>
                                        <th class="text-left">{{ trans('admin/main.name') }}</th>
                                        <th>{{ trans('admin/main.role_name') }}</th>
                                        <th>{{ trans('admin/main.register_date') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th width="80">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <figure class="avatar mr-2">
                                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}">
                                                    </figure>
                                                    <div class="media-body ml-1">
                                                        <div class="mt-0 mb-1 font-weight-bold">{{ $user->full_name }}</div>

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

                                            <td class="text-center">{{ $user->role->caption }}</td>
                                            <td>{{ dateTimeFormat($user->created_at, 'j M Y | H:i') }}</td>

                                            <td>
                                                <div class="media-body">
                                                    @if($user->ban and !empty($user->ban_end_at) and $user->ban_end_at > time())
                                                    <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.ban') }}</span>
                                                    <div class="text-small font-12 text-gray-500">Until {{ dateTimeFormat($user->ban_end_at, 'Y/m/j') }}</div>
                                                    @else
                                                    <span class="badge-status {{ ($user->status == 'active') ? 'text-success bg-success-30' : 'text-warning bg-warning-30' }}">{{ trans('admin/main.'.$user->status) }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                       

                                            <td>
                                             <div class="btn-group dropdown table-actions position-relative">
                                                 <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                     <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                 </button>

                                                 <div class="dropdown-menu dropdown-menu-right">
                                                     @can('admin_users_edit')
                                                         <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
