@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>


        <div class="section-body">
            {{-- Top Stats --}}
            @include('admin.meeting_packages.lists.top_stats')

            {{-- Filters --}}
            @include('admin.meeting_packages.lists.filters')


            <div class="card">
                <div class="card-header justify-content-between">
                    <div>
                        <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.view_and_manage_your_meeting_packages') }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-12">
                        @can('admin_meeting_packages_create')
                            <a href="{{ getAdminPanelUrl("/meeting-packages/create") }}" target="_blank" class="btn btn-primary">
                                <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                <span class="ml-4 font-12">{{ trans('update.new_package') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table custom-table font-14">
                            <tr>
                                <th class="text-left">{{ trans('public.title') }}</th>
                                <th class="text-left">{{ trans('update.creator_and_role') }}</th>
                                <th class="text-center">{{ trans('public.duration') }}</th>
                                <th class="text-center">{{ trans('public.price') }}</th>
                                <th class="text-center">{{ trans('panel.sales') }}</th>
                                <th class="text-center">{{ trans('public.status') }}</th>
                                <th class="text-center">{{ trans('update.created_date') }}</th>
                                <th class="text-right">{{ trans('update.actions') }}</th>
                            </tr>

                            @foreach($meetingPackages as $meetingPackage)
                                <tr>
                                    {{-- Title --}}
                                    <td class="text-left">
                                        <div class="d-flex flex-column">
                                            <span class="">{{ $meetingPackage->title }}</span>
                                            <span class="mt-4 font-12 text-gray-500">{{ trans('update.n_sessions', ['count' => $meetingPackage->sessions]) }} ({{ convertMinutesToHourAndMinute($meetingPackage->session_duration) }} {{ trans('home.hours') }})</span>
                                        </div>
                                    </td>

                                    {{-- Creator --}}
                                    <td class="text-left">
                                        <div class="d-flex align-items-center">
                                            <div class="size-32 rounded-circle">
                                                <img src="{{ $meetingPackage->creator->getAvatar(32) }}" alt="" class="img-cover rounded-circle">
                                            </div>
                                            <div class="ml-8">
                                                <div class="text-dark">{{ $meetingPackage->creator->full_name }}</div>
                                                <span class="d-block mt-4 font-12 text-gray-500">{{ $meetingPackage->creator->role->caption }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Duration --}}
                                    <td class="text-center">
                                        <span class="">{{ $meetingPackage->duration }} {{ trans("update.{$meetingPackage->duration_type}") }}</span>
                                    </td>

                                    {{-- Price --}}
                                    <td class="text-center">
                                        <span class="">{{ !empty($meetingPackage->price) ? handlePrice($meetingPackage->price) : trans('update.free') }}</span>
                                    </td>

                                    {{-- Sales --}}
                                    <td class="text-center">
                                        @if($meetingPackage->sales->count() > 0)
                                            <div class="d-flex-center flex-column text-center">
                                                <span class="">{{ $meetingPackage->sales->count() }}</span>
                                                <span class="mt-4 font-12 text-gray-500">{{ handlePrice($meetingPackage->sales->sum("paid_amount")) }}</span>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center d-flex-center">
                                        @if($meetingPackage->enable)
                                            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 bg-success-30 text-success">{{ trans('panel.active') }}</div>
                                        @else
                                            <div class="d-inline-flex-center py-6 px-8 rounded-8 font-12 bg-danger-30 text-danger">{{ trans('public.disabled') }}</div>
                                        @endif
                                    </td>

                                    {{-- created_date --}}
                                    <td class="text-center">
                                        <span class="">{{ dateTimeFormat($meetingPackage->created_at, 'j M Y H:i') }}</span>
                                    </td>

                                    <td class="text-right" width="150px">
                                        <div class="btn-group dropdown table-actions position-relative">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                @can('admin_meeting_packages_create')
                                                    <a href="{{ getAdminPanelUrl("/meeting-packages/{$meetingPackage->id}/edit") }}"
                                                       class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                                                    </a>
                                                @endcan

                                                @can('admin_meeting_packages_delete')
                                                    @include('admin.includes.delete_button',[
                                                        'url' => getAdminPanelUrl("/meeting-packages/{$meetingPackage->id}/delete"),
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
                    {{ $meetingPackages->appends(request()->input())->links() }}
                </div>

            </div>

        </div>
    </section>
@endsection
