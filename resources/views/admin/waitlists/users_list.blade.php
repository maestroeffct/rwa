@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $webinarTitle }} - {{ trans('update.waitlists') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('update.waitlists')}}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" class="form-control" name="search" placeholder="" value="{{ request()->get('search') }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('update.registration_status')}}</label>
                                    <select name="registration_status" class="form-control">
                                        <option value="">{{trans('admin/main.all')}}</option>
                                        <option value="registered" {{ (request()->get('registration_status') == "registered") ? 'selected' : '' }}>{{ trans('update.registered') }}</option>
                                        <option value="unregistered" {{ (request()->get('registration_status') == "unregistered") ? 'selected' : '' }}>{{ trans('update.unregistered') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-center ">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">{{trans('admin/main.show_results')}}</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="card">

                <div class="card-header justify-content-between">

                    <div>
                        <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-12">
                        @can('admin_waitlists_exports')
                            <a href="{{ getAdminPanelUrl("/waitlists/{$waitlistId}/export_list") }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <table class="table custom-table font-14" id="datatable-details">
                        <tr>
                            <th class="text-left">{{ trans('admin/main.name') }}</th>
                            <th class="">{{ trans('auth.email') }}</th>
                            <th class="">{{ trans('public.phone') }}</th>
                            <th class="">{{ trans('update.registration_status') }}</th>
                            <th class="">{{ trans('update.submission_date') }}</th>
                            <th class="text-left">{{ trans('admin/main.actions') }}</th>
                        </tr>

                        @foreach($waitlists as $waitlist)
                            <tr>
                                <td class="text-left">{{ !empty($waitlist->user) ? $waitlist->user->full_name : $waitlist->full_name }}</td>

                                <td>{{ !empty($waitlist->user) ? $waitlist->user->email : $waitlist->email }}</td>

                                <td>{{ !empty($waitlist->user) ? $waitlist->user->mobile : $waitlist->phone }}</td>

                                <td>
                                    @if(!empty($waitlist->user))
                                        <span class="">{{ trans('update.registered') }}</span>
                                    @else
                                        <span class="">{{ trans('update.unregistered') }}</span>
                                    @endif
                                </td>

                                <td>{{ dateTimeFormat($waitlist->created_at, 'j M Y H:i') }}</td>

                                <td>
                                    <div class="btn-group dropdown table-actions position-relative">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <!-- Delete Action -->
                                            @include('admin.includes.delete_button',[
                                                'url' => getAdminPanelUrl().'/waitlists/items/'.$waitlist->id.'/delete',
                                                'btnClass' => 'dropdown-item text-danger mb-3 py-3 px-0 font-14',
                                                'btnText' => trans('admin/main.delete'),
                                                'btnIcon' => 'trash',
                                                'iconType' => 'lin',
                                                'iconClass' => 'text-danger mr-2',
                                            ])

                                            @if(!empty($waitlist->user))
                                                <!-- Impersonate User -->
                                                @can('admin_users_impersonate')
                                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $waitlist->user->id }}/impersonate"
                                                       target="_blank"
                                                       class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                                                    </a>
                                                @endcan

                                                <!-- Edit User -->
                                                @can('admin_users_edit')
                                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $waitlist->user->id }}/edit"
                                                       class="dropdown-item d-flex align-items-center mb-0 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                    </a>
                                                @endcan
                                            @endif
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="card-footer text-center">
                    {{ $waitlists->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
