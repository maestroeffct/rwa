@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.events') }}</div>
            </div>
        </div>

        <div class="section-body">
            {{-- Top Stats --}}
            @include('admin.events.lists.top_stats')

            {{-- Filters --}}
            @include('admin.events.lists.filters')


            <div class="card">
                <div class="card-header justify-content-between">
                    <div>
                        <h5 class="font-14 mb-0">{{ trans('update.events') }}</h5>
                        <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_events_in_a_single_place') }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-12">
                        @can('admin_events_lists_export_excel')
                            <a href="{{ getAdminPanelUrl('/events/export-excel?'. http_build_query(request()->all())) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                            </a>
                        @endcan

                        @can('admin_events_create')
                            <a href="{{ getAdminPanelUrl('/events/create') }}" target="_blank" class="btn btn-primary">
                                <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                <span class="ml-4 font-12">{{ trans('update.new_event') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div>
                        <table class="table custom-table font-14 ">
                            <tr>
                                <th class="text-left">{{trans('update.event')}}</th>
                                <th class="text-left">{{trans('update.provider')}}</th>
                                <th>{{trans('update.event_type')}}</th>
                                <th>{{trans('admin/main.price')}}</th>
                                <th>{{trans('admin/main.capacity')}}</th>
                                <th>{{trans('update.sold_tickets')}}</th>
                                <th>{{trans('admin/main.start_date')}}</th>
                                <th>{{trans('admin/main.created_at')}}</th>
                                <th>{{trans('admin/main.status')}}</th>
                                <th width="120">{{trans('admin/main.actions')}}</th>
                            </tr>

                            @foreach($events as $event)
                                @include('admin.events.lists.table_items')
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $events->appends(request()->input())->links() }}
                </div>

            </div>

        </div>
    </section>
@endsection
