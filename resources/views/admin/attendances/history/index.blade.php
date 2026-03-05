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
            @include('admin.attendances.history.top_stats')


            {{-- Filters --}}
            @include('admin.attendances.history.filters')

            {{-- Lists --}}
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">


                        <div class="card-header justify-content-between">
                            <div>
                                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                                <p class="font-12 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">

                                @can('admin_gift_export')
                                    <div class="d-flex align-items-center gap-12">
                                        <a href="{{ getAdminPanelUrl() }}/attendances/excel?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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
                                        <th class="text-left">{{ trans('update.course_and_session') }}</th>
                                        <th class="text-left">{{ trans('admin/main.instructor') }}</th>
                                        <th>{{ trans('admin/main.start_date') }}</th>
                                        <th>{{ trans('update.session_provider') }}</th>
                                        <th>{{ trans('update.students_count') }}</th>
                                        <th>{{ trans('update.present') }}</th>
                                        <th>{{ trans('update.late') }}</th>
                                        <th>{{ trans('update.absent') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($sessions as $session)
                                        @include('admin.attendances.history.table_items')
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $sessions->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
