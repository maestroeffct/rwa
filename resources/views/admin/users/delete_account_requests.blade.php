@extends('admin.layouts.app')

@push('libraries_top')

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

        <div class="card-body">
            <div>
                <table class="table custom-table font-14">
                    <tr>
                        <th>ID</th>
                        <th class="text-left">{{ trans('admin/main.name') }}</th>
                        <th>{{ trans('admin/main.register_date') }}</th>
                        <th>{{ trans('admin/main.status') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($requests as $request)

                        <tr>
                            <td>{{ $request->user->id }}</td>
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar mr-2">
                                        <img src="{{ $request->user->getAvatar() }}" alt="{{ $request->user->full_name }}">
                                    </figure>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1">{{ $request->user->full_name }}</div>

                                        @if($request->user->mobile)
                                            <div class="text-small font-12 text-gray-500">{{ $request->user->mobile }}</div>
                                        @endif

                                        @if($request->user->email)
                                            <div class="text-small font-12 text-gray-500">{{ $request->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>{{ dateTimeFormat($request->user->created_at, 'j M Y | H:i') }}</td>

                            <td>
                                @if($request->user->ban and !empty($request->user->ban_end_at) and $request->user->ban_end_at > time())
                                    <div class="mt-0 mb-1 font-weight-bold text-danger">{{ trans('admin/main.ban') }}</div>
                                    <div class="text-small font-600-bold">Until {{ dateTimeFormat($request->user->ban_end_at, 'Y/m/j') }}</div>
                                @else
                                <span class="badge-status {{ ($request->user->status == 'active') ? 'text-success bg-success-30' : 'text-warning bg-warning-30' }}">{{ trans('admin/main.'.$request->user->status) }} </span>
                                @endif
                            </td>

                            <td class="text-center" width="120">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_users_impersonate')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $request->user->id }}/impersonate" 
                   target="_blank"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                </a>
            @endcan

            @can('admin_users_edit')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $request->user->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_delete_account_requests_confirm')
                <div class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl().'/users/delete-account-requests/'.$request->id.'/confirm',
                        'btnClass' => 'text-success font-14',
                        'btnText' => trans('update.confirm'),
                        'btnIcon' => 'tick-circle',
                        'iconType' => 'lin',
                        'iconClass' => 'text-success mr-2'
                    ])
                </div>

                <div class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4">
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl().'/users/delete-account-requests/'.$request->id.'/delete',
                        'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                        'btnText' => trans('public.delete'),
                        'btnIcon' => 'trash',
                        'iconType' => 'lin',
                        'iconClass' => 'text-danger mr-2'
                    ])
                </div>
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
            {{ $requests->appends(request()->input())->links() }}
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
