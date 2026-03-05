@extends('admin.layouts.app')

@push('styles_top')


@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a>{{ trans('admin/main.students') }}</a></div>
                <div class="breadcrumb-item"><a href="#">{{ $pageTitle }}</a></div>
            </div>
        </div>
    </section>

    <div class="section-body">
        <section class="card">
            <div class="card-body">
                <form method="get" class="mb-0">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.search') }}</label>
                                <input name="full_name" type="text" class="form-control" value="{{ request()->get('full_name') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                </div>
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
                                    <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                                </div>

                                <div class="d-flex align-items-center gap-12">

                                    @can('admin_users_not_access_content_toggle')
                                    <a type="button" id="addNewUserToNotaccess" class="btn btn-primary">
                                    <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                    <span class="ml-4 text-white font-12">{{ trans('admin/main.add_new') }}</span>
                                    </a>
                                    @endcan

                                </div>
                            </div>




        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table custom-table font-14">
                    <tr>
                        <th class="text-center" width="30">ID</th>
                        <th class="text-left">{{ trans('admin/main.name') }}</th>
                        <th>{{ trans('admin/main.register_date') }}</th>
                        <th>{{ trans('admin/main.status') }}</th>
                        <th>{{ trans('update.access_to_content') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($users as $user)

                        <tr>
                            <td class="text-center">{{ $user->id }}</td>
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar mr-2">
                                        <img src="{{ $user->getAvatar() }}" alt="{{ $user->full_name }}">
                                    </figure>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1">{{ $user->full_name }}</div>

                                        @if($user->mobile)
                                            <div class="text-small font-12 text-gray-500">{{ $user->mobile }}</div>
                                        @endif

                                        @if($user->email)
                                            <div class="text-small font-12 text-gray-500">{{ $user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>{{ dateTimeFormat($user->created_at, 'j M Y | H:i') }}</td>

                            <td>
                                @if($user->ban and !empty($user->ban_end_at) and $user->ban_end_at > time())
                                <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.ban') }}</span>
                                    <div class="text-small font-12 text-gray-500">Until {{ dateTimeFormat($user->ban_end_at, 'Y/m/j') }}</div>
                                @else
                                <span class="badge-status {{ ($user->status == 'active') ? 'text-success bg-success-30' : 'text-warning bg-warning-30' }}">{{ trans('admin/main.'.$user->status) }}</span>
                                @endif
                            </td>

                            <td>
                                <div class="mt-0 mb-1 text-danger">{{ trans('admin/main.no') }}</div>
                            </td>

                            <td class="text-center">
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

            @can('admin_users_not_access_content_toggle')
                <a href="{{ getAdminPanelUrl() }}/users/not-access-to-content/{{ $user->id }}/active"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-arrow-up class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.active') }}</span>
                </a>
            @endcan

            @can('admin_users_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/users/'.$user->id.'/delete',
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
            {{ $users->appends(request()->input())->links() }}
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

    <div id="addUserToNotAccessModal" class="d-none">
        <h3 class="section-title after-line">{{ trans('update.add_to_not_access') }}</h3>
        <div class="mt-25">
            <form action="{{ getAdminPanelUrl() }}/users/not-access-to-content/store" method="post">

                <div class="form-group">
                    <label class="input-label d-block">{{ trans('admin/main.user') }}</label>
                    <select name="user_id" class="form-control user-search" data-placeholder="{{ trans('public.search_user') }}">

                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="d-flex align-items-center justify-content-end mt-3">
                    <button type="button" class="js-save-add-user-to-not-access btn btn-sm btn-primary">{{ trans('public.save') }}</button>
                    <button type="button" class="close-swl btn btn-sm btn-danger ml-2">{{ trans('public.close') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts_bottom')



    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>

    <script src="/assets/admin/js/parts/not_access_to_content.min.js"></script>
@endpush
