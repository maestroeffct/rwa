@extends('admin.layouts.app')

@push('styles_top')

@endpush


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a>{{ trans('admin/main.users') }}</a></div>
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
                                <input name="search" type="text" class="form-control" value="{{ request()->get('search') }}" placeholder="{{ trans('public.search_user') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.session_status') }}</label>
                                <select name="session_status" class="form-control">
                                    <option value="">{{ trans('update.choose_session_status') }}</option>
                                    <option value="open" {{ (request()->get('session_status') == "open") ? 'selected' : '' }}>{{ trans('admin/main.open') }}</option>
                                    <option value="ended" {{ (request()->get('session_status') == "ended") ? 'selected' : '' }}>{{ trans('update.ended') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.start_login_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="from" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="input-label">{{ trans('update.end_login_date') }}</label>
                                <div class="input-group">
                                    <input type="date" id="to" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group mt-1">
                                <label class="input-label mb-4"> </label>
                                <input type="submit" class="text-center btn btn-primary w-100" value="{{ trans('admin/main.show_results') }}">
                            </div>
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
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_login_history_in_a_single_place') }}</p>
                            </div>
            @can('admin_user_login_history_export')
                <div class="d-flex align-items-center gap-12">
                    <a href="{{ getAdminPanelUrl("/users/login-history/export") }}?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                        <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                        <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                    </a>
                </div>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive text-center">
                <table class="table custom-table font-14">
                    <tr>
                        <th>ID</th>
                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                        <th>{{ trans('update.os') }}</th>
                        <th>{{ trans('update.browser') }}</th>
                        <th>{{ trans('update.device') }}</th>
                        <th>{{ trans('update.ip_address') }}</th>
                        <th>{{ trans('update.country') }}</th>
                        <th>{{ trans('update.city') }}</th>
                        <th>{{ trans('update.lat,long') }}</th>
                        <th>{{ trans('update.session_start') }}</th>
                        <th>{{ trans('update.session_end') }}</th>
                        <th>{{ trans('public.duration') }}</th>
                        <th width="120">{{ trans('admin/main.actions') }}</th>
                    </tr>

                    @foreach($sessions as $session)

                        <tr>
                            <td>{{ $session->user->id }}</td>
                            <td class="text-left">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar mr-2">
                                        <img src="{{ $session->user->getAvatar() }}" alt="{{ $session->user->full_name }}">
                                    </figure>
                                    <div class="media-body ml-1">
                                        <div class="mt-0 mb-1">{{ $session->user->full_name }}</div>

                                        @if($session->user->mobile)
                                            <div class="text-small font-12 text-gray-500">{{ $session->user->mobile }}</div>
                                        @endif

                                        @if($session->user->email)
                                            <div class="text-small font-12 text-gray-500">{{ $session->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>{{ $session->os ?? '-' }}</td>

                            <td>{{ $session->browser ?? '-' }}</td>

                            <td>{{ $session->device ?? '-' }}</td>

                            <td>{{ $session->ip ?? '-' }}</td>

                            <td>{{ $session->country ?? '-' }}</td>

                            <td>{{ $session->city ?? '-' }}</td>

                            <td>{{ $session->location ?? '-' }}</td>

                            <td>{{ dateTimeFormat($session->session_start_at, 'j M Y H:i') }}</td>

                            <td class="font-12">{{ !empty($session->session_end_at) ? dateTimeFormat($session->session_end_at, 'j M Y H:i') : '-' }}</td>

                            <td class="font-12">{{ $session->getDuration() }}</td>

                            <td class="text-center" width="120">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_users_impersonate')
                @if(!$session->user->isAdmin())
                    <a href="{{ getAdminPanelUrl() }}/users/{{ $session->user->id }}/impersonate"
                       target="_blank"
                       class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                        <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                        <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                    </a>
                @endif
            @endcan

            @can('admin_users_edit')
                <a href="{{ getAdminPanelUrl() }}/users/{{ $session->user->id }}/edit"
                   target="_blank"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('update.user_edit') }}</span>
                </a>
            @endcan

            @can('admin_user_ip_restriction_create')
                @if(!empty($session->ip))
                    <button type="button"
                            data-path="{{ getAdminPanelUrl() }}/users/ip-restriction/get-form?full_ip={{ $session->ip }}"
                            class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 js-add-restriction">
                        <x-iconsax-lin-minus-cirlce class="icons text-danger mr-2" width="18px" height="18px"/>
                        <span class="text-danger font-14">{{ trans('update.block_ip') }}</span>
                    </button>
                @endif
            @endcan

            @can('admin_user_login_history_end_session')
                @if(empty($session->session_end_at))
                    @include('admin.includes.delete_button',[
                        'url' => getAdminPanelUrl().'/users/login-history/'.$session->id.'/end-session',
                        'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                        'btnText' => trans('update.end_session'),
                        'btnIcon' => 'logout',
                        'iconType' => 'lin',
                        'iconClass' => 'text-danger mr-2'
                    ])
                @endif
            @endcan

            @can('admin_user_login_history_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/users/login-history/'.$session->id.'/delete',
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
            {{ $sessions->appends(request()->input())->links() }}
        </div>
    </div>


    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.login_history_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.login_history_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.login_history_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('update.login_history_hint_description_2')}}</div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.login_history_hint_title_3')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.login_history_hint_description_3')}}</div>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')


    <script src="/assets/admin/js/parts/ip-restriction.min.js"></script>
@endpush
