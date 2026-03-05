@extends('admin.layouts.app')

@section('content')
    <section class="section mb-48">
        <div class="section-header">
            <h1>{{ trans('update.bulk_imports') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.bulk_imports') }}</div>
            </div>
        </div>

        <div class="section-body">

            {{-- Top Stats --}}
            @include('admin.imports.history.top_stats')

            {{-- Filters --}}
            @include('admin.imports.history.filters')

            {{-- Lists --}}
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <div>
                                <h5 class="font-14 mb-0">{{ trans('update.bulk_import_history') }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.view_logs_and_details_of_previous_bulk_imports') }}</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">

                                @can('admin_gift_export')
                                    <div class="d-flex align-items-center gap-12">
                                        <a href="{{ getAdminPanelUrl("/imports/history/excel") }}?{{ http_build_query(request()->all()) }}" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                            <x-iconsax-lin-import-2 class="icons text-gray-500" width="18px" height="18px"/>
                                            <span class="ml-4 font-12">{{ trans('admin/main.export_xls') }}</span>
                                        </a>

                                        <a href="{{ getAdminPanelUrl("/imports") }}" class="btn bg-primary">
                                            <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                            <span class="ml-4 font-12 text-white">{{ trans('update.import_date') }}</span>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14 ">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th>{{ trans('update.data_type') }}</th>
                                        <th>{{ trans('update.total_records') }}</th>
                                        <th>{{ trans('update.valid_records') }}</th>
                                        <th>{{ trans('update.invalid_records') }}</th>
                                        <th>{{ trans('update.import_date') }}</th>
                                        <th width="120">{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($imports as $import)
                                        <tr class="text-center">

                                            <td class="text-left">
                                                <div class="d-flex align-items-center">
                                                    <div class="size-48 bg-gray-100 rounded-circle">
                                                        <img src="{{ $import->user->getAvatar(48) }}" alt="" class="img-cover rounded-circle">
                                                    </div>
                                                    <div class="ml-8">
                                                        <div class="">{{ $import->user->full_name }}</div>

                                                        @if(!empty($import->user->email))
                                                            <div class="mt-4 font-12 text-gray-500">{{ $import->user->email }}</div>
                                                        @endif

                                                        @if(!empty($import->user->mobile))
                                                            <div class="mt-4 font-12 text-gray-500">{{ $import->user->mobile }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="">{{ trans("update.{$import->data_type}") }}</span>
                                            </td>

                                            <td class="">
                                                <span class="">{{ $import->valid_items + $import->invalid_items }}</span>
                                            </td>

                                            <td>
                                                <span class="">{{ $import->valid_items }}</span>
                                            </td>

                                            <td>
                                                <span class="">{{ $import->invalid_items }}</span>
                                            </td>


                                            <td class="">
                                                <span class="">{{ dateTimeFormat($import->created_at, 'j M Y H:i') }}</span>
                                            </td>


                                            <td class="text-center mb-2" width="120">

                                                    <div class="btn-group dropdown table-actions position-relative">
                                                        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                        </button>

                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @include('admin.includes.delete_button',[
                                                                'url' => getAdminPanelUrl("/imports/history/{$import->id}/delete"),
                                                                'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                'btnText' => trans('admin/main.delete'),
                                                                'btnIcon' => 'close-square',
                                                                'iconType' => 'lin',
                                                                'iconClass' => 'text-danger mr-2',
                                                            ])
                                                        </div>
                                                    </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $imports->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
