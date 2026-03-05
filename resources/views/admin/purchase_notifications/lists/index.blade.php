@extends('admin.layouts.app')

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

            <div class="card">

                <div class="card-header justify-content-between">
                             <div>
                                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                            </div>
                             <div class="d-flex align-items-center gap-12">
                                 @can('admin_purchase_notifications_create')
                                    <a href="{{ getAdminPanelUrl("/purchase_notifications/create") }}" target="_blank" class="btn btn-primary">
                                        <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                        <span class="ml-4 font-12">{{ trans('update.new_notification') }}</span>
                                    </a>
                                @endcan
                             </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.title') }}</th>
                                <th class="text-center">{{ trans('update.displayed_time') }}</th>
                                <th class="text-center">{{ trans('update.contents') }}</th>
                                <th class="text-center">{{ trans('admin/main.users') }}</th>
                                <th class="text-center">{{ trans('admin/main.start_date') }}</th>
                                <th class="text-center">{{ trans('admin/main.end_date') }}</th>
                                <th class="text-center">{{ trans('admin/main.status') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->title }}</td>

                                    <td class="text-center">{{ $notification->display_time }}</td>

                                    <td class="text-center">{{ $notification->webinars_count + $notification->bundles_count + $notification->products_count }}</td>

                                    <td class="text-center">
                                        {{ !empty($notification->users) ? count(explode(',', $notification->users)) : 0 }}
                                    </td>

                                    <td class="text-center">{{ !empty($notification->start_at) ? dateTimeFormat($notification->start_at, 'j M Y') : '-' }}</td>

                                    <td class="text-center">{{ !empty($notification->end_at) ? dateTimeFormat($notification->end_at, 'j M Y') : '-' }}</td>

                                    <td class="text-center">
                                        @if(!$notification->enable)
                                            <span class="badge-status text-danger bg-danger-30">{{ trans('admin/main.disabled') }}</span>
                                        @elseif(!empty($notification->start_at) and $notification->start_at > time())
                                            <span class="badge-status text-warning bg-warning-30">{{ trans('admin/main.pending') }}</span>
                                        @elseif(!empty($notification->end_at) and $notification->end_at < time())
                                            <span class="badge-status text-danger bg-danger-30">{{ trans('panel.expired') }}</span>
                                        @else
                                            <span class="badge-status text-success bg-success-30">{{ trans('admin/main.active') }}</span>
                                        @endif
                                    </td>

                                    <td width="100">
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_purchase_notifications_edit')
                <a href="{{ getAdminPanelUrl("/purchase_notifications/{$notification->id}/edit") }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_purchase_notifications_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl("/purchase_notifications/{$notification->id}/delete"),
                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                    'btnText' => trans("admin/main.delete"),
                    'btnIcon' => 'trash',
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
                </div>

                <div class="card-footer text-center">
                    {{ $notifications->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>


@endsection
