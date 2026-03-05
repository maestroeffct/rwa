@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    {{ $pageTitle }}
                </div>
            </div>
        </div>

        <div class="section-body">

            {{-- Filters --}}
            @include('admin.attendances.details.top_stats')


            {{-- Filters --}}
            @include('admin.attendances.details.filters')

            {{-- Lists --}}
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">


                        <div class="card-header justify-content-between">
                            <div>
                                <h5 class="font-14 mb-0">{{ trans('update.live_session_attendance_details') }}</h5>
                                <p class="font-12 mb-0 text-gray-500">{{ trans('update.review_and_manage_live_session_attendance_status') }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">

                                @can('admin_gift_export')
                                    <div class="d-flex align-items-center gap-12">
                                        <a href="{{ getAdminPanelUrl("/attendances/{$session->id}/details/excel") }}?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                            <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                            <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('update.joined_date') }}</th>
                                        <th class="text-center">{{ trans('update.attendance_status') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($students as $student)
                                        @include('admin.attendances.details.table_items')
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $students->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
