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
            <section class="card">

                         <div class="card-header justify-content-between">
                            <div>
                               <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">
                            @can('admin_regions_create')
                                   <a href="{{ getAdminPanelUrl() }}/regions/new?type={{ $type }}" target="_blank" class="btn btn-primary">
                                       <x-iconsax-lin-add class="icons text-white" width="18px" height="18px"/>
                                       <span class="ml-4 font-12">{{ trans('admin/main.add_new') }}</span>
                                   </a>
                               @endcan
                            </div>
                       </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table custom-table text-center font-14">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.title') }}</th>

                                @if($type == \App\Models\Region::$country)
                                    <th class="text-center">{{ trans('update.provinces') }}</th>
                                @elseif($type == \App\Models\Region::$province)
                                    <th class="text-center">{{ trans('update.country') }}</th>
                                    <th class="text-center">{{ trans('update.cities') }}</th>
                                @elseif($type == \App\Models\Region::$city)
                                    <th class="text-center">{{ trans('update.country') }}</th>
                                    <th class="text-center">{{ trans('update.province') }}</th>
                                @elseif($type == \App\Models\Region::$district)
                                    <th class="text-center">{{ trans('update.country') }}</th>
                                    <th class="text-center">{{ trans('update.province') }}</th>
                                    <th class="text-center">{{ trans('update.city') }}</th>
                                @endif

                                <th class="text-center">{{ trans('admin/main.instructor') }}</th>
                                <th class="text-center">{{ trans('admin/main.date') }}</th>
                                <th class="text-center">{{ trans('admin/main.actions') }}</th>
                            </tr>

                            @foreach($regions as $region)

                                <tr>
                                    <td>{{ $region->title }}</td>

                                    @if($type == \App\Models\Region::$country)
                                        <td>{{ $region->countryProvinces->count() }}</td>

                                        <td>{{ $region->countryUsers->count() }}</td>
                                    @elseif($type == \App\Models\Region::$province)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->provinceCities->count() }}</td>

                                        <td>{{ $region->provinceUsers->count() }}</td>
                                    @elseif($type == \App\Models\Region::$city)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->province->title }}</td>
                                        <td>{{ $region->cityUsers->count() }}</td>
                                    @elseif($type == \App\Models\Region::$district)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->province->title }}</td>
                                        <td>{{ $region->city->title }}</td>
                                        <td>{{ $region->districtUsers->count() }}</td>
                                    @endif

                                    <td>{{ dateTimeFormat($region->created_at, 'Y M j | H:i') }}</td>

                                    <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_regions_edit')
                <a href="{{ getAdminPanelUrl() }}/regions/{{ $region->id }}/edit"
                   class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_regions_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/regions/'.$region->id.'/delete',
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
                    {{ $regions->appends(request()->input())->links() }}
                </div>
            </section>
        </div>
    </section>
@endsection
