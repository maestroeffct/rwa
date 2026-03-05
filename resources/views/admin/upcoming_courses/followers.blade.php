@extends('admin.layouts.app')


@push('styles_top')


    <style>
        .select2-container {
            z-index: 1212 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item"><a href="{{ getAdminPanelUrl('/upcoming_courses') }}">{{ trans('update.upcoming_courses') }}</a></div>
                <div class="breadcrumb-item"><span>{{ trans('update.followers') }}</span></div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card-statistic">
                <div class="card-statistic__mask"></div>
                <div class="card-statistic__wrap">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ trans('update.total_followers') }}</span>
                        <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                            <x-iconsax-bul-user class="icons text-primary" width="24px" height="24px"/>
                        </div>
                    </div>
                    <h5 class="font-24 mt-12 line-height-1 text-black">{{ $totalFollowers }}</h5>
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

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                            <div class="input-group">
                                <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                            <div class="input-group">
                                <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="input-label">{{ trans('admin/main.role') }}</label>
                            <select name="role_id" class="form-control">
                                <option value="">{{ trans('admin/main.all_roles') }}</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @if($role->id == request()->get('role_id')) selected @endif>{{ $role->caption }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                   <div class="col-md-2 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>
                </div>
            </form>
        </div>
    </section>

    <div class="card">


        <div class="card-body">
            <div class="text-center">
                <table class="table custom-table font-14">
                    <tr>
                        <th width="20" class="text-left">ID</th>
                        <th class="text-left">{{ trans('admin/main.name') }}</th>
                        <th class="">{{ trans('admin/main.role') }}</th>
                        <th>{{ trans('update.followed_at') }}</th>
                        <th>{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($followers as $follower)
                        @php
                            $user = $follower->user;
                        @endphp

                        <tr>
                            <td class="text-left">{{ $user->id }}</td>
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar mr-2">
                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}">
                                    </figure>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1 font-weight-bold">{{ $user->full_name }}</div>

                                        @if($user->mobile)
                                            <div class="text-primary text-small font-600-bold">{{ $user->mobile }}</div>
                                        @endif

                                        @if($user->email)
                                            <div class="text-primary text-small font-600-bold">{{ $user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>{{ $user->role->caption }}</td>

                            <td>{{ dateTimeFormat($follower->created_at, 'j M Y') }}</td>

                            <td class="text-center mb-2" width="120">
                                @can('admin_users_impersonate')
                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/impersonate" target="_blank" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.login') }}">
                                        <i class="fa fa-user-shield"></i>
                                    </a>
                                @endcan

                                @can('admin_users_edit')
                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/edit" class="btn-transparent  text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endcan

                                @can('admin_upcoming_courses_followers')
                                    @include('admin.includes.delete_button',[
                                                'url' => getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/followers/'. $follower->id .'/delete'),
                                                'tooltip' => trans('update.unfollow_course'),
                                            ])
                                @endcan
                            </td>

                            <td>
                                     <div class="btn-group dropdown table-actions position-relative">
                                             <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                 <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                             </button
                                             <div class="dropdown-menu dropdown-menu-right">
                                                @can('admin_users_impersonate')
                                                     <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/impersonate" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                     <x-iconsax-lin-user-tag class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                         <span class="text-gray-500 ml-2">{{ trans('admin/main.login') }}</span>
                                                     </a>
                                                 @endcan
                                                 @can('admin_users_edit')
                                                     <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                     <x-iconsax-lin-user-edit class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                         <span class="text-gray-500 ml-2">{{ trans('admin/main.edit') }}</span>
                                                     </a>
                                                 @endcan
                                                 @can('admin_upcoming_courses_followers')
                                                     @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl('/upcoming_courses/'. $upcomingCourse->id .'/followers/'. $follower->id .'/delete'),
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans("admin/main.delete"),
                                                    'btnIcon' => 'trash',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-danger',
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
            {{ $followers->appends(request()->input())->links() }}
        </div>

    </div>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.students_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.students_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.students_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.students_hint_description_2')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.students_hint_title_3')}}</div>
                        <div class="text-small font-600-bold">{{trans('admin/main.students_hint_description_3')}}</div>
                    </div>
                </div>


            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')



    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>

    <script src="/assets/admin/js/parts/webinar_students.min.js"></script>
@endpush
