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
                           
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped font-14" id="datatable-basic">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.title') }}</th>
                                <th class="text-center">{{ trans('admin/main.created_date') }}</th>
                                <th>{{ trans('public.controls') }}</th>
                            </tr>

                            @foreach($themeHeaders as $themeHeader)
                                <tr>
                                    <td>{{ $themeHeader->title }}</td>

                                    <td class="text-center">{{ dateTimeFormat($themeHeader->created_at, 'j M Y') }}</td>


                                    <td width="100">
                                        <a href="{{ getAdminPanelUrl("/themes/headers/{$themeHeader->id}/edit") }}" class="btn-transparent  text-primary mr-1" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                        <x-iconsax-lin-edit-2 class="icons text-gray-500" width="18px" height="18px"/>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $themeHeaders->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </section>

@endsection
