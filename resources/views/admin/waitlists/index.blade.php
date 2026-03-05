@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{trans('update.waitlists')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('update.waitlists')}}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="card">

                <div class="card-header justify-content-between">

                    <div>
                        <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_waitlists_in_a_single_place') }}</p>
                    </div>

                    @can('admin_waitlists_exports')
                        <div class="d-flex align-items-center gap-12">
                            <a href="{{ getAdminPanelUrl('/waitlists/export') }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                            </a>
                        </div>
                    @endcan

                </div>


                <div class="card-body">
                    <table class="table custom-table font-14">
                        <tr>
                            <th class="text-left">{{ trans('admin/main.course') }}</th>
                            <th class="">{{ trans('update.members') }}</th>
                            <th class="">{{ trans('update.registered_members') }}</th>
                            <th class="">{{ trans('update.last_submission') }}</th>
                            <th class="text-left">{{ trans('admin/main.actions') }}</th>
                        </tr>

                        @foreach($waitlists as $waitlist)
                            <tr>
                                <td class="text-left font-14">
                                    <a class="text-dark mt-0 mb-1" href="{{ $waitlist->getUrl() }}">{{ $waitlist->title }}</a>
                                    @if(!empty($waitlist->category->title))
                                        <div class="text-small font-weight-normal">{{ $waitlist->category->title }}</div>
                                    @else
                                        <div class="text-small text-warning">{{trans('admin/main.no_category')}}</div>
                                    @endif
                                </td>

                                <td>{{ $waitlist->members }}</td>

                                <td>{{ $waitlist->registered_members }}</td>

                                <td>
                                    {{ !empty($waitlist->last_submission) ? dateTimeFormat($waitlist->last_submission, 'j M Y H:i') : '-' }}
                                </td>

                                <td class="text-left">
                                    <div class="btn-group dropdown table-actions position-relative">
                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can('admin_waitlists_clear_list')
                                                @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/waitlists/'.$waitlist->id.'/clear_list',
                                                    'btnClass' => 'dropdown-item text-warning mb-3 py-3 px-0 font-14',
                                                    'btnText' => trans("update.clear_list"),
                                                    'btnIcon' => 'close-square',
                                                    'iconType' => 'lin',
                                                    'iconClass' => 'text-warning mr-2',
                                                ])
                                            @endcan

                                            @can('admin_waitlists_users')
                                                <a href="{{ getAdminPanelUrl() }}/waitlists/{{ $waitlist->id }}/view_list" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-eye class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('update.view_list') }}</span>
                                                </a>
                                            @endcan

                                            @can('admin_waitlists_exports')
                                                <a href="{{ getAdminPanelUrl() }}/waitlists/{{ $waitlist->id }}/export_list" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-export class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('update.export_list') }}</span>
                                                </a>
                                            @endcan

                                            @can('admin_waitlists_disable')
                                                @include('admin.includes.delete_button',[
                                                    'url' => getAdminPanelUrl().'/waitlists/'.$waitlist->id.'/disable',
                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                    'btnText' => trans("update.disable_waitlist"),
                                                    'btnIcon' => 'lock',
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

                <div class="card-footer text-center">
                    {{ $waitlists->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
