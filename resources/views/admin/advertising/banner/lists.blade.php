@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle}}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">                   

                        <div class="card-header justify-content-between">
                             <div>
                                <h5 class="font-14 mb-0">{{ $pageTitle }}</h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500">{{ trans('update.manage_all_items_in_a_single_place') }}</p>
                            </div>
                            
                             <div class="d-flex align-items-center gap-12">

                             @can('admin_advertising_banners_create')
                                <a href="{{ getAdminPanelUrl() }}/advertising/banners/new" class="btn btn-primary">{{ trans('admin/main.new_banner') }}</a>
                            @endcan

                             </div>
                        </div>
                    

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('admin/main.position') }}</th>
                                        <th class="text-center">{{ trans('admin/main.banner_size') }}</th>
                                        <th class="text-center">{{ trans('admin/main.published') }}</th>
                                        <th class="text-center">{{ trans('admin/main.created_at') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($banners as $banner)
                                        <tr>
                                            <td>{{ $banner->title }}</td>
                                            <td class="text-center">{{ $banner->position }}</td>
                                            <td class="text-center">{{ \App\Models\AdvertisingBanner::$size[$banner->size] }}</td>
                                            <td class="text-center">
                                                @if($banner->published)
                                                    <span class="text-success fas fa-check"></span>
                                                @else
                                                    <span class="text-danger fas fa-times"></span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ dateTimeFormat($banner->created_at, 'Y M j | H:i') }}</td>
                                            <td>
    <div class="btn-group dropdown table-actions position-relative">
        <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
            <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            @can('admin_advertising_banners_edit')
                <a href="{{ getAdminPanelUrl() }}/advertising/banners/{{ $banner->id }}/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                    <x-iconsax-lin-edit-2 class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                    <span class="text-gray-500 font-14">{{ trans('admin/main.edit') }}</span>
                </a>
            @endcan

            @can('admin_advertising_banners_delete')
                @include('admin.includes.delete_button',[
                    'url' => getAdminPanelUrl().'/advertising/banners/'.$banner->id.'/delete',
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
                            {{ $banners->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
